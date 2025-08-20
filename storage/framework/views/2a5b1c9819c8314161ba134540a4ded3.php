<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="<?php echo e(URL::asset('build/images/logo-sm.png')); ?>" alt="" height="">
            </span>
            <span class="logo-lg">
                <img src="<?php echo e(URL::asset('build/images/logo-dark.png')); ?>" alt="" height="">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="<?php echo e(URL::asset('build/images/logo-sm.png')); ?>" alt="" height="">
            </span>
            <span class="logo-lg">
                <img src="<?php echo e(URL::asset('build/images/logo-light.png')); ?>" alt="" height="">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span><?php echo app('translator')->get('translation.menu'); ?></span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?php echo e(route('crm.index')); ?>">
                        <i class="ri-home-8-line"></i> <span data-key="t-widgets">Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="las la-tachometer-alt"></i> <span><?php echo app('translator')->get('translation.dashboards'); ?></span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo e(route('crm.dashboard')); ?>" class="nav-link"><?php echo app('translator')->get('translation.analytics'); ?></a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarBooking" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarBooking">
                        <i class="las la-columns"></i> <span>Bookings</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarBooking">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item d-none">
                                <a href="<?php echo e(route('crm.flights-search-view')); ?>" class="nav-link">Flights Search</a>
                            </li>
                            <li class="nav-item d-none">
                                <a href="<?php echo e(route('crm.create-booking-pnr')); ?>" class="nav-link">Create Booking PNR</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('crm.create-booking')); ?>" class="nav-link">New Booking</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('crm.booking-list')); ?>" class="nav-link">Bookings List</a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarInquiries" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarInquiries">
                        <i class="las la-columns"></i> <span>Inquiries</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarInquiries">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo e(route('crm.create-inquiry')); ?>" class="nav-link">New Inquiry</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('crm.inquiry-list')); ?>" class="nav-link">Inquiries List</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('crm.pool-inquiry-list')); ?>" class="nav-link">Pool Inquiries List</a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarReports" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarReports">
                        <i class="las la-columns"></i> <span>Reports</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarReports">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo e(route('crm.close-traveling-bookings')); ?>" class="nav-link">Close Traveling Bookings</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('crm.ticket-deadline-bookings')); ?>" class="nav-link">Ticket Dead Line Bookings</a>
                            </li>
                            <?php if(Auth::user()->role <=2): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('crm.bookings-received-payments')); ?>" class="nav-link">Bookings Received Payments</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('crm.bookings-pending-payments')); ?>" class="nav-link">Bookings Installment Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('crm.daily-sales-report')); ?>" class="nav-link">Daily Sales Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('crm.daily-issuance-report')); ?>" class="nav-link">Daily Issuance Report</a>
                            </li>

                            <li class="nav-item">
                                <a href="#sidebarSignInn" class="nav-link text-success" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSignInn" data-key="t-signin"> Booking Status Reports
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarSignInn">
                                    <ul class="nav nav-sm flex-column">
                                        <?php $__currentLoopData = $bookingStatusSidebarMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bookingStatus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <li class="nav-item">
                                            <a href="<?php echo e(route('crm.booking-status-report', $bookingStatus->detail_number)); ?>" class="nav-link"><?php echo e($bookingStatus->details); ?> <span class="badge badge-pill bg-danger" data-key="t-hot"><?php echo e($bookingStatus->booking_count); ?></span></a>
                                        </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a href="#sidebarSignIn" class="nav-link text-danger" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSignIn" data-key="t-signin"> Refund Reports
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarSignIn">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="<?php echo e(route('crm.booking-status-report', 15)); ?>" class="nav-link">Refund Requested Report</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?php echo e(route('crm.booking-status-report', 17)); ?>" class="nav-link">Refund Applied Report</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?php echo e(route('crm.booking-status-report', 16)); ?>" class="nav-link">Refund Received Report</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?php echo e(route('crm.booking-status-report', 18)); ?>" class="nav-link">Refund Pending to Customer Report</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            
                            <?php endif; ?>

                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/layouts/inc/crm/sidebar.blade.php ENDPATH**/ ?>