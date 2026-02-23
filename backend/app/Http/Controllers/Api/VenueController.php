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
        $query = Venue::with('category')->withCount('spaces')->where('is_active', true);

        // Search
        // ... (existing search logic remains)
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

        // Map Bounds Filter
        if ($request->filled(['minLat', 'maxLat', 'minLng', 'maxLng'])) {
            $query->whereBetween('latitude', [$request->get('minLat'), $request->get('maxLat')])
                ->whereBetween('longitude', [$request->get('minLng'), $request->get('maxLng')]);
        }

        // Calculate total spaces count for the filtered results
        $totalSpacesCount = \App\Models\Space::whereHas('venue', function ($q) use ($query) {
            // Replicate the same constraints as $query but for the Space model
            // This is slightly complex, alternatively we can just sum the spaces_count from the results
        })->count();

        // Simpler way: get the IDs of venues from the current query and sum spaces
        $venueIds = (clone $query)->pluck('id');
        $totalSpacesCount = \App\Models\Space::whereIn('venue_id', $venueIds)->count();

        $venues = $query->latest()->paginate($request->get('per_page', 9));

        // Add total_spaces to the pagination response
        $responseData = $venues->toArray();
        $responseData['total_spaces'] = $totalSpacesCount;

        return response()->json($responseData);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $venue = Venue::with(['category', 'spaces'])->findOrFail($id);
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'spaces_data' => 'nullable|string', // JSON string of spaces
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->except(['images', 'spaces_data']);

        // Handle Image Uploads
        $imageUrls = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('venues', 'public');
                $imageUrls[] = url('storage/' . $path);
            }
        }
        $data['images'] = empty($imageUrls) ? [] : $imageUrls;

        $venue = Venue::create($data);

        // Handle Spaces
        if ($request->filled('spaces_data')) {
            $spaces = json_decode($request->get('spaces_data'), true);
            if (is_array($spaces)) {
                foreach ($spaces as $spaceData) {
                    $venue->spaces()->create([
                        'name' => $spaceData['name'],
                        'capacity' => $spaceData['capacity'],
                        'description' => $spaceData['description'] ?? null,
                    ]);
                }
            }
        }

        return response()->json($venue->load('spaces'), 201);
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'spaces_data' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->except(['images', 'spaces_data']);

        // Handle Image Uploads
        if ($request->hasFile('images')) {
            $imageUrls = [];
            foreach ($request->file('images') as $file) {
                $path = $file->store('venues', 'public');
                $imageUrls[] = url('storage/' . $path);
            }
            $existingImages = is_array($venue->images) ? $venue->images : [];
            $data['images'] = array_merge($existingImages, $imageUrls);
        }

        $venue->update($data);

        // Handle Spaces (Sync approach for simplicity: delete old, add new)
        if ($request->filled('spaces_data')) {
            $spaces = json_decode($request->get('spaces_data'), true);
            if (is_array($spaces)) {
                $venue->spaces()->delete();
                foreach ($spaces as $spaceData) {
                    $venue->spaces()->create([
                        'name' => $spaceData['name'],
                        'capacity' => $spaceData['capacity'],
                        'description' => $spaceData['description'] ?? null,
                    ]);
                }
            }
        }

        return response()->json($venue->load('spaces'));
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
