<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Venue;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = auth()->guard('api')->user();
        $favorites = Venue::whereHas('favoritedBy', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('category')->get();

        return response()->json($favorites);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
        ]);

        $user_id = auth()->guard('api')->id();
        $venue_id = $request->venue_id;

        $favorite = Favorite::where('user_id', $user_id)
            ->where('venue_id', $venue_id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => 'Removed from favorites', 'favorited' => false]);
        }

        Favorite::create([
            'user_id' => $user_id,
            'venue_id' => $venue_id,
        ]);

        return response()->json(['message' => 'Added to favorites', 'favorited' => true], 201);
    }
}
