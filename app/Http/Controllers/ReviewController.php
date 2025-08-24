<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ReviewController extends Controller
{
    public function store(Request $request, $driverId, $tripId)
    {

        $trip = Trip::findOrFail($tripId);

        if ($trip->status !== 'completed' || $trip->passenger_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Нельзя оставить отзыв для этой поездки');
        }

        if ($trip->review) {
            return redirect()->back()->with('error', 'Отзыв уже оставлен');
        }


        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500',
            'is_anonymous' => 'boolean',
            'passenger_name' => 'nullable|required_if:is_anonymous,false|string|max:255'
        ]);

        Review::create([
            'driver_id' => $driverId,
            'passenger_id' => $request->is_anonymous ? null : Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_anonymous' => $request->is_anonymous ?? false,
            'passenger_name' => $request->is_anonymous ? null : $request->passenger_name
        ]);

        // Обновляем рейтинг водителя
        $driver = User::findOrFail($driverId);
        $totalRatings = $driver->total_ratings + 1;
        $newRating = (($driver->rating * $driver->total_ratings) + $request->rating) / $totalRatings;

        $driver->update([
            'rating' => $newRating,
            'total_ratings' => $totalRatings
        ]);

        return redirect()->back()->with('success', 'Отзыв добавлен');
    }
}
