<!-- resources/views/drivers/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Панель водителя</h2>

        <!-- Форма выбора города -->
        <div class="bg-white rounded-2xl shadow p-6 mb-6">
            <h3 class="text-xl font-semibold mb-4">📍 Выберите город работы</h3>
            <form action="{{ route('driver.select.city') }}" method="POST" class="mb-4">
                @csrf
                <div class="relative">
                    <input type="text" id="city-search" placeholder="Поиск города..."
                           class="w-full p-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <ul id="city-suggestions" class="absolute z-50 w-full bg-white border rounded-xl shadow mt-1 hidden max-h-60 overflow-y-auto"></ul>
                </div>

                <div id="selected-city-display" class="mt-4 p-3 bg-blue-50 rounded-xl hidden">
                    <p class="font-semibold">Выбранный город: <span id="selected-city-name"></span></p>
                    <input type="hidden" name="city_id" id="selected-city-id">
                    <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-xl text-sm">
                        Подтвердить город
                    </button>
                </div>
            </form>

            @if(session('selected_city'))
                @php
                    $currentCity = \App\Models\City::find(session('selected_city'));
                @endphp
                <div class="p-3 bg-green-50 rounded-xl">
                    <p class="font-semibold">✅ Текущий город: {{ $currentCity->name_en }}</p>
                </div>
            @endif
        </div>

        <!-- Текущий тариф -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-blue-800">Ваш текущий тариф</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format(Auth::user()->fixed_price, 0, ',', ' ') }} ₸</p>
                </div>
                <a href="{{ route('driver.profile.edit') }}"
                   class="bg-blue-500 text-white px-4 py-2 rounded-xl hover:bg-blue-600 transition-colors">
                    ✏️ Изменить
                </a>
            </div>
        </div>

        <!-- Новые заказы -->
        @if($pendingOrders->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">🆕 Новые заказы</h2>
                @foreach($pendingOrders as $order)
                    <div class="border border-orange-200 rounded-xl p-4 mb-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-semibold">Заказ #{{ $order->id }}</p>
                                <p class="text-sm text-gray-600">{{ $order->created_at->format('d.m.Y H:i') }}</p>
                            </div>
                            <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs">Ожидает</span>
                        </div>

                        <div class="mb-3">
                            <p class="text-sm"><span class="font-semibold">📍 От:</span> {{ $order->pickup_address }}</p>
                            <p class="text-sm"><span class="font-semibold">🎯 Куда:</span> {{ $order->destination_address }}</p>
                            <p class="text-sm"><span class="font-semibold">💰 Цена:</span> {{ number_format($order->price, 0, ',', ' ') }} ₸</p>
                            <p class="text-sm"><span class="font-semibold">👤 Пассажир:</span> {{ $order->passenger->first_name }} {{ $order->passenger->last_name }}</p>
                            @if($order->passenger->phone)
                                <p class="text-sm"><span class="font-semibold">📞 Телефон:</span> {{ $order->passenger->phone }}</p>
                            @endif
                        </div>

                        <form action="{{ route('driver.order.accept', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-xl font-semibold hover:bg-green-600 transition-colors">
                                ✅ Принять заказ
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Активные заказы -->
        @if($activeOrders->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">🚗 Активные поездки</h2>
                @foreach($activeOrders as $order)
                    <div class="border border-blue-200 rounded-xl p-4 mb-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-semibold">Заказ #{{ $order->id }}</p>
                                <p class="text-sm text-gray-600">Принят: {{ $order->accepted_at->format('d.m.Y H:i') }}</p>
                            </div>
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                        {{ $order->status === 'accepted' ? 'Принят' : 'В пути' }}
                    </span>
                        </div>

                        <div class="mb-3">
                            <p class="text-sm"><span class="font-semibold">📍 От:</span> {{ $order->pickup_address }}</p>
                            <p class="text-sm"><span class="font-semibold">🎯 Куда:</span> {{ $order->destination_address }}</p>
                            <p class="text-sm"><span class="font-semibold">💰 Цена:</span> {{ number_format($order->price, 0, ',', ' ') }} ₸</p>
                            <p class="text-sm"><span class="font-semibold">👤 Пассажир:</span> {{ $order->passenger->first_name }} {{ $order->passenger->last_name }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            @if($order->status === 'accepted')
                                <form action="{{ route('driver.order.start', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-xl font-semibold text-sm hover:bg-blue-600 transition-colors">
                                        🚗 Начать поездку
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('driver.order.complete', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-xl font-semibold text-sm hover:bg-green-600 transition-colors">
                                    ✅ Завершить
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Форма добавления слота -->
        @if(session('selected_city'))
            <div class="bg-white rounded-2xl shadow p-6 mb-6">
                <h3 class="text-xl font-semibold mb-4">➕ Добавить рабочий слот</h3>
                <form action="{{ route('driver.slot.create') }}" method="POST">
                    @csrf
                    <input type="hidden" name="city_id" value="{{ session('selected_city') }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Город</label>
                            <div class="p-3 bg-gray-100 rounded-xl">
                                {{ $currentCity->name_en }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Дата</label>
                            <input type="date" name="date" required
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full p-3 border rounded-xl">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Время начала</label>
                            <input type="time" name="start_time" required class="w-full p-3 border rounded-xl">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Время окончания</label>
                            <input type="time" name="end_time" required class="w-full p-3 border rounded-xl">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-xl font-semibold">
                        Добавить слот
                    </button>
                </form>
            </div>
        @endif

        <!-- Мои слоты -->
        <div class="bg-white rounded-2xl shadow p-6">
            <h3 class="text-xl font-semibold mb-4">📋 Мои рабочие слоты</h3>
            @if($driverSlots->count() > 0)
                @foreach($driverSlots as $slot)
                    <div class="border rounded-xl p-4 mb-3 flex justify-between items-center">
                        <div>
                            <p class="font-semibold">{{ $slot->city->name_en ?? 'Неизвестный город' }}</p>
                            <p class="text-sm text-gray-600">{{ $slot->date }} | {{ $slot->start_time }} - {{ $slot->end_time }}</p>
                            <span class="text-sm {{ $slot->is_available ? 'text-green-600' : 'text-gray-500' }}">
                                {{ $slot->is_available ? '✅ Доступен' : '❌ Не доступен' }}
                            </span>
                        </div>
                        <form action="{{ route('driver.slot.toggle', $slot->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-gray-200 px-4 py-2 rounded-xl text-sm hover:bg-gray-300 transition-colors">
                                {{ $slot->is_available ? 'Отключить' : 'Включить' }}
                            </button>
                        </form>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500 text-center">У вас пока нет рабочих слотов</p>
            @endif
        </div>

        <!-- Завершенные заказы -->
        @if($completedOrders->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6 mt-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">📊 История поездок</h2>
                @foreach($completedOrders as $order)
                    <div class="border border-gray-200 rounded-xl p-4 mb-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-semibold">Заказ #{{ $order->id }}</p>
                                <p class="text-sm text-gray-600">Завершен: {{ $order->completed_at->format('d.m.Y H:i') }}</p>
                            </div>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Завершен</span>
                        </div>

                        <div class="mb-3">
                            <p class="text-sm"><span class="font-semibold">📍 Маршрут:</span> {{ $order->pickup_address }} → {{ $order->destination_address }}</p>
                            <p class="text-sm"><span class="font-semibold">💰 Заработок:</span> {{ number_format($order->price, 0, ',', ' ') }} ₸</p>
                            <p class="text-sm"><span class="font-semibold">👤 Пассажир:</span> {{ $order->passenger->first_name }} {{ $order->passenger->last_name }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('city-search');
            const suggestions = document.getElementById('city-suggestions');
            const selectedCityDisplay = document.getElementById('selected-city-display');
            const selectedCityName = document.getElementById('selected-city-name');
            const selectedCityId = document.getElementById('selected-city-id');

            let timeout = null;

            searchInput.addEventListener('input', () => {
                clearTimeout(timeout);
                timeout = setTimeout(async () => {
                    let query = searchInput.value.trim();
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
                            <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-100 city-item"
                                    data-id="${city.id}" data-name="${city.name_en}">
                                ${city.name_en}
                            </button>
                        `;
                                suggestions.appendChild(li);
                            });
                            suggestions.classList.remove('hidden');
                        } else {
                            suggestions.classList.add('hidden');
                        }
                    } catch (error) {
                        console.error('Error fetching cities:', error);
                    }
                }, 300);
            });

            // Обработка выбора города
            suggestions.addEventListener('click', (e) => {
                if (e.target.classList.contains('city-item')) {
                    const cityId = e.target.dataset.id;
                    const cityName = e.target.dataset.name;

                    selectedCityId.value = cityId;
                    selectedCityName.textContent = cityName;
                    selectedCityDisplay.classList.remove('hidden');

                    suggestions.classList.add('hidden');
                    searchInput.value = '';
                }
            });

            // Закрытие выпадающего списка при клике outside
            document.addEventListener('click', (e) => {
                if (!searchInput.contains(e.target) && !suggestions.contains(e.target)) {
                    suggestions.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
