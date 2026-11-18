<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\BuilderProject;
use App\Models\BrokerProfile;
use App\Models\MetroStation;
use App\Models\PropertyMedia;
use App\Models\OwnerListing;
use App\Models\Auction;
use App\Models\AuctionRegistration;
use Illuminate\Support\Facades\Hash;

class ReelEstateSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create or get test users
        $admin = User::firstOrCreate(
            ['email' => 'admin@reelestate.ai'],
            [
                'name' => 'ReelEstate Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $brokerUser = User::firstOrCreate(
            ['email' => 'rajesh.patel@brokerhub.in'],
            [
                'name' => 'Rajesh Patel',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $ownerUser = User::firstOrCreate(
            ['email' => 'amit.shah@gmail.com'],
            [
                'name' => 'Amit Shah',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 1b. Default Team
        \App\Models\Team::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Default Team',
                'personal_team' => false,
                'user_id' => $admin->id,
            ]
        );

        // 2. Categories
        $saleCategory = PropertyCategory::firstOrCreate(
            ['slug' => 'sales'],
            ['name' => 'Sales']
        );
        $rentCategory = PropertyCategory::firstOrCreate(
            ['slug' => 'lettings'],
            ['name' => 'Lettings']
        );

        // 3. Builder Projects
        $godrejProject = BuilderProject::firstOrCreate(
            ['name' => 'Godrej Garden City'],
            [
                'builder_name' => 'Godrej Properties',
                'rera_number' => 'PR/GJ/AHMEDABAD/AHMEDABAD CITY/AUDA/RAA00012/010123',
                'location' => 'SG Highway, Gota',
                'city' => 'Ahmedabad',
                'total_units' => 850,
                'available_units' => 42,
                'price_range_min' => 6500000,
                'price_range_max' => 18500000,
                'possession_date' => '2025-12-31',
                'status' => 'Under Construction',
                'amenities' => ['Clubhouse', 'Olympic Pool', 'Tennis Court', 'EV Charging', 'Jogging Track', 'Smart Security'],
                'floor_plans' => [
                    ['name' => '2 BHK Luxury', 'carpet_area' => 850, 'price' => 6500000],
                    ['name' => '3 BHK Sky Residence', 'carpet_area' => 1450, 'price' => 11500000],
                    ['name' => '4 BHK Penthouse', 'carpet_area' => 2200, 'price' => 18500000],
                ],
                'latitude' => 23.1147,
                'longitude' => 72.5413,
                'description' => 'A master-planned sustainable township with 80% open greenery, international school, and hospital along SG Highway, Ahmedabad.',
                'logo_url' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=200&h=200&fit=crop',
                'banner_url' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&h=500&fit=crop',
            ]
        );

        $adaniProject = BuilderProject::firstOrCreate(
            ['name' => 'Adani Shantigram'],
            [
                'builder_name' => 'Adani Realty',
                'rera_number' => 'PR/GJ/GANDHINAGAR/GANDHINAGAR/AUDA/RAA00045/020223',
                'location' => 'Near Vaishnodevi Circle, SG Highway',
                'city' => 'Ahmedabad',
                'total_units' => 1200,
                'available_units' => 88,
                'price_range_min' => 7500000,
                'price_range_max' => 32000000,
                'possession_date' => '2026-06-30',
                'status' => 'Under Construction',
                'amenities' => ['9-Hole Golf Course', 'Helipad', 'Private Lake', 'Spa & Wellness Club', 'Cricket Stadium'],
                'floor_plans' => [
                    ['name' => '3 BHK Premium', 'carpet_area' => 1650, 'price' => 9500000],
                    ['name' => '4 BHK Golf Villa', 'carpet_area' => 3200, 'price' => 32000000],
                ],
                'latitude' => 23.1480,
                'longitude' => 72.5590,
                'description' => 'Gujarat largest integrated township with golf course living, pristine lakes and luxury lifestyle.',
                'logo_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=200&h=200&fit=crop',
                'banner_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&h=500&fit=crop',
            ]
        );

        // 4. Metro Stations
        $metroStations = [
            [
                'name' => 'Thaltej Gam Metro Station',
                'line_name' => 'Ahmedabad Metro Line 1 (East-West)',
                'line_color' => '#10B981',
                'latitude' => 23.0510,
                'longitude' => 72.5020,
                'city' => 'Ahmedabad',
                'is_operational' => true,
            ],
            [
                'name' => 'Doordarshan Kendra Metro',
                'line_name' => 'Ahmedabad Metro Line 1',
                'line_color' => '#10B981',
                'latitude' => 23.0534,
                'longitude' => 72.5204,
                'city' => 'Ahmedabad',
                'is_operational' => true,
            ],
            [
                'name' => 'Commerce Six Road',
                'line_name' => 'Ahmedabad Metro Line 1',
                'line_color' => '#10B981',
                'latitude' => 23.0380,
                'longitude' => 72.5510,
                'city' => 'Ahmedabad',
                'is_operational' => true,
            ],
            [
                'name' => 'BKC Metro Station',
                'line_name' => 'Aqua Line 3 (Underground)',
                'line_color' => '#06B6D4',
                'latitude' => 19.0657,
                'longitude' => 72.8687,
                'city' => 'Mumbai',
                'is_operational' => true,
            ],
            [
                'name' => 'Indiranagar Metro Station',
                'line_name' => 'Namma Metro Purple Line',
                'line_color' => '#8B5CF6',
                'latitude' => 12.9783,
                'longitude' => 77.6408,
                'city' => 'Bangalore',
                'is_operational' => true,
            ],
        ];

        foreach ($metroStations as $ms) {
            MetroStation::firstOrCreate(['name' => $ms['name']], $ms);
        }

        // 5. Broker Profiles
        BrokerProfile::updateOrCreate(
            ['user_id' => $brokerUser->id],
            [
                'rera_id' => 'AG/GJ/AHMEDABAD/AHMEDABAD/AA00128/2022',
                'agency_name' => 'Patel Premier Realty Group',
                'specialization' => 'Luxury High-Rise & SG Highway Developments',
                'areas_served' => ['SG Highway', 'Bodakdev', 'Thaltej', 'Gota', 'Sindhu Bhavan'],
                'experience_years' => 14,
                'total_deals' => 184,
                'rating' => 4.92,
                'verified' => true,
                'bio' => 'Top 1% RERA-certified luxury property advisor in Ahmedabad. Specialized in high-ticket residential developments and NRI investments along SG Highway.',
                'phone' => '+91 98250 12345',
                'whatsapp' => '+919825012345',
                'social_links' => [
                    'instagram' => 'https://instagram.com/patelrealty_ahmedabad',
                    'youtube' => 'https://youtube.com/@patelrealty',
                    'linkedin' => 'https://linkedin.com/in/rajeshpatel-realty',
                ],
            ]
        );

        // 6. Properties
        $p1 = Property::create([
            'title' => 'Godrej Garden City - Celona 3 BHK Sky Residence',
            'description' => 'East-facing Vastu compliant 3 BHK apartment on the 18th floor with unobstructed views of Central Greens. Features Italian marble flooring, smart automation, modular kitchen with Bosch appliances, and two dedicated covered car parkings.',
            'location' => 'SG Highway, Gota',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'pincode' => '382481',
            'price' => 11500000,
            'bedrooms' => 3,
            'bathrooms' => 3,
            'area_sqft' => 1850,
            'carpet_area_sqft' => 1320,
            'super_buildup_area_sqft' => 1850,
            'floor_number' => 18,
            'total_floors' => 22,
            'facing' => 'East',
            'furnishing' => 'semi',
            'parking_count' => 2,
            'property_age' => 1,
            'maintenance_charge' => 4500,
            'year_built' => 2024,
            'property_type' => 'Apartment',
            'status' => 'For Sale',
            'is_featured' => true,
            'list_date' => now(),
            'user_id' => $admin->id,
            'property_category_id' => $saleCategory->id,
            'builder_project_id' => $godrejProject->id,
            'rera_number' => 'PR/GJ/AHMEDABAD/AHMEDABAD CITY/AUDA/RAA00012/010123',
            'latitude' => 23.1147,
            'longitude' => 72.5413,
        ]);

        $p2 = Property::create([
            'title' => '4 BHK Ultra Luxury Villa - Sindhu Bhavan Road',
            'description' => 'Exclusive independent villa with private swimming pool, double-height ceiling living room, home theatre, and private terrace garden. High security gated community with 24x7 concierge.',
            'location' => 'Sindhu Bhavan Marg, Bodakdev',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'pincode' => '380054',
            'price' => 38500000,
            'bedrooms' => 4,
            'bathrooms' => 5,
            'area_sqft' => 4200,
            'carpet_area_sqft' => 3600,
            'super_buildup_area_sqft' => 4200,
            'floor_number' => 1,
            'total_floors' => 3,
            'facing' => 'North-East',
            'furnishing' => 'fully',
            'parking_count' => 3,
            'property_age' => 0,
            'maintenance_charge' => 12000,
            'year_built' => 2024,
            'property_type' => 'Villa',
            'status' => 'For Sale',
            'is_featured' => true,
            'list_date' => now(),
            'user_id' => $brokerUser->id,
            'property_category_id' => $saleCategory->id,
            'rera_number' => 'PR/GJ/AHMEDABAD/AHMEDABAD CITY/AUDA/RAA00088/050523',
            'latitude' => 23.0450,
            'longitude' => 72.5050,
        ]);

        $p3 = Property::create([
            'title' => 'SARFAESI Bank Auction: 3 BHK Duplex Penthouse',
            'description' => 'Foreclosed asset under SARFAESI Act by State Bank of India. 3 BHK Duplex Penthouse with private terrace. Reserve price is 28% below current market valuation. Clear title, physical possession assured.',
            'location' => 'Prahladnagar, Satellite',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'pincode' => '380015',
            'price' => 8800000,
            'bedrooms' => 3,
            'bathrooms' => 3,
            'area_sqft' => 2100,
            'carpet_area_sqft' => 1550,
            'super_buildup_area_sqft' => 2100,
            'floor_number' => 12,
            'total_floors' => 13,
            'facing' => 'North',
            'furnishing' => 'unfurnished',
            'parking_count' => 2,
            'property_age' => 4,
            'maintenance_charge' => 3800,
            'year_built' => 2020,
            'property_type' => 'Penthouse',
            'status' => 'For Sale',
            'is_featured' => true,
            'list_date' => now(),
            'user_id' => $admin->id,
            'property_category_id' => $saleCategory->id,
            'latitude' => 23.0120,
            'longitude' => 72.5120,
        ]);

        $p4 = Property::create([
            'title' => 'Zero Brokerage: 2 BHK High-Rise with Metro Connectivity',
            'description' => 'Direct from verified owner, zero brokerage. 2 BHK apartment just 350 meters from Thaltej Metro Station. Low maintenance society with clubhouse and landscaped garden.',
            'location' => 'Near Thaltej Gam Metro, Thaltej',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'pincode' => '380059',
            'price' => 5400000,
            'bedrooms' => 2,
            'bathrooms' => 2,
            'area_sqft' => 1120,
            'carpet_area_sqft' => 840,
            'super_buildup_area_sqft' => 1120,
            'floor_number' => 8,
            'total_floors' => 14,
            'facing' => 'East',
            'furnishing' => 'semi',
            'parking_count' => 1,
            'property_age' => 2,
            'maintenance_charge' => 2200,
            'year_built' => 2022,
            'property_type' => 'Apartment',
            'status' => 'For Sale',
            'is_featured' => false,
            'list_date' => now(),
            'user_id' => $ownerUser->id,
            'property_category_id' => $saleCategory->id,
            'latitude' => 23.0505,
            'longitude' => 72.5025,
        ]);

        $p5 = Property::create([
            'title' => 'Sea-Facing 3 BHK Luxury Suite - Worli Sea Face',
            'description' => 'Uninterrupted Arabian Sea panoramic views from every room. Ultra high-end interior, smart climate controls, infinity rooftop pool, and direct access to coastal promenade.',
            'location' => 'Worli Sea Face, Worli',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'pincode' => '400018',
            'price' => 72000000,
            'bedrooms' => 3,
            'bathrooms' => 4,
            'area_sqft' => 2400,
            'carpet_area_sqft' => 1950,
            'super_buildup_area_sqft' => 2400,
            'floor_number' => 28,
            'total_floors' => 45,
            'facing' => 'West',
            'furnishing' => 'fully',
            'parking_count' => 2,
            'property_age' => 1,
            'maintenance_charge' => 25000,
            'year_built' => 2023,
            'property_type' => 'Apartment',
            'status' => 'For Sale',
            'is_featured' => true,
            'list_date' => now(),
            'user_id' => $admin->id,
            'property_category_id' => $saleCategory->id,
            'latitude' => 19.0178,
            'longitude' => 72.8150,
        ]);

        // 7. Property Media (Reels & StreetView)
        $mediaData = [
            [
                'property_id' => $p1->id,
                'type' => 'reel',
                'url' => 'https://assets.mixkit.co/videos/preview/mixkit-modern-apartment-interior-design-39943-large.mp4',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&h=800&fit=crop',
                'duration' => '0:48',
                'views_count' => 24300,
                'likes_count' => 1840,
                'caption' => 'Experience the panoramic 18th floor living at Godrej Garden City, Ahmedabad! 🌇 RERA Approved.',
                'sort_order' => 1,
            ],
            [
                'property_id' => $p1->id,
                'type' => 'streetview',
                'url' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&h=600&fit=crop',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=400&h=300&fit=crop',
                'duration' => null,
                'views_count' => 8900,
                'likes_count' => 450,
                'caption' => '360° Boulevard Perspective - SG Highway',
                'sort_order' => 2,
            ],
            [
                'property_id' => $p2->id,
                'type' => 'reel',
                'url' => 'https://assets.mixkit.co/videos/preview/mixkit-living-room-of-a-luxurious-modern-house-43399-large.mp4',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&h=800&fit=crop',
                'duration' => '1:12',
                'views_count' => 41200,
                'likes_count' => 3280,
                'caption' => 'Inside a ₹3.85 Cr Bodakdev Villa with Private Pool & Home Cinema! ✨',
                'sort_order' => 1,
            ],
            [
                'property_id' => $p3->id,
                'type' => 'reel',
                'url' => 'https://assets.mixkit.co/videos/preview/mixkit-bright-kitchen-and-dining-room-41372-large.mp4',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=600&h=800&fit=crop',
                'duration' => '0:35',
                'views_count' => 15600,
                'likes_count' => 920,
                'caption' => 'Live Bank Auction Alert: SBI foreclosed duplex 28% below circle rate! 🔨',
                'sort_order' => 1,
            ],
            [
                'property_id' => $p4->id,
                'type' => 'reel',
                'url' => 'https://assets.mixkit.co/videos/preview/mixkit-sun-streaming-into-a-modern-living-room-40280-large.mp4',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=600&h=800&fit=crop',
                'duration' => '0:29',
                'views_count' => 9800,
                'likes_count' => 640,
                'caption' => 'Direct from Owner: 2 BHK near Thaltej Metro. Save ₹1.1 Lakh brokerage! 🏷️',
                'sort_order' => 1,
            ],
            [
                'property_id' => $p5->id,
                'type' => 'reel',
                'url' => 'https://assets.mixkit.co/videos/preview/mixkit-modern-apartment-with-a-view-of-the-city-at-night-42866-large.mp4',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=600&h=800&fit=crop',
                'duration' => '0:55',
                'views_count' => 68000,
                'likes_count' => 5400,
                'caption' => 'Worli Sea Face 28th floor sunset reel. Luxury Mumbai living at its finest 🌊',
                'sort_order' => 1,
            ],
        ];

        foreach ($mediaData as $m) {
            PropertyMedia::create($m);
        }

        // 8. Auctions
        $auction = Auction::create([
            'property_id' => $p3->id,
            'start_time' => now()->subHours(1),
            'end_time' => now()->addDays(2),
            'starting_price' => 8800000,
            'current_bid' => 9250000,
            'minimum_increment' => 50000,
            'status' => 'active',
        ]);

        AuctionRegistration::create([
            'auction_id' => $auction->id,
            'user_id' => $admin->id,
            'emd_amount' => 880000,
            'emd_status' => 'verified',
            'registration_number' => 'REG-SBI-2026-0891',
        ]);

        // 9. Owner Listing
        OwnerListing::create([
            'user_id' => $ownerUser->id,
            'property_id' => $p4->id,
            'listing_type' => 'sell',
            'asking_price' => 5400000,
            'is_zero_brokerage' => true,
            'contact_phone' => '+91 98980 54321',
            'contact_whatsapp' => '+919898054321',
            'verification_status' => 'verified',
            'documents' => ['electricity_bill' => 'verified', 'property_tax' => 'verified', 'index_ii' => 'verified'],
        ]);
    }
}
