<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Province;
use App\City;
use App\Http\Controllers\API\RajaOngkirHelper;

class RegionalController extends Controller
{
    use RajaOngkirHelper;

    public function getProvinces() {
        return response()->json(Province::all());
    }

    public function getCities(Request $request) {
        return response()->json(City::where('province_id', $request->pro_id)->get());
    }

    public function getSubdistricts(Request $request) {
        $client = $this->createClient();

        $result = $client->request('GET', 'subdistrict?city=' . $request->city_id);
        return $result->getBody()->getContents();
    }
}
