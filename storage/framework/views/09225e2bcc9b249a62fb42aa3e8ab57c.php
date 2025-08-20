<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="<?php echo e(URL::asset('build/images/logo-sm.png')); ?>" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?php echo e(URL::asset('build/images/logo-dark.png')); ?>" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="<?php echo e(URL::asset('build/images/logo-sm.png')); ?>" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?php echo e(URL::asset('build/images/logo-light.png')); ?>" alt="" height="17">
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
                <li class="nav-item d-none">
                    <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="las la-tachometer-alt"></i> <span><?php echo app('translator')->get('translation.dashboards'); ?></span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo e(route('hrm.index')); ?>" class="nav-link"><?php echo app('translator')->get('translation.analytics'); ?></a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarVendors" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarVendors">
                        <i class="las la-columns"></i> <span>Vendors</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarVendors">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo e(route('ams.vendor.add')); ?>" class="nav-link">New Vendor</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('ams.vendor.index')); ?>" class="nav-link">Vendors List</a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarBusinessCustomer" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarBusinessCustomer">
                        <i class="las la-columns"></i> <span>Business Customers</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarBusinessCustomer">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo e(route('ams.businessCustomer.add')); ?>" class="nav-link">New Business Customer</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('ams.businessCustomer.index')); ?>" class="nav-link">Business Customers List</a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end BusinessCustomer Menu -->
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
<?php /**PATH /home/ashraf/Documents/web_dev/mis/resources/views/layouts/inc/ams/sidebar.blade.php ENDPATH**/ ?>