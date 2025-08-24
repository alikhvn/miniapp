<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
            DB::statement('CREATE INDEX cities_name_en_gin_idx ON cities USING gin (name_en gin_trgm_ops)');
            DB::statement('CREATE INDEX cities_name_ru_gin_idx ON cities USING gin (name_ru gin_trgm_ops)');
            DB::statement('CREATE INDEX cities_name_kz_gin_idx ON cities USING gin (name_kz gin_trgm_ops)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            DB::statement('DROP INDEX IF EXISTS cities_name_en_gin_idx');
            DB::statement('DROP INDEX IF EXISTS cities_name_ru_gin_idx');
            DB::statement('DROP INDEX IF EXISTS cities_name_kz_gin_idx');
    }
};
