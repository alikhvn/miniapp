@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-2xl shadow p-5">
        <h2 class="text-2xl font-bold mb-2">{{ $city->name_en }}</h2>
        <p class="text-gray-600">🌍 {{ $city->lat }}, {{ $city->lng }}</p>
        <p class="text-gray-600">👥 {{ number_format($city->population) }}</p>

        <form method="POST" action="{{ route('city.select', $city->id) }}" class="mt-6">
            @csrf
            <button class="w-full bg-blue-500 text-white py-3 rounded-xl text-lg font-semibold shadow hover:bg-blue-600">
                ✅ Выбрать этот город
            </button>
        </form>
    </div>
@endsection
