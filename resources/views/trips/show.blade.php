<!-- resources/views/trips/show.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Поездка #{{ $trip->id }}</h2>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-600">Статус</p>
                    <p class="font-semibold">
                        @switch($trip->status)
                            @case('pending') ⏳ Ожидание @break
                            @case('accepted') ✅ Принята @break
                            @case('in_progress') 🚗 В пути @break
                            @case('completed') ✅ Завершена @break
                            @case('cancelled') ❌ Отменена @break
                        @endswitch
                    </p>
                </div>

                @if($trip->price)
                    <div>
                        <p class="text-sm text-gray-600">Стоимость</p>
                        <p class="font-semibold">{{ number_format($trip->price, 0) }} ₸</p>
                    </div>
                @endif
            </div>

            <div class="space-y-3">
                <p><span class="text-gray-600">От:</span> {{ $trip->from_address }}</p>
                <p><span class="text-gray-600">До:</span> {{ $trip->to_address }}</p>

                @if($trip->started_at)
                    <p><span class="text-gray-600">Начало:</span> {{ $trip->started_at->format('d.m.Y H:i') }}</p>
                @endif

                @if($trip->completed_at)
                    <p><span class="text-gray-600">Завершение:</span> {{ $trip->completed_at->format('d.m.Y H:i') }}</p>
                @endif

                @if($trip->duration_minutes)
                    <p><span class="text-gray-600">Длительность:</span> {{ $trip->duration_minutes }} мин.</p>
                @endif
            </div>

            <!-- Кнопки действий -->
            @if($trip->status === 'pending' && $trip->driver_id === Auth::id())
                <form action="{{ route('trips.accept', $trip->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full bg-green-500 text-white py-3 rounded-xl font-semibold">
                        Принять поездку
                    </button>
                </form>
            @endif

            @if($trip->status === 'accepted' && $trip->driver_id === Auth::id())
                <form action="{{ route('trips.start', $trip->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-xl font-semibold">
                        Начать поездку
                    </button>
                </form>
            @endif

            @if($trip->status === 'in_progress' && $trip->driver_id === Auth::id())
                <form action="{{ route('trips.complete', $trip->id) }}" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Стоимость поездки (₸)</label>
                        <input type="number" name="price" required
                               class="w-full p-3 border rounded-xl"
                               placeholder="Введите сумму">
                    </div>
                    <button type="submit" class="w-full bg-green-500 text-white py-3 rounded-xl font-semibold">
                        Завершить поездку
                    </button>
                </form>
            @endif

            @if(in_array($trip->status, ['pending', 'accepted']))
                <form action="{{ route('trips.cancel', $trip->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 text-white py-3 rounded-xl font-semibold">
                        Отменить поездку
                    </button>
                </form>
            @endif

            <!-- Отзыв -->
            @if($trip->status === 'completed' && $trip->passenger_id === Auth::id() && !$trip->review)
                <div class="mt-6 pt-4 border-t">
                    <h3 class="text-lg font-semibold mb-3">Оставить отзыв</h3>
                    <form action="{{ route('review.store', $trip->id) }}" method="POST">
                        @csrf
                        <!-- Форма отзыва -->
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
