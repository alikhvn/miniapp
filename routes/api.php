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
        ->select('id', 'name_en', 'population')
        ->where('name_en', 'ILIKE', "%{$q}%")
        ->orderByRaw("similarity(name_en, ?) DESC", [$q])
        ->limit(10)
        ->get();

    return response()->json($cities);
});
