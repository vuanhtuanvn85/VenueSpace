<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Venue::with('category')->where('is_active', true);

        // Search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        if ($request->filled('city')) {
            $query->where('city', $request->get('city'));
        }

        if ($request->filled('min_capacity')) {
            $query->where('capacity', '>=', $request->get('min_capacity'));
        }

        if ($request->filled('price_level')) {
            $query->where('price_level', $request->get('price_level'));
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', true);
        }

        $venues = $query->latest()->paginate($request->get('per_page', 9));

        return response()->json($venues);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $venue = Venue::with('category')->findOrFail($id);
        return response()->json($venue);
    }

    /**
     * Store a newly created resource in storage (Admin only).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'capacity' => 'required|integer',
            'price_level' => 'required|integer|between:1,5',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $venue = Venue::create($request->all());

        return response()->json($venue, 201);
    }

    /**
     * Update the specified resource (Admin only).
     */
    public function update(Request $request, $id)
    {
        $venue = Venue::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'category_id' => 'exists:categories,id',
            'name' => 'string|max:255',
            'price_level' => 'integer|between:1,5',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $venue->update($request->all());

        return response()->json($venue);
    }

    /**
     * Remove the specified resource (Admin only).
     */
    public function destroy($id)
    {
        $venue = Venue::findOrFail($id);
        $venue->delete();

        return response()->json(null, 204);
    }
}
