<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // включаем расширение (один раз на базу)
        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');

        // создаем индекс
        DB::statement('CREATE INDEX cities_name_trgm_idx ON cities USING gin (name_en gin_trgm_ops)');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS cities_name_trgm_idx');
    }
};
