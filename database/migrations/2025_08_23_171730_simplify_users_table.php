<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Удаляем ненужные поля
            $table->dropColumn(['email', 'email_verified_at', 'password', 'remember_token']);

            // Добавляем Telegram данные
            $table->bigInteger('telegram_id')->unique()->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('phone')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();

            $table->dropColumn(['telegram_id', 'first_name', 'last_name', 'username', 'photo_url', 'phone']);
        });
    }
};
