<?php

namespace App\Http\Controllers\SABRE;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class SabrePnrConverterController extends Controller
{
    public function getPassengerData($pnrNumber)
    {

        return response()->json(['error' => ''], 404);
    }

    private function getAccessToken($clientId, $clientSecret, $tokenUrl)
    {
        abort(500, 'Unable to obtain access token from Sabre API');
    }
}
