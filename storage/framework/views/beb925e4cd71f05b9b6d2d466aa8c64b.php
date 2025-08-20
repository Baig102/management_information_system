<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.crm'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Dashboards
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            CRM
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <div class="row mb-3 pb-1 d-none">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-16 mb-1">Good Morning, Anna!</h4>
                    <p class="text-muted mb-0">Here's what's happening with your store today.</p>

                    

                </div>
                <div class="mt-3 mt-lg-0">
                    <form action="javascript:void(0);">
                        <div class="row g-3 mb-0 align-items-center">
                            <div class="col-sm-auto">
                                <div class="input-group">
                                    <input type="text" class="form-control border-0 dash-filter-picker shadow" data-provider="flatpickr"  data-range-date="true" data-date-format="d M, Y" data-deafult-date="01 Jan 2022 to 31 Jan 2022">
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-auto">
                                <button type="button" class="btn btn-soft-success shadow-none"><i class="ri-add-circle-line align-middle me-1"></i>
                                    Add Product</button>
                            </div>
                            <!--end col-->
                            <div class="col-auto">
                                <button type="button"
                                    class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn shadow-none"><i
                                        class="ri-pulse-line"></i></button>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
            </div><!-- end card header -->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
    <form action="<?php echo e(route('crm.booking-list')); ?>" id="bookingSearchForm" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2">
                        <h4 class="card-title mb-0 flex-grow-1">Dashboard Search</h4>

                    </div><!-- end card header -->
                    <div class="card-body bg-light">

                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-lg-2">
                                <label for="company" class="form-label">Select Company</label>
                                <select class="select2 form-control-sm" id="company" name="company_id"
                                    data-placeholder="Select Company">
                                    <option></option>
                                    <?php $__currentLoopData = $assignedCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assiCompany): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($assiCompany->id); ?>"
                                            <?php echo e(isset($searchParams['company_id']) && $searchParams['company_id'] == $assiCompany->id ? 'selected' : ''); ?>>
                                            <?php echo e($assiCompany->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label for="company" class="form-label">Select Date Range</label>
                                <div class="input-group">
                                    <input type="text" class="form-control border-0 dash-filter-picker shadow" data-provider="flatpickr"  data-range-date="true" data-date-format="d M, Y" data-deafult-date="01 Jan 2022 to 31 Jan 2022">
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-2">
                                <label for="agent" class="form-label">Select Agent</label>
                                <select class="select2 form-control-sm" id="agent" name="created_by" data-placeholder="Select Agent">
                                    <option></option>
                                    <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($agent->id); ?>" <?php echo e(isset($searchParams['created_by']) && $searchParams['created_by'] == $agent->id ? 'selected' : ((auth()->user()->role > 2 && auth()->user()->id == $agent->id) ? 'selected' : '')); ?>>
                                            <?php echo e($agent->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <a href="<?php echo e(route('crm.dashboard')); ?>" class="btn btn-danger"> <i class="ri-restart-line me-1 align-bottom"></i> Reset </a>
                            <button type="submit" class="btn btn-primary"> <i class="ri-equalizer-fill me-1 align-bottom"></i> Filter </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                Total Sales</p>
                        </div>
                        <div class="flex-shrink-0 d-none">
                            <h5 class="text-success fs-14 mb-0">
                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                +16.24 %
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                    data-target="<?php echo e($counts['total_sales']); ?>">0</span></h4>
                            <a href="" class="text-decoration-underline d-none">View net earnings</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success rounded fs-3">
                                <i class="bx bx-bar-chart-alt-2"></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Sales Amount</p>
                        </div>
                        <div class="flex-shrink-0 d-none">
                            <h5 class="text-danger fs-14 mb-0">
                                <i class="ri-arrow-right-down-line fs-13 align-middle"></i> -3.57 %
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">£<span class="counter-value"
                                    data-target="<?php echo e($counts['total_sales_value']); ?>">0</span></h4>
                            <a href="" class="text-decoration-underline d-none">View all orders</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info rounded fs-3">
                                <i class="bx bx-pound"></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Deposit Amount</p>
                        </div>
                        <div class="flex-shrink-0 d-none">
                            <h5 class="text-success fs-14 mb-0">
                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                +29.08 %
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">£<span class="counter-value"
                                    data-target="<?php echo e($counts['total_deposits']); ?>">0</span>
                            </h4>
                            <a href="" class="text-decoration-underline d-none">See details</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning rounded fs-3">
                                <i class="bx bx-wallet"></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Balance</p>
                        </div>
                        <div class="flex-shrink-0 d-none">
                            <h5 class="text-muted fs-14 mb-0">
                                +0.00 %
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">£<span class="counter-value"
                                    data-target="<?php echo e($counts['total_balance_pending']); ?>">0</span>
                            </h4>
                            <a href="" class="text-decoration-underline d-none">Withdraw money</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-danger rounded fs-3">
                                <i class="bx bx-money-withdraw"></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Refund</p>
                        </div>
                        <div class="flex-shrink-0 d-none">
                            <h5 class="text-muted fs-14 mb-0">
                                +0.00 %
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">£<span class="counter-value"
                                    data-target="<?php echo e($counts['total_refunds']); ?>">0</span>
                            </h4>
                            <a href="" class="text-decoration-underline d-none">Withdraw money</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-danger rounded fs-3">
                                <i class="bx bxs-doughnut-chart"></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div> <!-- end row-->

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Supplier Wise Revenue</h4>
                    
                </div><!-- end card header -->

                <div class="card-header p-0 border-0 bg-light-subtle d-none">
                    <div class="row g-0 text-center">
                        <div class="col-6 col-sm-3">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1"><span class="counter-value" data-target="7585">0</span></h5>
                                <p class="text-muted mb-0">Orders</p>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-6 col-sm-3">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1">$<span class="counter-value" data-target="22.89">0</span>k</h5>
                                <p class="text-muted mb-0">Earnings</p>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-6 col-sm-3">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1"><span class="counter-value" data-target="367">0</span></h5>
                                <p class="text-muted mb-0">Refunds</p>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-6 col-sm-3">
                            <div class="p-3 border border-dashed border-start-0 border-end-0">
                                <h5 class="mb-1 text-success"><span class="counter-value" data-target="18.92">0</span>%
                                </h5>
                                <p class="text-muted mb-0">Conversation Ratio</p>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                </div><!-- end card header -->

                <div class="card-body p-0 pb-2">
                    <div class="w-100">
                        <div id="supplier_wise_sale_chart" data-colors='["--vz-success", "--vz-primary", "--vz-danger"]'
                            class="apex-charts" dir="ltr"></div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Booking Status</h4>
                    
                </div>
                <div class="card-body p-0">
                    <div>
                        <div id="countries_charts"
                            data-colors='["--vz-primary", "--vz-primary", "--vz-info", "--vz-info", "--vz-danger", "--vz-primary", "--vz-primary", "--vz-warning", "--vz-primary", "--vz-primary"]'
                            class="apex-charts" dir="ltr"></div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div> <!-- end col-->
        <div class="col-xl-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Store Visits by Source</h4>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <span class="text-muted">Report<i class="mdi mdi-chevron-down ms-1"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Download Report</a>
                                <a class="dropdown-item" href="#">Export</a>
                                <a class="dropdown-item" href="#">Import</a>
                            </div>
                        </div>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div id="store-visits-source"
                        data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger", "--vz-info"]'
                        class="apex-charts" dir="ltr"></div>
                </div>
            </div> <!-- .card-->
        </div> <!-- .col-->
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <!-- apexcharts -->
    <script src="<?php echo e(URL::asset('build/libs/apexcharts/apexcharts.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/dashboard-crm.init.js')); ?>"></script>
    <!-- dashboard init -->
    
    <script src="<?php echo e(URL::asset('build/js/pages/dashboard-analytics.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
    
    <script>
        $(function() {
            $('select.select2').select2();
        });
        document.addEventListener("DOMContentLoaded", function() {
            // Chart data from server-side
            const chartData = <?php echo json_encode($supplierWiseSales, 15, 512) ?>;

            const supplierNames = chartData.map(item => item.ticket_supplier);
            const totalBookings = chartData.map(item => item.total_booking);
            const totalNetCosts = chartData.map(item => item.total_net_cost);

            const linechartcustomerColors = getChartColorsArray("supplier_wise_sale_chart");

            if (linechartcustomerColors) {
                var options = {
                    series: [{
                            name: "Bookings",
                            type: "area",
                            data: totalBookings,
                        },
                        {
                            name: "Cost",
                            type: "bar",
                            data: totalNetCosts,
                        },
                        // {
                        //     name: "Refunds",
                        //     type: "line",
                        //     data: [8, 12, 7, 17, 21, 11, 5, 9, 7, 29, 12, 35],
                        // },
                    ],
                    chart: {
                        height: 370,
                        type: "line",
                        toolbar: {
                            show: false,
                        },
                    },
                    stroke: {
                        curve: "straight",
                        dashArray: [0, 0, 8],
                        width: [2, 0, 2.2],
                    },
                    fill: {
                        opacity: [0.1, 0.9, 1],
                    },
                    markers: {
                        size: [0, 0, 0],
                        strokeWidth: 2,
                        hover: {
                            size: 4,
                        },
                    },
                    xaxis: {
                        categories: supplierNames,
                        axisTicks: {
                            show: false,
                        },
                        axisBorder: {
                            show: false,
                        },
                    },
                    grid: {
                        show: true,
                        xaxis: {
                            lines: {
                                show: true,
                            },
                        },
                        yaxis: {
                            lines: {
                                show: false,
                            },
                        },
                        padding: {
                            top: 0,
                            right: -2,
                            bottom: 15,
                            left: 10,
                        },
                    },
                    legend: {
                        show: true,
                        horizontalAlign: "center",
                        offsetX: 0,
                        offsetY: -5,
                        markers: {
                            width: 9,
                            height: 9,
                            radius: 6,
                        },
                        itemMargin: {
                            horizontal: 10,
                            vertical: 0,
                        },
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: "30%",
                            barHeight: "70%",
                        },
                    },
                    colors: linechartcustomerColors,
                    tooltip: {
                        shared: true,
                        y: [{
                                formatter: function(y) {
                                    if (typeof y !== "undefined") {
                                        return y.toFixed(0);
                                    }
                                    return y;
                                },
                            },
                            {
                                formatter: function(y) {
                                    if (typeof y !== "undefined") {
                                        //return "$" + y.toFixed(2) + "k";
                                        return "£" + y.toFixed(2);
                                    }
                                    return y;
                                },
                            },
                            {
                                formatter: function(y) {
                                    if (typeof y !== "undefined") {
                                        return y.toFixed(0) + " Sales";
                                    }
                                    return y;
                                },
                            },
                        ],
                    },
                };
                var chart = new ApexCharts(
                    document.querySelector("#supplier_wise_sale_chart"),
                    options
                );
                chart.render();
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
            // Chart data from server-side
            const chartData = <?php echo json_encode($supplierWiseSales, 15, 512) ?>;

            const supplierNames = chartData.map(item => item.ticket_supplier);
            const totalBookings = chartData.map(item => item.total_booking);
            const totalNetCosts = chartData.map(item => item.total_net_cost);

            const linechartcustomerColors = getChartColorsArray("supplier_wise_sale_chart");

            if (linechartcustomerColors) {
                var options = {
                    series: [{
                            name: "Bookings",
                            type: "area",
                            data: totalBookings,
                        },
                        {
                            name: "Cost",
                            type: "bar",
                            data: totalNetCosts,
                        },
                        // {
                        //     name: "Refunds",
                        //     type: "line",
                        //     data: [8, 12, 7, 17, 21, 11, 5, 9, 7, 29, 12, 35],
                        // },
                    ],
                    chart: {
                        height: 370,
                        type: "line",
                        toolbar: {
                            show: false,
                        },
                    },
                    stroke: {
                        curve: "straight",
                        dashArray: [0, 0, 8],
                        width: [2, 0, 2.2],
                    },
                    fill: {
                        opacity: [0.1, 0.9, 1],
                    },
                    markers: {
                        size: [0, 0, 0],
                        strokeWidth: 2,
                        hover: {
                            size: 4,
                        },
                    },
                    xaxis: {
                        categories: supplierNames,
                        axisTicks: {
                            show: false,
                        },
                        axisBorder: {
                            show: false,
                        },
                    },
                    grid: {
                        show: true,
                        xaxis: {
                            lines: {
                                show: true,
                            },
                        },
                        yaxis: {
                            lines: {
                                show: false,
                            },
                        },
                        padding: {
                            top: 0,
                            right: -2,
                            bottom: 15,
                            left: 10,
                        },
                    },
                    legend: {
                        show: true,
                        horizontalAlign: "center",
                        offsetX: 0,
                        offsetY: -5,
                        markers: {
                            width: 9,
                            height: 9,
                            radius: 6,
                        },
                        itemMargin: {
                            horizontal: 10,
                            vertical: 0,
                        },
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: "30%",
                            barHeight: "70%",
                        },
                    },
                    colors: linechartcustomerColors,
                    tooltip: {
                        shared: true,
                        y: [{
                                formatter: function(y) {
                                    if (typeof y !== "undefined") {
                                        return y.toFixed(0);
                                    }
                                    return y;
                                },
                            },
                            {
                                formatter: function(y) {
                                    if (typeof y !== "undefined") {
                                        //return "$" + y.toFixed(2) + "k";
                                        return "£" + y.toFixed(2);
                                    }
                                    return y;
                                },
                            },
                            {
                                formatter: function(y) {
                                    if (typeof y !== "undefined") {
                                        return y.toFixed(0) + " Sales";
                                    }
                                    return y;
                                },
                            },
                        ],
                    },
                };
                var chart = new ApexCharts(
                    document.querySelector("#supplier_wise_sale_chart"),
                    options
                );
                chart.render();
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-crm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/dashboard.blade.php ENDPATH**/ ?>