<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ru')->nullable();
            $table->string('name_kz')->nullable();
            $table->string('country');
            $table->string('iso2')->nullable();
            $table->string('region')->nullable();
            $table->string('capital')->nullable();
            $table->decimal('lat', 10, 6);
            $table->decimal('lng', 10, 6);
            $table->bigInteger('population')->nullable();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
