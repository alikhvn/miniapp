<?php

namespace App\Http\Controllers;

use App\Models\DriverSlot;
use App\Models\City;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Telegram\Bot\Api;

class PassengerController extends Controller
{
    public function searchDrivers(Request $request)
    {
        $cityId = session('selected_city');

        if (!$cityId) {
            return redirect()->route('home')->with('error', 'Сначала выберите город');
        }

        $drivers = DriverSlot::with(['driver', 'city'])
            ->where('city_id', $cityId)
            ->where('is_available', true)
            ->where('date', '>=', now()->format('Y-m-d'))
            ->where('end_time', '>=', now()->format('H:i:s'))
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy('driver_id');

        return view('passengers.search', compact('drivers'));
    }

    public function showDriver($driverId)
    {
        $driver = User::with(['reviews' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($driverId);

        return view('passengers.driver-profile', compact('driver'));
    }

    public function createOrder(Request $request, $driverId)
    {
        $request->validate([
            'pickup_address' => 'required|string|max:255',
            'destination_address' => 'required|string|max:255',
        ]);

        $passenger = Auth::user();
        $driver = User::findOrFail($driverId);

        \Log::info('Creating order:', [
            'passenger_id' => $passenger->id,
            'driver_id' => $driver->id,
            'passenger_telegram_id' => $passenger->telegram_id
        ]);

        // Проверяем, что это действительно водитель
        if ($driver->role !== 'driver') {
            return redirect()->back()->with('error', 'Выбранный пользователь не является водителем');
        }

        $order = Order::create([
            'passenger_id' => $passenger->id, // ID из базы (2)
            'driver_id' => $driver->id,       // ID из базы (3)
            'city_id' => session('selected_city'),
            'status' => 'pending',
            'pickup_address' => $request->input('pickup_address'),
            'destination_address' => $request->input('destination_address'),
            'price' => $driver->fixed_price,
        ]);

        $this->sendOrderNotification($driver, $order);

        return redirect()->route('passenger.order.status', $order->id)
            ->with('success', 'Заказ создан! Ожидайте ответа водителя.');
    }

    /**
     * Отправка уведомления водителю о новом заказе
     */
    private function sendOrderNotification($driver, $order)
    {
        $message = "🚗 *Новый заказ!* \n";
        $message .= "📍 *От:* {$order->pickup_address}\n";
        $message .= "🎯 *Куда:* {$order->destination_address}\n";
        $message .= "💰 *Цена:* {$order->price} ₸\n";
        $message .= "📞 *Пассажир:* {$order->passenger->first_name} {$order->passenger->last_name}\n";
        $message .= "🔢 *Номер заказа:* #{$order->id}";

        if ($driver->telegram_chat_id) {
            $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
            $telegram->sendMessage([
                'chat_id' => $driver->telegram_chat_id,
                'text' => $message,
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [
                            [
                                'text' => '✅ Принять заказ',
                                'url' => url("/driver/order/accept/{$order->id}")
                            ]
                        ]
                    ]
                ])
            ]);
        }
    }

    public function orderStatus($orderId)
    {
        $order = Order::with(['driver', 'city'])->findOrFail($orderId);

        // Проверяем, что заказ принадлежит текущему пользователю
        if ($order->passenger_id !== Auth::id()) {
            abort(403, 'Доступ запрещен');
        }

        return view('passengers.order-status', compact('order'));
    }

}
