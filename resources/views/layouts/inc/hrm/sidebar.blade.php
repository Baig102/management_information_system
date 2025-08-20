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
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>@lang('translation.menu')</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
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

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarEmployees" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmployees">
                        <i class="las la-columns"></i> <span>Employees</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarEmployees">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('hrm.employee-register') }}" class="nav-link">New Employee</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('hrm.all-emp') }}" class="nav-link">Employees List</a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarCompanies" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCompanies">
                        <i class="las la-columns"></i> <span>Companies</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarCompanies">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('hrm.create-company') }}" class="nav-link">New Company</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('hrm.company-list') }}" class="nav-link">Companies List</a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarAcl" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAcl">
                        <i class="las la-columns"></i> <span>Access Control List</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarAcl">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('hrm.employee-role') }}" class="nav-link">Create Role</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href="{{ route('hrm.acl.create') }}" class="nav-link">Create Acl</a>
                            </li> --}}
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
