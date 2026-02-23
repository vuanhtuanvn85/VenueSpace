<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Quote::with('venue');

        if ($request->has('status') && $request->get('status') !== '') {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('venue_name') && $request->get('venue_name') !== '') {
            $query->whereHas('venue', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->get('venue_name') . '%');
            });
        }

        return response()->json($query->latest()->paginate(20));
    }

    public function myQuotes(Request $request)
    {
        $userId = auth()->guard('api')->id();
        $query = Quote::with('venue')->where('user_id', $userId);

        if ($request->has('venue_name') && $request->get('venue_name') !== '') {
            $query->whereHas('venue', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->get('venue_name') . '%');
            });
        }

        $quotes = $query->latest()->get();
        return response()->json($quotes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'event_date' => 'nullable|date',
            'guest_count' => 'nullable|integer|min:1',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();
        // If user is logged in, attach user_id
        if (auth()->guard('api')->check()) {
            $data['user_id'] = auth()->guard('api')->id();
        }

        $quote = Quote::create($data);

        return response()->json($quote, 201);
    }

    public function update(Request $request, $id)
    {
        $quote = Quote::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,contacted,completed,cancelled',
        ]);

        $quote->update(['status' => $request->status]);

        return response()->json($quote);
    }

    public function destroy($id)
    {
        $quote = Quote::findOrFail($id);
        $quote->delete();

        return response()->json(null, 204);
    }
}
