<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Property;
use App\Models\BuilderProject;
use App\Models\BrokerProfile;
use App\Models\PropertyMedia;
use App\Models\MetroStation;
use App\Models\OwnerListing;
use App\Models\Auction;
use App\Models\AuctionRegistration;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API endpoints
Route::prefix('v1')->group(function () {

    // Properties & Marketplace
    Route::get('/properties', function (Request $request) {
        $query = Property::query()->with(['images', 'features', 'builderProject']);

        if ($request->filled('city')) $query->where('city', $request->city);
        if ($request->filled('type')) $query->where('property_type', $request->type);
        if ($request->filled('min_price')) $query->where('price', '>=', $request->min_price);
        if ($request->filled('max_price')) $query->where('price', '<=', $request->max_price);
        if ($request->filled('bedrooms')) $query->where('bedrooms', '>=', $request->bedrooms);
        if ($request->filled('furnishing')) $query->where('furnishing', $request->furnishing);
        if ($request->filled('search')) $query->search($request->search);

        return $query->paginate($request->get('per_page', 20));
    });

    Route::get('/properties/{property}', function (Property $property) {
        return $property->load(['images', 'features', 'reviews', 'builderProject', 'propertyMedia', 'neighborhood']);
    });

    // Property Media / Reels
    Route::get('/properties/{property}/media', function (Property $property) {
        return $property->propertyMedia()->ordered()->get();
    });

    Route::get('/reels', function (Request $request) {
        $query = PropertyMedia::reels()->with('property')->ordered();
        if ($request->filled('city')) {
            $query->whereHas('property', fn($q) => $q->where('city', $request->city));
        }
        return $query->paginate($request->get('per_page', 20));
    });

    Route::post('/media/{media}/view', function (PropertyMedia $media) {
        $media->incrementViews();
        return response()->json(['views_count' => $media->fresh()->views_count]);
    });

    Route::post('/media/{media}/like', function (PropertyMedia $media) {
        $media->incrementLikes();
        return response()->json(['likes_count' => $media->fresh()->likes_count]);
    });

    // Builder Projects
    Route::get('/builders', function (Request $request) {
        $query = BuilderProject::query();
        if ($request->filled('city')) $query->inCity($request->city);
        if ($request->filled('status')) $query->where('status', $request->status);
        return $query->paginate($request->get('per_page', 20));
    });

    Route::get('/builders/{builderProject}', function (BuilderProject $builderProject) {
        return $builderProject->load('properties.images');
    });

    // Brokers
    Route::get('/brokers', function (Request $request) {
        $query = BrokerProfile::query()->with('user');
        if ($request->boolean('verified', true)) $query->verified();
        if ($request->filled('area')) $query->inArea($request->area);
        if ($request->filled('specialization')) $query->where('specialization', $request->specialization);
        return $query->orderByDesc('rating')->paginate($request->get('per_page', 20));
    });

    Route::get('/brokers/{brokerProfile}', function (BrokerProfile $brokerProfile) {
        return $brokerProfile->load('user');
    });

    // Metro Stations
    Route::get('/metro-stations', function (Request $request) {
        $query = MetroStation::operational();
        if ($request->filled('city')) $query->inCity($request->city);
        if ($request->filled('lat') && $request->filled('lng')) {
            $query->nearby($request->lat, $request->lng, $request->get('radius', 5));
        }
        return $query->get();
    });

    // Map Discovery
    Route::get('/map/properties', function (Request $request) {
        $request->validate(['lat' => 'required|numeric', 'lng' => 'required|numeric']);
        return Property::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->nearby($request->lat, $request->lng, $request->get('radius', 5))
            ->with('images')
            ->limit(100)
            ->get();
    });

    // Auctions (public listing)
    Route::get('/auctions', function () {
        return Auction::with('property')
            ->where('status', 'active')
            ->where('end_time', '>', now())
            ->orderBy('end_time')
            ->get();
    });

    Route::get('/auctions/{auction}', function (Auction $auction) {
        return $auction->load(['property.images', 'bids.user', 'registrations']);
    });
});

// Authenticated API endpoints
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    // Owner Listings
    Route::get('/owner/listings', function (Request $request) {
        return OwnerListing::where('user_id', $request->user()->id)
            ->with('property.images')
            ->latest()
            ->paginate(10);
    });

    Route::post('/owner/listings', function (Request $request) {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'listing_type' => 'required|in:sell,rent',
            'asking_price' => 'required|numeric|min:0',
            'is_zero_brokerage' => 'boolean',
            'contact_phone' => 'required|string',
            'contact_whatsapp' => 'nullable|string',
        ]);
        $data['user_id'] = $request->user()->id;
        $data['verification_status'] = 'pending';
        return OwnerListing::create($data);
    });

    // Auction Registration
    Route::post('/auctions/{auction}/register', function (Request $request, Auction $auction) {
        $registration = AuctionRegistration::firstOrCreate(
            ['auction_id' => $auction->id, 'user_id' => $request->user()->id],
            [
                'emd_amount' => $auction->starting_price * 0.1,
                'emd_status' => 'pending',
                'registration_number' => 'REG-' . strtoupper(uniqid()),
            ]
        );
        return $registration;
    });
});
