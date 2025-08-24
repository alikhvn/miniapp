<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['passenger_id']);
            $table->dropForeign(['driver_id']);

            $table->bigInteger('passenger_id')->change();
            $table->bigInteger('driver_id')->change();

            $table->foreign('passenger_id')->references('telegram_id')->on('users')->onDelete('cascade');
            $table->foreign('driver_id')->references('telegram_id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['passenger_id']);
            $table->dropForeign(['driver_id']);

            $table->foreign('passenger_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
