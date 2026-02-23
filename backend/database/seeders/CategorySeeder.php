<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Function Venue',
            'Ballroom',
            'Hotel',
            'Restaurant',
            'Bar',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "Description for {$name}",
                'icon' => null,
            ]);
        }
    }
}
