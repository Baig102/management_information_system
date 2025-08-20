<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class FacebookLeadsController extends Controller
{
    public function fetchLeads(Request $request)
    {
        if (Auth::user()->role >= 3) {
            return redirect()->back()->with('error', 'Unauthorized access!!');
        }

        // Verify token if required
        $token = config('facebook_leads.security_token');

        $account = $request->input('account', 'all');

        // Dispatch the command
        $exitCode = Artisan::call('facebook:fetch-leads', [
            'token' => $token,
            '--account' => $account
        ]);

        if ($exitCode === 0) {
            $output = Artisan::output();
            // echo '<pre>'; print_r($output); echo '</pre>'; //exit;
            return redirect()->back()->with('success', 'Facebook leads fetched successfully!');
        } else {
            $output = Artisan::output();
            // echo '<pre>'; print_r($output); echo '</pre>'; //exit;
            return redirect()->back()->with('error', 'Error fetching Facebook leads: ');
        }
    }
}
