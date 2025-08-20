<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Inquiry;
use Illuminate\Console\Command;

class UpdateInquiryPooledStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:inquiry-pooled-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the is_pooled and is_pooled_at fields for inquiries based on recent_status_on in inquiry_assignments table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logFile = storage_path('logs/InquiryPoolLog.txt'); // Define the log file path

        try {
        } catch (\Exception $e) {

        }
    }

}
