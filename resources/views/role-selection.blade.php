<!-- resources/views/role-selection.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-blue-100 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                <span class="material-icons text-blue-500 text-4xl">person</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Выберите вашу роль</h1>
            <p class="text-gray-600">Как вы хотите использовать TaxiApp?</p>
        </div>

        <form method="POST" action="{{ route('role.set') }}">
            @csrf

            <div class="grid grid-cols-1 gap-4 mb-6">
                <!-- Passenger Card -->
                <label class="relative">
                    <input type="radio" name="role" value="passenger" class="hidden peer" checked>
                    <div class="bg-white border-2 border-gray-200 rounded-2xl p-5 transition-all duration-300 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200 peer-checked:shadow-lg cursor-pointer">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                <span class="material-icons text-blue-500">person</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">Пассажир</h3>
                                <p class="text-gray-500 text-sm">Заказывайте поездки и находите водителей</p>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                <span class="material-icons text-white text-sm hidden peer-checked:block">check</span>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Driver Card -->
                <label class="relative">
                    <input type="radio" name="role" value="driver" class="hidden peer">
                    <div class="bg-white border-2 border-gray-200 rounded-2xl p-5 transition-all duration-300 peer-checked:border-green-500 peer-checked:ring-2 peer-checked:ring-green-200 peer-checked:shadow-lg cursor-pointer">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                                <span class="material-icons text-green-500">directions_car</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">Водитель</h3>
                                <p class="text-gray-500 text-sm">Принимайте заказы и зарабатывайте</p>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center">
                                <span class="material-icons text-white text-sm hidden peer-checked:block">check</span>
                            </div>
                        </div>
                    </div>
                </label>
            </div>

            <!-- Driver Fields (Hidden by default) -->
            <div id="driver-fields" class="bg-gray-50 rounded-2xl p-5 mb-6 hidden transition-all duration-300">
                <h3 class="font-semibold text-gray-800 mb-4">Информация для водителя</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Марка и модель авто</label>
                        <input type="text" name="car_info"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-transparent"
                               placeholder="Например: Toyota Camry">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Госномер</label>
                        <input type="text" name="license_plate"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-transparent"
                               placeholder="Например: A123BC">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-4 rounded-xl font-semibold text-lg shadow-lg hover:bg-blue-600 transition-colors transform hover:scale-105">
                Продолжить
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-gray-500 text-sm">
                Вы всегда можете сменить роль в профиле
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const driverFields = document.getElementById('driver-fields');

            roleRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    driverFields.classList.toggle('hidden', this.value !== 'driver');

                    // Add validation for driver fields
                    const driverInputs = driverFields.querySelectorAll('input');
                    driverInputs.forEach(input => {
                        input.toggleAttribute('required', this.value === 'driver');
                    });
                });
            });
        });
    </script>
@endsection
