<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>@lang('translation.menu')</span></li>
                <li class="nav-item d-none">
                    <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="las la-tachometer-alt"></i> <span>@lang('translation.dashboards')</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('hrm.index') }}" class="nav-link">@lang('translation.analytics')</a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->

                <!-- Setups Menu -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarSetups" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarSetups">
                        <i class="las la-cog"></i> <span>Setups</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarSetups">
                        <ul class="nav nav-sm flex-column">
                            <!-- Vendors Submenu -->
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarVendors" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarVendors">
                                    <span>Vendors</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarVendors">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('ams.vendor.add') }}" class="nav-link">New Vendor</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('ams.vendor.index') }}" class="nav-link">Vendors List</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <!-- Business Customers Submenu -->
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarBusinessCustomer" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarBusinessCustomer">
                                    <span>Business Customers</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarBusinessCustomer">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('ams.businessCustomer.add') }}" class="nav-link">New
                                                Business Customer</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('ams.businessCustomer.index') }}"
                                                class="nav-link">Business Customers List</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <!-- Account of Chart Submenu -->
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarAccountChart" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarAccountChart">
                                    <span>Account of Chart</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarAccountChart">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('ams.chartOfAccounts.add') }}" class="nav-link">New Account of Chart</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('ams.chartOfAccounts.index') }}" class="nav-link">Account of Chart List</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Setups Menu -->

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>