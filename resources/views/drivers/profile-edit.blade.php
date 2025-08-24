<!-- resources/views/drivers/profile-edit.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="material-icons text-blue-500 mr-2">edit</span>
                Редактирование профиля
            </h2>

            <form action="{{ route('driver.profile.update') }}" method="POST">
                @csrf

                <!-- Fixed Price -->
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <span class="material-icons text-green-500 mr-2">payments</span>
                        Цена за поездку (₸)
                    </label>
                    <div class="relative">
                        <input type="number" name="fixed_price" value="{{ old('fixed_price', Auth::user()->fixed_price) }}"
                               class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-transparent"
                               placeholder="1000" min="1" step="50" required>
                        <span class="material-icons absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">payments</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Укажите фиксированную стоимость за одну поездку</p>
                </div>

                <!-- Car Info -->
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <span class="material-icons text-blue-500 mr-2">directions_car</span>
                        Информация об авто
                    </label>
                    <input type="text" name="car_info" value="{{ old('car_info', Auth::user()->car_info) }}"
                           class="w-full px-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent"
                           placeholder="Toyota Camry, Chevrolet Spark" required>
                </div>

                <!-- License Plate -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <span class="material-icons text-purple-500 mr-2">confirmation_number</span>
                        Госномер
                    </label>
                    <input type="text" name="license_plate" value="{{ old('license_plate', Auth::user()->license_plate) }}"
                           class="w-full px-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent"
                           placeholder="A123BC" required>
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-[1.02]">
                    💾 Сохранить изменения
                </button>
            </form>

            <div class="border-t mt-6 pt-6">
                <a href="{{ route('driver.dashboard') }}"
                   class="w-full bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold text-center block hover:bg-gray-200 transition-colors">
                    ← Назад в панель
                </a>
            </div>
        </div>
    </div>
@endsection
