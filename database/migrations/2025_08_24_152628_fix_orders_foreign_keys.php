<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Удаляем старые foreign keys
            $table->dropForeign(['passenger_id']);
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['city_id']);

            // Добавляем новые foreign keys с правильными ссылками
            $table->foreign('passenger_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['passenger_id']);
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['city_id']);
        });
    }
};
