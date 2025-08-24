<!-- resources/views/trips/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Мои поездки</h1>
            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
            {{ $trips->count() }} поездок
        </span>
        </div>

        @if($trips->count() > 0)
            <div class="space-y-4">
                @foreach($trips as $trip)
                    <a href="{{ route('trips.show', $trip->id) }}" class="block">
                        <div class="bg-white rounded-2xl shadow-lg p-5 card-hover">
                            <!-- Trip Header -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    @if($trip->driver_id === Auth::id())
                                        <span class="material-icons text-green-500 mr-2">directions_car</span>
                                        <span class="text-sm text-gray-600">Вы водитель</span>
                                    @else
                                        <span class="material-icons text-blue-500 mr-2">person</span>
                                        <span class="text-sm text-gray-600">Вы пассажир</span>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500">{{ $trip->created_at->format('d.m.Y H:i') }}</span>
                            </div>

                            <!-- Status Badge -->
                            <div class="mb-3">
                                @switch($trip->status)
                                    @case('pending')
                                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm flex items-center w-fit">
                                    <span class="material-icons text-sm mr-1">schedule</span>
                                    Ожидание
                                </span>
                                        @break
                                    @case('accepted')
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm flex items-center w-fit">
                                    <span class="material-icons text-sm mr-1">check_circle</span>
                                    Принята
                                </span>
                                        @break
                                    @case('in_progress')
                                        <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm flex items-center w-fit">
                                    <span class="material-icons text-sm mr-1">directions_car</span>
                                    В пути
                                </span>
                                        @break
                                    @case('completed')
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm flex items-center w-fit">
                                    <span class="material-icons text-sm mr-1">check_circle</span>
                                    Завершена
                                </span>
                                        @break
                                    @case('cancelled')
                                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm flex items-center w-fit">
                                    <span class="material-icons text-sm mr-1">cancel</span>
                                    Отменена
                                </span>
                                        @break
                                @endswitch
                            </div>

                            <!-- Route Info -->
                            <div class="space-y-2">
                                <div class="flex items-center text-gray-700">
                                    <span class="material-icons text-green-500 text-sm mr-2">location_on</span>
                                    <span class="text-sm truncate">{{ $trip->from_address }}</span>
                                </div>
                                <div class="flex items-center text-gray-700">
                                    <span class="material-icons text-red-500 text-sm mr-2">flag</span>
                                    <span class="text-sm truncate">{{ $trip->to_address }}</span>
                                </div>
                            </div>

                            <!-- Price and Duration -->
                            @if($trip->price)
                                <div class="flex items-center justify-between mt-3 pt-3 border-t">
                                    <div class="text-sm text-gray-600">
                                        @if($trip->duration_minutes)
                                            {{ $trip->duration_minutes }} мин
                                        @endif
                                    </div>
                                    <div class="font-semibold text-lg text-gray-800">
                                        {{ number_format($trip->price, 0) }} ₸
                                    </div>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="material-icons text-gray-400">receipt</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Пока нет поездок</h3>
                <p class="text-gray-600 mb-4">У вас еще не было заказанных или принятых поездок</p>

                @if(Auth::user()->role === 'passenger')
                    <a href="{{ route('passenger.search') }}" class="inline-block bg-blue-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-600 transition-colors">
                        Найти такси
                    </a>
                @else
                    <a href="{{ route('driver.dashboard') }}" class="inline-block bg-green-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-600 transition-colors">
                        Перейти в панель
                    </a>
                @endif
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="fixed bottom-20 right-4">
            @if(Auth::user()->role === 'passenger')
                <a href="{{ route('passenger.search') }}" class="w-14 h-14 bg-blue-500 rounded-full shadow-lg flex items-center justify-center text-white hover:bg-blue-600 transition-colors">
                    <span class="material-icons">search</span>
                </a>
            @else
                <a href="{{ route('driver.dashboard') }}" class="w-14 h-14 bg-green-500 rounded-full shadow-lg flex items-center justify-center text-white hover:bg-green-600 transition-colors">
                    <span class="material-icons">directions_car</span>
                </a>
            @endif
        </div>
    </div>
@endsection
