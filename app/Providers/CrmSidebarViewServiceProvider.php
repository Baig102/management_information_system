<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\TypeDetail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class CrmSidebarViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        View::composer('layouts.inc.crm.sidebar', function ($view) {
            // 1. Fetch menu items from type_details where type_id = 1
            $menus = TypeDetail::where('type_id', 1)->orderBy('name', 'ASC')->get();

            // 2. Fetch booking counts grouped by ticket_status
            $bookingCounts = Booking::selectRaw('ticket_status, COUNT(*) as count')
                ->groupBy('ticket_status')
                ->pluck('count', 'ticket_status'); // returns [ticket_status => count]

            // 3. Add booking_count to each menu item
            $menus = $menus->map(function ($menu) use ($bookingCounts) {
                $menu->booking_count = $bookingCounts[$menu->detail_number] ?? 0;
                return $menu;
            });

            // 4. Pass to sidebar view
            $view->with('bookingStatusSidebarMenus', $menus);
        });
    }
}
