<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HouseController extends Controller
{
    public function getData(Request $request): string
    {
        $searchString = Str::squish(preg_replace("/[^A-Za-z0-9 ]/", ' ', $request['search']));

        $housesQuery = DB::table('houses');

        // Filter
        if (@$searchString) {
            $housesQuery->whereFullText('name', $searchString);
        }
        if (@$request['priceFrom']) {
            $housesQuery->where('price', '>=', $request['priceFrom']);
        }
        if (@$request['priceTo']) {
            $housesQuery->where('price', '<=', $request['priceTo']);
        }
        if (is_numeric($request['bedrooms']) && $request['bedrooms'] > 0) {
            $housesQuery->where('bedrooms', '=', $request['bedrooms']);
        }
        if (is_numeric($request['bathrooms']) && $request['bathrooms'] > 0) {
            $housesQuery->where('bathrooms', '=', $request['bathrooms']);
        }
        if (is_numeric($request['storeys']) && $request['storeys'] > 0) {
            $housesQuery->where('storeys', '=', $request['storeys']);
        }

        if (is_numeric($request['garages']) && $request['garages'] > 0) {
            $housesQuery->where('garages', '=', $request['garages']);
        }

        $housesQuery->orderBy('id');
        $houses = $housesQuery->paginate(20)->onEachSide(1)->withQueryString();

        return $houses->toJson();
    }
}
