<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
//use App\Http\Controllers\API\PNRConverterController;

class PNRConverterController extends Controller
{
    static public function generateUniqueKey($pnr)
    {

        $pnr = trim($pnr, ' ');
        // Create a hash of the input string (e.g., MD5, SHA1, or SHA256)
        $hash = md5($pnr);  // You could also use hash('sha256', $input) for a longer hash

        // Take the first 8 characters of the hash to ensure it is 8 characters long
        return $referenceNumber = substr($hash, 0, 8);
    }

    public function pnrConvert(Request $request) {

    }

    static public function pnrConvertPnrExpert(Request $request) {
        $pnr = $request->input('pnr');
        $response = [];

        $uniqueKey = '';

        return response()->json(["pnrResponse"=>$response, "pnrKey"=>$uniqueKey, "pnr" => $pnr], 200);

    }
}
