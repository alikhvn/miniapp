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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users');
            $table->foreignId('passenger_id')->nullable()->constrained('users');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('passenger_name')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
