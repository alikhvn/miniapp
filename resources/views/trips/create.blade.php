<!-- resources/views/trips/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto">
        <!-- Header -->
        <div class="flex items-center mb-6">
            <a href="{{ route('passenger.search') }}" class="mr-3">
                <span class="material-icons text-gray-600">arrow_back</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Заказ поездки</h1>
        </div>

        <!-- Driver Info -->
        <div class="bg-white rounded-2xl shadow-lg p-5 mb-6">
            <div class="flex items-center">
                @if($driver->photo_url)
                    <img src="{{ $driver->photo_url }}" class="w-12 h-12 rounded-full">
                @else
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr($driver->first_name, 0, 1) }}{{ substr($driver->last_name, 0, 1) }}
                    </div>
                @endif
                <div class="ml-4">
                    <h3 class="font-semibold text-gray-800">{{ $driver->first_name }} {{ $driver->last_name }}</h3>
                    <div class="flex items-center">
                        <span class="text-yellow-400">⭐</span>
                        <span class="text-sm text-gray-600 ml-1">{{ number_format($driver->rating, 1) }} ({{ $driver->total_ratings }})</span>
                    </div>
                </div>
            </div>

            @if($driver->car_info)
                <div class="mt-4 pt-4 border-t">
                    <p class="text-sm text-gray-600">🚗 {{ $driver->car_info }}</p>
                    <p class="text-sm text-gray-600">🔢 {{ $driver->license_plate }}</p>
                </div>
            @endif
        </div>

        <!-- Trip Form -->
        <div class="bg-white rounded-2xl shadow-lg p-5">
            <form action="{{ route('trips.store', $driver->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">📍 Откуда</label>
                    <input type="text" name="from_address" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent"
                           placeholder="Ваш текущий адрес">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">🏁 Куда</label>
                    <input type="text" name="to_address" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent"
                           placeholder="Адрес назначения">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">📝 Примечания (необязательно)</label>
                    <textarea name="notes" rows="3"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent"
                              placeholder="Особые указания для водителя..."></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white py-4 rounded-xl font-semibold text-lg shadow-lg hover:bg-blue-600 transition-colors">
                    📞 Заказать поездку
                </button>
            </form>
        </div>
    </div>
@endsection
