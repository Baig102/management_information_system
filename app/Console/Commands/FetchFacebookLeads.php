<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Inquiry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FetchFacebookLeads extends Command
{
    protected $signature = 'facebook:fetch-leads {token?} {--account=all}';
    protected $description = 'Fetch leads from Facebook Ads API and store in database';

    public function handle()
    {

    }
}
