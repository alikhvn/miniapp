<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $cities = City::select('id', 'name_en', 'name_ru', 'name_kz', 'country', 'population')
            ->whereRaw('name_en % ?', [$cleanQuery])
            ->orWhereRaw('name_ru % ?', [$cleanQuery])
            ->orWhereRaw('name_kz % ?', [$cleanQuery])
            ->orderByRaw('similarity(name_en, ?) DESC', [$cleanQuery])
            ->orderByRaw('similarity(name_ru, ?) DESC', [$cleanQuery])
            ->orderByRaw('similarity(name_kz, ?) DESC', [$cleanQuery])
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

        return redirect()->route('home')->with('success', 'Город выбран: '.$city->name_en);
    }
}
