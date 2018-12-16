<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class RajaOngkirController extends Controller
{
    public function getShippingCost(Request $request) {
        $client = $this->createClient();
        $payload = $request->all(); 

        $result = $client->request('POST', 'cost', ['form_params' => $payload]);
        return $result->getBody()->getContents();
    }
} 
