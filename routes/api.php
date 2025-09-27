<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::get('/cities/search', function (Request $request) {
    $q = $request->query('q');

    if (!$q || strlen($q) < 2) {
        return response()->json([]);
    }

    $cities = DB::table('cities')
        ->selectRaw("
        id,
        name->>'ru' as name_ru,
        name->>'kz' as name_kz,
        name->>'en' as name_en
    ")
        ->where(function ($query) use ($q) {
            $query->whereRaw("name->>'ru' ILIKE ?", ["%{$q}%"])
                ->orWhereRaw("name->>'kz' ILIKE ?", ["%{$q}%"])
                ->orWhereRaw("name->>'en' ILIKE ?", ["%{$q}%"]);
        })
        ->orderByRaw("GREATEST(
        similarity(name->>'ru', ?),
        similarity(name->>'kz', ?),
        similarity(name->>'en', ?)
    ) DESC", [$q, $q, $q])
        ->limit(10)
        ->get();





    return response()->json($cities);
});
