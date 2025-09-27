<!-- resources/views/cities/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto">
        <!-- Hero Section -->
        <div class="text-center mb-8">
            <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl mx-auto mb-5 flex items-center justify-center shadow-lg">
                <span class="material-icons text-white text-5xl">local_taxi</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-3">Добро пожаловать!</h1>
            <p class="text-gray-600 text-lg">Найдите надежное такси в вашем городе</p>
        </div>

        <!-- City Search Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 card-hover">
            <div class="flex items-center mb-5">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                    <span class="material-icons text-blue-500">location_on</span>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Выберите город</h2>
                    <p class="text-gray-500 text-sm">Начните с выбора вашего города</p>
                </div>
            </div>

            <div class="relative mb-4">
                <span class="material-icons absolute left-3 top-3 text-gray-400">search</span>
                <input type="text" id="city-search" placeholder="Введите название города..."
                       class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent">

                <ul id="suggestions" class="absolute z-50 w-full bg-white border border-gray-200 rounded-xl shadow-lg mt-1 hidden max-h-60 overflow-y-auto"></ul>
            </div>

            @if(session('selected_city'))
                @php $currentCity = \App\Models\City::find(session('selected_city')) @endphp
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                    <div class="flex items-center">
                        <span class="material-icons text-green-500 mr-2">check_circle</span>
                        <span class="text-green-700 font-medium">Выбран город: {{ $currentCity->name['ru'] }}</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        @auth
            <div class="grid grid-cols-2 gap-4 mb-6">
                <a href="{{ route('passenger.search') }}" class="bg-white rounded-2xl shadow-lg p-5 text-center card-hover group">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-500 transition-colors">
                        <span class="material-icons text-blue-500 group-hover:text-white">person</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">Найти такси</h3>
                    <p class="text-gray-500 text-sm">Заказать поездку</p>
                </a>

                @if(Auth::user()->role === 'driver')
                    <a href="{{ route('driver.dashboard') }}" class="bg-white rounded-2xl shadow-lg p-5 text-center card-hover group">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-green-500 transition-colors">
                            <span class="material-icons text-green-500 group-hover:text-white">directions_car</span>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-1">Принять заказ</h3>
                        <p class="text-gray-500 text-sm">Водительская панель</p>
                    </a>
                @else
                    <a href="{{ route('role.selection') }}" class="bg-white rounded-2xl shadow-lg p-5 text-center card-hover group">
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-orange-500 transition-colors">
                            <span class="material-icons text-orange-500 group-hover:text-white">swap_horiz</span>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-1">Сменить роль</h3>
                        <p class="text-gray-500 text-sm">Стать водителем</p>
                    </a>
                @endif
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 text-center">
                <span class="material-icons text-blue-500 text-4xl mb-3">person_add</span>
                <h3 class="font-semibold text-blue-800 mb-2">Начните использовать TaxiApp</h3>
                <p class="text-blue-600 text-sm mb-3">Выберите город и начните поиск такси</p>
            </div>
        @endauth

        <!-- Stats -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
            <h3 class="font-semibold text-lg mb-4">TaxiApp в цифрах</h3>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold">500+</div>
                    <div class="text-sm opacity-90">водителей</div>
                </div>
                <div>
                    <div class="text-2xl font-bold">1K+</div>
                    <div class="text-sm opacity-90">поездок</div>
                </div>
                <div>
                    <div class="text-2xl font-bold">4.8★</div>
                    <div class="text-sm opacity-90">рейтинг</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('city-search');
            const suggestions = document.getElementById('suggestions');
            let timeout = null;

            input.addEventListener('input', () => {
                clearTimeout(timeout);
                timeout = setTimeout(async () => {
                    let query = input.value.trim();
                    if (query.length < 2) {
                        suggestions.classList.add('hidden');
                        return;
                    }

                    try {
                        let response = await fetch(`/cities/search?q=${encodeURIComponent(query)}`);
                        let data = await response.json();

                        suggestions.innerHTML = "";
                        if (data.length > 0) {
                            data.forEach(city => {
                                let li = document.createElement('li');
                                li.innerHTML = `
                            <a href="/city/${city.id}" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0">
                                <span class="material-icons text-gray-400 mr-3">location_city</span>
                                <div>
                                    <div class="font-semibold">${city.name_en}</div>
                                    <div class="text-sm text-gray-500">${city.population ? '👥 ' + new Intl.NumberFormat().format(city.population) : ''}</div>
                                </div>
                            </a>
                        `;
                                suggestions.appendChild(li);
                            });
                            suggestions.classList.remove('hidden');
                        } else {
                            suggestions.classList.add('hidden');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        suggestions.classList.add('hidden');
                    }
                }, 300);
            });

            // Close suggestions when clicking outside
            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                    suggestions.classList.add('hidden');
                }
            });
        });

        // Автодетект города по геопозиции
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(async (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                try {
                    let response = await fetch("/detect-city", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify({ lat, lng })
                    });

                    let data = await response.json();

                    if (data.city) {
                        // автозаполняем input поиска
                        document.getElementById("city-search").value = data.city;
                    }
                } catch (error) {
                    console.error("Ошибка при определении города:", error);
                }
            }, (error) => {
                console.warn("Геопозиция не разрешена пользователем:", error);
            });
        }

    </script>
@endsection
