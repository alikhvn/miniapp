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
        Schema::create('driver_slots', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('driver_id');
            $table->foreignId('city_id')->constrained('cities');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->date('date');
            $table->timestamps();

            $table->index(['city_id', 'date', 'is_available']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_slots');
    }
};
