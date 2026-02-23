<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'address',
        'suburb',
        'city',
        'state',
        'latitude',
        'longitude',
        'capacity',
        'rating',
        'reviews_count',
        'price_level',
        'images',
        'amenities',
        'is_featured',
        'has_offer',
        'offer_text',
        'is_active',
    ];

    protected $casts = [
        'images' => 'json',
        'amenities' => 'json',
        'is_featured' => 'boolean',
        'has_offer' => 'boolean',
        'is_active' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'rating' => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function spaces()
    {
        return $this->hasMany(Space::class);
    }
}
