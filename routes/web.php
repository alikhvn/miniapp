<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TripController;
use App\Http\Middleware\TelegramAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;


//Route::get('/', function () {
//    return view('welcome');
//});


Route::get('/', [CityController::class, 'index'])->name('home');
Route::get('/cities/search', [CityController::class, 'search'])->name('cities.search');
Route::get('/city/{id}', [CityController::class, 'show'])->name('city.show');
Route::post('/city/select/{id}', [CityController::class, 'select'])->name('city.select');

// Маршруты с Telegram аутентификацией
Route::middleware(['web', TelegramAuth::class])->group(function () {

    // Выбор роли
    Route::get('/role-selection', [RoleController::class, 'showRoleSelection'])->name('role.selection');
    Route::post('/set-role', [RoleController::class, 'setRole'])->name('role.set');
    Route::post('/switch-role', [RoleController::class, 'switchRole'])->name('role.switch');
    Route::get('/switch-role', [RoleController::class, 'switchRole'])->name('role.switch.get');

    // Отзывы
// routes/web.php
    Route::prefix('trips')->group(function () {
        Route::get('/create/{driverId}', [TripController::class, 'create'])->name('trips.create');
        Route::post('/store/{driverId}', [TripController::class, 'store'])->name('trips.store');
        Route::get('/{tripId}', [TripController::class, 'show'])->name('trips.show');
        Route::post('/{tripId}/accept', [TripController::class, 'accept'])->name('trips.accept');
        Route::post('/{tripId}/start', [TripController::class, 'start'])->name('trips.start');
        Route::post('/{tripId}/complete', [TripController::class, 'complete'])->name('trips.complete');
        Route::post('/{tripId}/cancel', [TripController::class, 'cancel'])->name('trips.cancel');
        Route::get('/', [TripController::class, 'index'])->name('trips.index');
    });

// Обновим маршрут отзывов
    Route::post('/review/{tripId}', [ReviewController::class, 'store'])->name('review.store');});

Route::prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('profile');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/switch-role', [ProfileController::class, 'switchRole'])->name('profile.switch-role');
});

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/passenger/search', [PassengerController::class, 'searchDrivers'])->name('passenger.search');
    Route::get('/passenger/driver/{id}', [PassengerController::class, 'showDriver'])->name('passenger.driver.show');
    Route::post('/passenger/order/create/{driverId}', [PassengerController::class, 'createOrder'])->name('passenger.order.create');
    Route::get('/passenger/order/{order}', [PassengerController::class, 'orderStatus'])->name('passenger.order.status')->middleware(['web', 'auth', 'role:passenger']);;
    Route::post('/passenger/order/cancel/{order}', [PassengerController::class, 'cancelOrder'])->name('passenger.order.cancel');
});

// Driver routes
Route::middleware(['web', 'auth', 'driver'])->group(function () {
    Route::get('/driver/dashboard', [DriverController::class, 'dashboard'])->name('driver.dashboard');
    Route::get('/driver/profile/edit', [DriverController::class, 'editProfile'])->name('driver.profile.edit');
    Route::post('/driver/order/accept/{order}', [DriverController::class, 'acceptOrder'])->name('driver.order.accept');
    Route::post('/driver/order/complete/{order}', [DriverController::class, 'completeOrder'])->name('driver.order.complete');
    Route::post('/driver/order/cancel/{order}', [DriverController::class, 'cancelOrder'])->name('driver.order.cancel');
    Route::post('/driver/profile/update', [DriverController::class, 'updateProfile'])->name('driver.profile.update');
    Route::post('/driver/select-city', [DriverController::class, 'selectCity'])->name('driver.select.city');
    Route::post('/driver/slot/create', [DriverController::class, 'createSlot'])->name('driver.slot.create');
    Route::post('/driver/slot/toggle/{id}', [DriverController::class, 'toggleSlot'])->name('driver.slot.toggle');
    Route::post('/driver/order/accept/{order}', [DriverController::class, 'acceptOrder'])->name('driver.order.accept');
    Route::post('/driver/order/start/{order}', [DriverController::class, 'startOrder'])->name('driver.order.start');
    Route::post('/driver/order/complete/{order}', [DriverController::class, 'completeOrder'])->name('driver.order.complete');
    Route::get('/driver/orders', [DriverController::class, 'orders'])->name('driver.orders');

});

Route::post('/review/store/{order}', [ReviewController::class, 'store'])->name('review.store');

