<!-- resources/views/passengers/driver-profile.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto">
        <!-- Driver Profile Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 card-hover">
            <div class="flex items-center mb-6">
                @if($driver->photo_url)
                    <img src="{{ $driver->photo_url }}" class="w-20 h-20 rounded-full object-cover border-4 border-blue-100">
                @else
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                        {{ substr($driver->first_name, 0, 1) }}{{ substr($driver->last_name, 0, 1) }}
                    </div>
                @endif
                <div class="ml-5 flex-1">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $driver->first_name }} {{ $driver->last_name }}</h1>
                    <p class="text-gray-600 text-sm">@{{ $driver->username }}</p>
                    <div class="flex items-center mt-2">
                        <div class="flex items-center bg-yellow-100 px-3 py-1 rounded-full">
                            <span class="material-icons text-yellow-500 text-sm mr-1">star</span>
                            <span class="font-semibold text-yellow-700">{{ number_format($driver->rating, 1) }}</span>
                            <span class="text-yellow-600 text-sm ml-2">({{ $driver->total_ratings }})</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($driver->car_info)
                <div class="border-t pt-5 mb-6">
                    <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <span class="material-icons text-blue-500 mr-2">directions_car</span>
                        Информация об авто
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-3 rounded-xl">
                            <p class="text-xs text-blue-600 mb-1">Модель</p>
                            <p class="font-semibold text-blue-800">{{ $driver->car_info }}</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-xl">
                            <p class="text-xs text-green-600 mb-1">Номер</p>
                            <p class="font-semibold text-green-800">{{ $driver->license_plate }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Stats -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 mb-6">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-blue-600">{{ $driver->completed_rides ?? 0 }}</div>
                        <div class="text-xs text-blue-500">Поездок</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-green-600">{{ $driver->years_experience ?? 1 }}+</div>
                        <div class="text-xs text-green-500">Лет опыта</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-purple-600">4.8★</div>
                        <div class="text-xs text-purple-500">Рейтинг</div>
                    </div>
                </div>
            </div>
        </div>

        @auth
            @if(Auth::user()->role === 'passenger')
                <!-- Order Section -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 card-hover">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 text-center flex items-center justify-center">
                        <span class="material-icons text-green-500 mr-2">directions_car</span>
                        Заказать поездку
                    </h2>

                    <form action="{{ route('passenger.order.create', $driver->id) }}" method="POST" id="orderForm">
                        @csrf

                        <!-- From Address -->
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <span class="material-icons text-blue-500 mr-2">my_location</span>
                                Откуда забрать?
                            </label>
                            <div class="relative">
                                <input type="text" name="pickup_address" required
                                       class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent transition-all duration-200"
                                       placeholder="Ваш текущий адрес">
                                <span class="material-icons absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">location_on</span>
                            </div>
                        </div>

                        <!-- To Address -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <span class="material-icons text-green-500 mr-2">flag</span>
                                Куда едем?
                            </label>
                            <div class="relative">
                                <input type="text" name="destination_address" required
                                       class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent transition-all duration-200"
                                       placeholder="Адрес назначения">
                                <span class="material-icons absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">place</span>
                            </div>
                        </div>

                        <!-- Price Display -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-100 border border-green-200 p-5 rounded-xl mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-green-600 mb-1">Стоимость поездки</p>
                                    <p class="text-3xl font-bold text-green-700">{{ number_format($driver->fixed_price, 0, ',', ' ') }} ₸</p>
                                </div>
                                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="material-icons text-green-600 text-2xl">payments</span>
                                </div>
                            </div>
                            <p class="text-xs text-green-500 mt-2 text-center">Фиксированная цена • Без скрытых платежей</p>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl flex items-center justify-center space-x-3">
                            <span class="material-icons">directions_car</span>
                            <span>Заказать за {{ number_format($driver->fixed_price, 0, ',', ' ') }} ₸</span>
                        </button>
                    </form>
                </div>
            @endif
        @endauth

        <!-- Reviews Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="material-icons text-yellow-500 mr-2">reviews</span>
                Отзывы пассажиров
            </h2>

            @if($driver->reviews->count() > 0)
                <div class="space-y-5">
                    @foreach($driver->reviews as $review)
                        <div class="border-b border-gray-100 pb-5 last:border-b-0 last:pb-0">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="flex items-center bg-yellow-100 px-3 py-1 rounded-full">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="material-icons text-{{ $i <= $review->rating ? 'yellow' : 'gray' }}-400 text-sm">
                                        {{ $i <= $review->rating ? 'star' : 'star_border' }}
                                    </span>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-xs text-gray-500">{{ $review->created_at->format('d.m.Y H:i') }}</span>
                            </div>

                            @if($review->comment)
                                <p class="text-gray-700 mb-3 leading-relaxed">{{ $review->comment }}</p>
                            @endif

                            <p class="text-xs text-gray-500">
                                @if($review->is_anonymous)
                                    <span class="material-icons text-gray-400 text-sm mr-1">visibility_off</span>
                                    Анонимный отзыв
                                @else
                                    От: {{ $review->passenger_name ?? 'Пассажир' }}
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <span class="material-icons text-gray-300 text-4xl mb-3">reviews</span>
                    <p class="text-gray-500">Пока нет отзывов</p>
                    <p class="text-sm text-gray-400 mt-1">Будьте первым, кто оставит отзыв!</p>
                </div>
            @endif

            <!-- Leave Review Button -->
            @auth
                @if(Auth::user()->role === 'passenger')
                    <button onclick="openReviewModal()"
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 rounded-xl font-semibold mt-6 flex items-center justify-center space-x-2">
                        <span class="material-icons">rate_review</span>
                        <span>Оставить отзыв</span>
                    </button>
                @endif
            @endauth
        </div>
    </div>

    <!-- Review Modal -->
    @auth
        @if(Auth::user()->role === 'passenger')
            <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
                <div class="bg-white rounded-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold">Оставить отзыв</h3>
                            <button onclick="closeReviewModal()"
                                    class="text-white hover:text-blue-200 transition-colors">
                                <span class="material-icons">close</span>
                            </button>
                        </div>
                    </div>

                    <div class="p-6">
                        <form action="{{ route('review.store', $driver->id) }}" method="POST">
                            @csrf
                            <!-- Rating -->
                            <div class="mb-5">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Оценка</label>
                                <div class="flex justify-center space-x-1" id="ratingStars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="material-icons text-3xl text-gray-300 cursor-pointer hover:text-yellow-400"
                                              onclick="setRating({{ $i }})"
                                              id="star{{ $i }}">star_border</span>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" value="5" required>
                            </div>

                            <!-- Comment -->
                            <div class="mb-5">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Комментарий</label>
                                <textarea name="comment" rows="4"
                                          class="w-full p-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent"
                                          placeholder="Расскажите о вашей поездке..."></textarea>
                            </div>

                            <!-- Anonymous -->
                            <div class="mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_anonymous" value="1"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-3 text-sm text-gray-700">Оставить анонимный отзыв</span>
                                </label>
                            </div>

                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-3 rounded-xl font-semibold">
                                Отправить отзыв
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <script>
        // Review Modal Functions
        function openReviewModal() {
            document.getElementById('reviewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Rating System
        function setRating(rating) {
            document.getElementById('ratingInput').value = rating;

            for (let i = 1; i <= 5; i++) {
                const star = document.getElementById('star' + i);
                if (i <= rating) {
                    star.textContent = 'star';
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.textContent = 'star_border';
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            }
        }

        // Initialize rating to 5 stars
        document.addEventListener('DOMContentLoaded', function() {
            setRating(5);
        });

        // Form submission smoothness
        document.getElementById('orderForm')?.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.disabled = true;
            button.innerHTML = '<span class="material-icons animate-spin mr-2">refresh</span>Обработка...';
        });

        // Close modal on outside click
        document.getElementById('reviewModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeReviewModal();
        });
    </script>

    <style>
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        input:focus, textarea:focus {
            transform: translateY(-1px);
        }

        #ratingStars span {
            transition: all 0.2s ease;
        }

        #ratingStars span:hover {
            transform: scale(1.2);
        }
    </style>
@endsection
