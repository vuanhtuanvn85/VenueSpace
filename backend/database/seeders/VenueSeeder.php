<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->pluck('id', 'name')->toArray();

        $venues = [
            [
                'category_id' => $categories['Function Venue'],
                'name' => 'Comfortably Large-scale Venue in Sydney',
                'description' => 'A large scale venue perfect for big corporate events and parties.',
                'address' => 'Sydney CBD',
                'suburb' => 'Sydney CBD',
                'city' => 'Sydney',
                'state' => 'NSW',
                'latitude' => -33.8660,
                'longitude' => 151.2080,
                'capacity' => 100,
                'rating' => 5.0,
                'reviews_count' => 121,
                'price_level' => 2,
                'images' => ['https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=800&q=80'],
                'is_featured' => true,
                'has_offer' => false,
            ],
            [
                'category_id' => $categories['Function Venue'],
                'name' => 'Historic Jubilee Room NSW Parliament',
                'description' => 'Experience history in this beautiful room located in the NSW Parliament.',
                'address' => 'Sydney CBD',
                'suburb' => 'Sydney CBD',
                'city' => 'Sydney',
                'state' => 'NSW',
                'latitude' => -33.8680,
                'longitude' => 151.2120,
                'capacity' => 90,
                'rating' => 4.8,
                'reviews_count' => 204,
                'price_level' => 3,
                'images' => ['https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80'],
                'is_featured' => true,
                'has_offer' => false,
            ],
            [
                'category_id' => $categories['Ballroom'],
                'name' => 'Lumiere on Thirty Five at Sydney',
                'description' => 'Stunning views and elegant ballroom for your special night.',
                'address' => 'Sydney CBD',
                'suburb' => 'Sydney CBD',
                'city' => 'Sydney',
                'state' => 'NSW',
                'latitude' => -33.8710,
                'longitude' => 151.2050,
                'capacity' => 120,
                'rating' => 4.4,
                'reviews_count' => 5146,
                'price_level' => 3,
                'images' => ['https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80'],
                'is_featured' => true,
                'has_offer' => true,
                'offer_text' => 'OFFER',
            ],
            [
                'category_id' => $categories['Hotel'],
                'name' => 'Ten Stories Restaurant at Swissôtel',
                'description' => 'Exquisite dining experience in the heart of the city.',
                'address' => 'Sydney CBD',
                'suburb' => 'Sydney CBD',
                'city' => 'Sydney',
                'state' => 'NSW',
                'latitude' => -33.8690,
                'longitude' => 151.2085,
                'capacity' => 100,
                'rating' => 4.2,
                'reviews_count' => 3155,
                'price_level' => 2,
                'images' => ['https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80'],
                'is_featured' => false,
                'has_offer' => false,
            ],
            [
                'category_id' => $categories['Function Venue'],
                'name' => 'Pre Function Area - Ground Floor',
                'description' => 'A versatile space for pre-event gatherings and cocktails.',
                'address' => 'Sydney CBD',
                'suburb' => 'Sydney CBD',
                'city' => 'Sydney',
                'state' => 'NSW',
                'latitude' => -33.8720,
                'longitude' => 151.2100,
                'capacity' => 40,
                'rating' => 4.2,
                'reviews_count' => 1583,
                'price_level' => 3,
                'images' => ['https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?auto=format&fit=crop&w=800&q=80'],
                'is_featured' => false,
                'has_offer' => false,
            ],
            [
                'category_id' => $categories['Hotel'],
                'name' => 'Smith Rooms 1 & 2 at Capella',
                'description' => 'Modern meeting rooms with state-of-the-art facilities.',
                'address' => 'Sydney CBD',
                'suburb' => 'Sydney CBD',
                'city' => 'Sydney',
                'state' => 'NSW',
                'latitude' => -33.8650,
                'longitude' => 151.2110,
                'capacity' => 64,
                'rating' => 4.7,
                'reviews_count' => 482,
                'price_level' => 3,
                'images' => ['https://images.unsplash.com/photo-1431540015161-0bf868a2d407?auto=format&fit=crop&w=800&q=80'],
                'is_featured' => false,
                'has_offer' => false,
            ],
        ];

        foreach ($venues as $venueData) {
            $venueData['amenities'] = ['Wifi', 'Audio Visual', 'Catering', 'Parking'];
            $venue = Venue::create($venueData);

            // Create random spaces for each venue
            $numSpaces = rand(1, 5);
            for ($j = 1; $j <= $numSpaces; $j++) {
                \App\Models\Space::create([
                    'venue_id' => $venue->id,
                    'name' => 'Space ' . $j . ' at ' . $venue->name,
                    'capacity' => rand(10, $venue->capacity),
                    'description' => 'A beautiful space within ' . $venue->name,
                ]);
            }
        }

        // Add more random venues for map clustering demo
        for ($i = 0; $i < 20; $i++) {
            $venue = Venue::create([
                'category_id' => array_values($categories)[rand(0, count($categories) - 1)],
                'name' => 'Venue ' . ($i + 7),
                'description' => 'Random venue description.',
                'address' => 'Sydney CBD',
                'suburb' => 'Sydney CBD',
                'city' => 'Sydney',
                'state' => 'NSW',
                'latitude' => -33.8688 + (rand(-100, 100) / 10000),
                'longitude' => 151.2093 + (rand(-100, 100) / 10000),
                'capacity' => rand(20, 500),
                'rating' => rand(30, 50) / 10,
                'reviews_count' => rand(10, 1000),
                'price_level' => rand(1, 4),
                'images' => ['https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80'],
                'amenities' => ['Wifi'],
                'is_featured' => false,
                'has_offer' => rand(0, 1) == 1,
            ]);

            // Create random spaces
            $numSpaces = rand(1, 5);
            for ($j = 1; $j <= $numSpaces; $j++) {
                \App\Models\Space::create([
                    'venue_id' => $venue->id,
                    'name' => 'Space ' . $j . ' at ' . $venue->name,
                    'capacity' => rand(10, $venue->capacity),
                ]);
            }
        }
    }
}
