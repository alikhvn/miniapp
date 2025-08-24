<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function create(Request $request, $driverId)
    {
        $driver = User::findOrFail($driverId);
        $city = City::find(session('selected_city'));

        return view('trips.create', compact('driver', 'city'));
    }

    public function store(Request $request, $driverId)
    {
        $request->validate([
            'from_address' => 'required|string|max:255',
            'to_address' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $trip = Trip::create([
            'driver_id' => $driverId,
            'passenger_id' => Auth::id(),
            'city_id' => session('selected_city'),
            'from_address' => $request->from_address,
            'to_address' => $request->to_address,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('trips.show', $trip->id)
            ->with('success', 'Запрос на поездку отправлен водителю');
    }

    public function show($tripId)
    {
        $trip = Trip::with(['driver', 'passenger', 'city'])
            ->findOrFail($tripId);

        // Проверяем что пользователь имеет отношение к поездке
        if ($trip->driver_id !== Auth::id() && $trip->passenger_id !== Auth::id()) {
            abort(403);
        }

        return view('trips.show', compact('trip'));
    }

    public function accept($tripId)
    {
        $trip = Trip::where('driver_id', Auth::id())->findOrFail($tripId);

        $trip->update([
            'status' => 'accepted',
            'started_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Поезка принята');
    }

    public function start($tripId)
    {
        $trip = Trip::where('driver_id', Auth::id())->findOrFail($tripId);

        $trip->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Поезка началась');
    }

    public function complete(Request $request, $tripId)
    {
        $trip = Trip::where('driver_id', Auth::id())->findOrFail($tripId);

        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $trip->update([
            'status' => 'completed',
            'completed_at' => now(),
            'price' => $request->price,
            'duration_minutes' => $trip->started_at ? now()->diffInMinutes($trip->started_at) : null,
        ]);

        return redirect()->back()->with('success', 'Поезка завершена');
    }

    public function cancel($tripId)
    {
        $trip = Trip::findOrFail($tripId);

        // Проверяем что пользователь имеет отношение к поездке
        if ($trip->driver_id !== Auth::id() && $trip->passenger_id !== Auth::id()) {
            abort(403);
        }

        $trip->update([
            'status' => 'cancelled',
        ]);

        return redirect()->back()->with('success', 'Поезка отменена');
    }

    public function index()
    {
        $trips = Trip::with(['driver', 'passenger', 'city', 'review'])
            ->where('driver_id', Auth::id())
            ->orWhere('passenger_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('trips.index', compact('trips'));
    }
}
