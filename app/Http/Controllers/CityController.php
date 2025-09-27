<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function index()
    {
        return view('cities.index');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query || mb_strlen($query) < 2) {
            return response()->json([]);
        }

        // Чистим запрос от лишних символов
        $cleanQuery = preg_replace('/[^\p{L}\p{N}\s]/u', '', $query);
        $cleanQuery = trim($cleanQuery);

        // Ищем с помощью pg_trgm similarity
        $cities = DB::table('cities')
            ->selectRaw("
        id,
        name->>'ru' as name_ru,
        name->>'kz' as name_kz,
        name->>'en' as name_en
    ")
            ->where(function ($q) use ($cleanQuery) {
                $q->whereRaw("name->>'ru' ILIKE ?", ["%{$cleanQuery}%"])
                    ->orWhereRaw("name->>'kz' ILIKE ?", ["%{$cleanQuery}%"])
                    ->orWhereRaw("name->>'en' ILIKE ?", ["%{$cleanQuery}%"]);
            })
            ->orderByRaw("GREATEST(
    similarity(name->>'ru', ?),
    similarity(name->>'kz', ?),
    similarity(name->>'en', ?)
) DESC", [$cleanQuery, $cleanQuery, $cleanQuery])

            ->limit(10)
            ->get();

        return response()->json($cities);
    }

    public function show($id)
    {
        $city = City::findOrFail($id);
        return view('cities.show', compact('city'));
    }

    public function select($id)
    {
        $city = City::findOrFail($id);
        session(['selected_city' => $city->id]);

        return redirect()->route('home')->with('success', 'Город выбран: ' . $city->name_en);
    }

    public function detectCity(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        if (!$lat || !$lng) {
            return response()->json(['city' => null], 400);
        }

        // Используем OpenStreetMap Nominatim API
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}&accept-language=ru";

        $response = \Illuminate\Support\Facades\Http::get($url)->json();

        $cityName = $response['address']['city']
            ?? $response['address']['town']
            ?? $response['address']['village']
            ?? null;

        if (!$cityName) {
            return response()->json(['city' => null]);
        }

        $city = City::where('name_en', 'ILIKE', $cityName)
            ->orWhere('name_ru', 'ILIKE', $cityName)
            ->orWhere('name_kz', 'ILIKE', $cityName)
            ->first();

        return response()->json([
            'city' => $cityName,
            'city_id' => $city->id ?? null
        ]);
    }


}
