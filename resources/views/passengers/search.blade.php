<!-- resources/views/passengers/search.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Поиск водителей</h2>

        @if($drivers->count() > 0)
            @foreach($drivers as $driverSlots)
                @php $driver = $driverSlots->first()->driver; @endphp
                <div class="bg-white rounded-2xl shadow p-6 mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($driver->name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold">{{ $driver->name }}</h3>
                                <p class="text-sm text-gray-600">⭐ {{ number_format($driver->rating, 1) }} ({{ $driver->total_ratings }})</p>
                            </div>
                        </div>
                        <a href="{{ route('passenger.driver.show', $driver->id) }}"
                           class="bg-blue-500 text-white px-4 py-2 rounded-xl">
                            Выбрать
                        </a>
                    </div>

                    <div class="border-t pt-4">
                        <p class="font-semibold mb-2">Доступные слоты:</p>
                        @foreach($driverSlots as $slot)
                            <p class="text-sm text-gray-600">{{ $slot->start_time }} - {{ $slot->end_time }}</p>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white rounded-2xl shadow p-6 text-center">
                <p class="text-gray-600">Водители не найдены в выбранном городе</p>
            </div>
        @endif
    </div>
@endsection
