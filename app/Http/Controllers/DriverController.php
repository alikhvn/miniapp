<?php

namespace App\Http\Controllers;

use App\Models\DriverSlot;
use App\Models\City;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role !== 'driver') {
            return redirect()->route('role.selection')->with('error', 'Сначала станьте водителем');
        }

        $driver = Auth::user();

        $driverSlots = DriverSlot::where('driver_id', $driver->id)
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get();



        $cities = City::orderBy('name_en')->get();

        $pendingOrders = Order::with(['passenger', 'city'])
            ->where('driver_id', $driver->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $activeOrders = Order::with(['passenger', 'city'])
            ->where('driver_id', $driver->id)
            ->whereIn('status', ['accepted', 'in_progress'])
            ->orderBy('created_at', 'desc')
            ->get();

        $completedOrders = Order::with(['passenger', 'city'])
            ->where('driver_id', $driver->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('drivers.dashboard', compact(
            'pendingOrders',
            'activeOrders',
            'completedOrders',
            'driverSlots', // Исправлено: было $driverSlots, стало $driverSlots
            'cities',
            'driver'
        ));
    }

    public function createSlot(Request $request)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        DriverSlot::create([
            'driver_id' => Auth::user()->telegram_id, // Используем telegram_id вместо id
            'city_id' => $request->city_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_available' => true
        ]);

        return redirect()->back()->with('success', 'Слот успешно добавлен');
    }

    public function toggleSlot($id)
    {
        $slot = DriverSlot::where('driver_id', Auth::id())->findOrFail($id);
        $slot->is_available = !$slot->is_available;
        $slot->save();

        return redirect()->back()->with('success', 'Статус обновлен');
    }

    // Новый метод для выбора города водителем
    public function selectCity(Request $request)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id'
        ]);

        session(['selected_city' => $request->city_id]);

        return redirect()->back()->with('success', 'Город выбран');
    }

    public function acceptOrder($orderId)
    {
        $order = Order::where('driver_id', Auth::id())
            ->findOrFail($orderId);

        $order->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);

        // Уведомление пассажиру
        // $this->sendPassengerNotification($order, 'Водитель принял заказ!');

        return redirect()->back()->with('success', 'Заказ принят!');
    }

    public function startOrder($orderId)
    {
        $order = Order::where('driver_id', Auth::id())
            ->findOrFail($orderId);

        $order->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);

        return redirect()->back()->with('success', 'Поездка начата!');
    }

    public function completeOrder($orderId)
    {
        $order = Order::where('driver_id', Auth::id())
            ->findOrFail($orderId);

        $order->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return redirect()->back()->with('success', 'Поездка завершена!');
    }

    public function editProfile()
    {
        return view('drivers.profile-edit');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'fixed_price' => 'required|numeric|min:1',
            'car_info' => 'required|string',
            'license_plate' => 'required|string',
        ]);

        Auth::user()->update($request->only('fixed_price', 'car_info', 'license_plate'));

        return redirect()->route('driver.dashboard')->with('success', 'Профиль обновлен!');
    }


}
