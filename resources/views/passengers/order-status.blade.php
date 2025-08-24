<!-- resources/views/passengers/order-status.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Статус заказа #{{ $order->id }}</h2>

            @if($order->status === 'pending')
                <div class="text-center py-8">
                    <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-icons text-yellow-600 text-4xl">schedule</span>
                    </div>
                    <p class="text-lg font-semibold text-gray-800 mb-2">⏳ Ожидаем подтверждения</p>
                    <p class="text-gray-600">Водитель {{ $order->driver->first_name }} уведомлен о вашем заказе</p>
                </div>
            @elseif($order->status === 'accepted')
                <div class="text-center py-8">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-icons text-green-600 text-4xl">check_circle</span>
                    </div>
                    <p class="text-lg font-semibold text-gray-800 mb-2">✅ Заказ принят!</p>
                    <p class="text-gray-600">Водитель уже едет к вам</p>
                </div>
            @elseif($order->status === 'completed')
                <div class="text-center py-8">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-icons text-blue-600 text-4xl">directions_car</span>
                    </div>
                    <p class="text-lg font-semibold text-gray-800 mb-2">🚗 Поездка завершена</p>
                    <p class="text-gray-600">Спасибо за поездку!</p>
                </div>
            @endif

            <div class="border-t pt-6">
                <h3 class="font-semibold text-gray-800 mb-4">Детали заказа:</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Откуда:</span>
                        <span class="font-semibold">{{ $order->pickup_address }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Куда:</span>
                        <span class="font-semibold">{{ $order->destination_address }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Стоимость:</span>
                        <span class="font-semibold text-green-600">{{ number_format($order->price, 0, ',', ' ') }} ₸</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Статус:</span>
                        <span class="font-semibold
                        @if($order->status === 'pending') text-yellow-600
                        @elseif($order->status === 'accepted') text-green-600
                        @elseif($order->status === 'completed') text-blue-600
                        @endif">
                        {{ $order->status }}
                    </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
