<?php

namespace App\Http\Controllers;

use App\Models\Property;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProperties = Property::query()
            ->where('is_featured', true)
            ->latest()
            ->limit(3)
            ->get();

        $mapProperties = Property::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', 0)
            ->where('longitude', '!=', 0)
            ->select(['id', 'title', 'price', 'latitude', 'longitude'])
            ->latest()
            ->limit(100)
            ->get()
            ->map(function ($property) {
                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'price' => $property->price,
                    'latitude' => $property->latitude,
                    'longitude' => $property->longitude,
                ];
            });

        return view('home', [
            'featuredProperties' => $featuredProperties,
            'mapProperties' => $mapProperties,
        ]);
    }
}
