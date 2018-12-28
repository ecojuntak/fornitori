<?php

namespace App\Http\Controllers\PublicAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\RajaOngkirHelper;

class RajaOngkirController extends Controller
{
    use RajaOngkirHelper;

    public function getShippingCost(Request $request) {
        $client = $this->createClient();
        $payload = $request->all(); 

        $result = $client->request('POST', 'cost', ['form_params' => $payload]);
        return $result->getBody()->getContents();
    }
} 
