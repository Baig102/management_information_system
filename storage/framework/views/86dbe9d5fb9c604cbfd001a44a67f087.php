
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.crm'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Dashboards
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Pool Inquiry
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

<?php $__env->startSection('css'); ?>
    <style>
        .tab-pane.fade {
            transition: all 0.2s;
            transform: translateY(1rem);
        }

        .tab-pane.fade.show {
            transform: translateY(0rem);
        }

        .select2.selection {}
    </style>
<?php $__env->stopSection(); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex p-2">
                <h4 class="card-title mb-0 flex-grow-1">Pool Inquiry Search</h4>
            </div><!-- end card header -->
            <form action="<?php echo e(route('crm.pool-inquiry-list')); ?>" method="get" id="inquirySearchForm" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="company" class="form-label">Select Company</label>
                            <select class="select2 form-control-sm" id="company" name="company_id" data-placeholder="Select Company">
                                <option></option>
                                <?php $__currentLoopData = $assignedCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assiCompany): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($assiCompany->id); ?>"
                                        <?php echo e(isset($searchParams['company_id']) && $searchParams['company_id'] == $assiCompany->id ? 'selected' : ''); ?>>
                                        <?php echo e($assiCompany->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="<?php echo e(route('crm.pool-inquiry-list')); ?>" class="btn btn-danger"> <i class="ri-restart-line me-1 align-bottom"></i> Reset </a>
                        <button type="submit" class="btn btn-primary"> <i class="ri-equalizer-fill me-1 align-bottom"></i> Search </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if(isset($inquiries)): ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Pool Inquiry List</h4>
                    <button type="button" id="bulkAssignBtn" class="btn btn-soft-success btn-sm material-shadow-none" onclick="bulk_assign_inquiry()">
                        <i class="ri-file-list-3-line align-middle"></i> Bulk Assign
                    </button>
                </div><!-- end card header -->
                <div class="card-body">

                    <div class="table-responsive">
                        
                        <table id="alternative-paginationn" class="alternative-paginationn table nowrap dt-responsive align-middle table-hover table-bordered align-middle mb-0 fs-12" data-ordering="false">
                        
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Sr #</th>
                                    <th scope="col">Company & Source Details</th>
                                    <th scope="col">Passenger Details</th>
                                    <th scope="col">Flight Details</th>
                                    <th scope="col">Umrah Details</th>
                                    <th scope="col">Inquiry Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $inquiries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $inquiry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $phone_number   = "************";
                                        $email          = "************";

                                        if (Auth::user()->role < 3 || ($inquiry->inquiry_assignment_status == 2 && ( $inquiry->inquiry_assigned_to == Auth::id() || (Auth::user()->role == 4 && in_array($inquiry->inquiry_assigned_to, $agents->toArray())) ))
                                        ) {
                                            $phone_number = $inquiry->contact_number;
                                            $email = $inquiry->email;
                                        }
                                    ?>

                                    <tr id="inquiryRow_<?php echo e($inquiry->id); ?>">
                                        <td>
                                            <a href="javascript:void(0)" onclick="view_inquiry(<?php echo e($inquiry->id); ?>)" class="fw-semibold me-2">
                                                <?php echo e($inquiry->id); ?>

                                            </a>
                                        </td>

                                        <td>
                                            <p class="mb-0"><i class="ri-coupon-line align-middle text-muted fs-12 me-2"></i>Company: <?php echo e($inquiry->company_name); ?></p>
                                            <p class="mb-0"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Source:</span> <?php echo e($inquiry->source); ?></p>
                                            <p class="mb-0 text-warning"><i class="ri-calendar-todo-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Created At:</span> <?php echo e(date("M-d-Y", strtotime($inquiry->created_at))); ?></p>
                                            <?php if($inquiry->inquiry_assigned_to != null): ?>
                                                <hr>
                                                <p class="mb-0 text-primary"><i class="ri-message-3-line align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Recent Action:</span> <?php echo e($inquiry->inquiryAssigments->recent_status ?? 'Pending'); ?></p>
                                                <p class="mb-0 text-primary"><i class="ri-calendar-todo-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Recent Action On:</span> <?php echo e(($inquiry->inquiryAssigments->recent_status_on != null) ? date("M-d-Y", strtotime($inquiry->inquiryAssigments->recent_status_on)) : '-'); ?></p>
                                            <?php endif; ?>


                                        </td>

                                        <td>
                                            <span>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="fs-12 mb-1">
                                                            <a href="javascript:void(0)" class="link-primary">
                                                                <?php echo e($inquiry->lead_passenger_name); ?>

                                                            </a>
                                                        </h5>

                                                        <p class="text-muted mb-0">
                                                            <a href="tel:<?php echo e($phone_number); ?>" target="__blank" class="text-muted">
                                                                <span class="fw-medium"><i class="ri-phone-line me-2 align-middle text-muted fs-16"></i><?php echo e($phone_number); ?> </span>
                                                            </a>
                                                        </p>

                                                        <p class="text-muted mb-0">
                                                            <a href="mailto:<?php echo e($email); ?>" class="text-muted" target="__blank">
                                                                <span class="fw-medium"><i class="ri-mail-line me-2 align-middle text-muted fs-16"></i><?php echo e($email); ?></span>
                                                            </a>
                                                        </p>

                                                    </div>
                                                </div>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="card ribbon-box border shadow-none mb-lg-0 left material-shadow">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-5">
                                                            <h6 class="mb-2 text-primary">Departure Details</h6>
                                                            <p class="mb-2 fw-medium text-truncate"><i class="ri-flight-takeoff-line"></i> <?php echo e($inquiry->departure_airport); ?></p>
                                                            <p class="mb-0"><i class="ri-calendar-todo-line"></i> <?php echo e($inquiry->departure_date); ?> </p>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <spain class="text-danger display-6"><i class="ri-plane-line"></i></spain>
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <h6 class="mb-2 text-warning">Arrival Details</h6>
                                                            <p class="mb-2 fw-medium text-truncate"><i class="ri-flight-land-line"></i> <?php echo e($inquiry->arrival_airport); ?></p>
                                                            <p class="mb-0"><i class="ri-calendar-todo-line"></i> <?php echo e($inquiry->arrival_date); ?> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0"><i class="ri-coupon-line align-middle text-muted fs-12 me-2"></i>Nights In Makkah: <?php echo e($inquiry->nights_in_makkah); ?></p>
                                            <p class="mb-0"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Nights In Madina:</span> <?php echo e($inquiry->nights_in_madina); ?></p>
                                            <p class="mb-0"><i class=" ri-shield-user-line align-middle text-muted fs-12 me-2"></i><span class="fw-semibold"># Of Adult Travelers:</span> <?php echo e($inquiry->no_of_adult_travelers); ?></p>
                                            <p class="mb-0"><i class=" ri-shield-user-line align-middle text-muted fs-12 me-2"></i><span class="fw-semibold"># Of Child Travelers:</span> <?php echo e($inquiry->no_of_child_travelers); ?></p>
                                            <p class="mb-0"><i class=" ri-shield-user-line align-middle text-muted fs-12 me-2"></i><span class="fw-semibold"># Of Infant Travelers:</span> <?php echo e($inquiry->no_of_infant_travelers); ?></p>
                                        </td>
                                        <td>
                                            <?php if($inquiry->inquiry_assignment_status == 2): ?>
                                                <p class="mb-0"><i class="ri-coupon-line align-middle fs-12 me-2 "></i>Inquiry Status: <span class="<?php echo e(($inquiry->inquiry_assignment_status == 1) ? "text-danger" : "text-success"); ?>"><?php echo e(($inquiry->inquiry_assignment_status == 1) ? "Pending" : "Assigned"); ?></span></p>
                                                <p class="mb-0"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Assignment On:</span> <?php echo e(($inquiry->inquiry_assignment_status == 1) ? "Nill" : $inquiry->inquiry_assignment_on); ?></p>
                                                <p class="mb-0 text-success"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Assigned To:</span> <?php echo e(userDetails($inquiry->inquiry_assigned_to)->name); ?></p>
                                                <p class="mb-0 text-info"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Assigned By:</span> <?php echo e(($inquiry->inquiryAssigments->agent_id == $inquiry->inquiryAssigments->assigned_by) ? "Self Assigned": userDetails($inquiry->inquiryAssigments->assigned_by)->name); ?></p>
                                            <?php else: ?>
                                                <p class="mb-0"><i class="ri-coupon-line align-middle fs-12 me-2 "></i>Inquiry Status: <span class="text-danger" id="inquiryStatus_<?php echo e($inquiry->id); ?>">Pending</span></p>
                                            <?php endif; ?>

                                        </td>

                                        <td>
                                            <?php if(Auth::user()->role < 3): ?>
                                                <div class="form-check form-switch form-switch-custom form-switch-secondary">
                                                    <input class="form-check-input inquiry-checkbox" type="checkbox" name="bulk_assign" value="<?php echo e($inquiry->id); ?>" role="switch" id="bulkAssign_<?php echo e($inquiry->id); ?>" data-company="<?php echo e($inquiry->company_id); ?>" >
                                                </div>
                                            <?php endif; ?>
                                            <div class="dropdown">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false"> <i class="ri-equalizer-fill"></i> </button>
                                                <ul class="dropdown-menu dropdown-menu-end">

                                                    <li><button type="button" class="dropdown-item text-primary" onclick="view_inquiry(<?php echo e($inquiry->id); ?>)"><i class="ri-eye-fill align-bottom me-2"></i> View</button></li>

                                                    <?php if(Auth::user()->role < 3): ?>

                                                    <li><button type="button" class="dropdown-item text-danger <?php echo e(($inquiry->inquiry_assignment_status == 2) ? "" : "disabled"); ?>" id="ReAssignInquiry_<?php echo e($inquiry->id); ?>" onclick="re_assign_inquiry(<?php echo e($inquiry->id); ?>)"><i class="ri-reply-line align-bottom me-2"></i> Assign Inquiry</button></li>

                                                    <?php endif; ?>

                                                    <li><button type="button" class="dropdown-item text-success <?php echo e(($inquiry->inquiry_assignment_status == 2) ? "" : ""); ?>" id="pickupInquiry_<?php echo e($inquiry->id); ?>" onclick="pickup_inquiry(<?php echo e($inquiry->id); ?>)"><i class="ri-shield-star-line align-bottom me-2"></i> Pickup</button></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center"> No Record Found!</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <!-- end table -->
                        <!-- Pagination Links -->
                        <?php echo e($inquiries->links('pagination::bootstrap-5')); ?>

                    </div>
                    <!-- end table responsive -->
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="selected_inquiries" id="selectedInquiries" value="">
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    $(function() {
        $('select.select2').select2();
        $(".flatpickr-date").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $('#alternative-paginationn').dataTable( {
            "pageLength": 100,
            "bPaginate": false,
        } );

    })


    function pickup_inquiry(inquiry_id){
        Swal.fire({
            title: 'Are you sure?',
            text: "Once assigned, you won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Pickup Inquiry!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform AJAX request to approve the refund
                 $.ajax({
                    url: '<?php echo e(route("crm.save-pool-assign-inquiry", "")); ?>/' + inquiry_id,
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function (response) {
                        // console.log(response);

                        Swal.fire(
                            'Approved!',
                            response.message,
                            'success'
                        );
                        $('#pickupInquiry_'+inquiry_id).addClass('disabled');
                        //$('.modal.extraLargeModal').modal('toggle');
                        //$('.modal.fullscreeexampleModal').modal('toggle');
                        //view_booking(booking_id);
                        // Optionally, you can remove the deleted payment record from the DOM
                        //$('#payment_' + paymentId).remove();

                    },
                    error: function (response) {
                        //console.log(response.responseJSON.message);
                        //console.log(JSON.parse(response));
                        Swal.fire(
                            'Error!',
                            response.responseJSON.message,
                            //'There was a problem in pickup inqury. Please try again...',
                            'error'
                        );
                    }
                });
            }
        });
    };

    function re_assign_inquiry(inquiry_id){
        $.ajax({
            type: 'GET',
            url: 're-assign-inquiry/' + inquiry_id, // in here you should put your query
            success: function (r) {

                $('.extraLargeModal .modal-content').html(r);
                $('.modal.extraLargeModal').modal('show');

            }
        });
    };

    function bulk_assign_inquiry(inquiry_id){
        // Clear any previous selections

        const inquiryIds = $('#selectedInquiries').val();
        if (inquiryIds) {

            //console.log(inquiryIds);
            // AJAX call to fetch the modal content
            $.ajax({
                type: 'GET',
                url: 'bulk-assign-inquiries/' + inquiryIds, // Pass the inquiry IDs in the URL
                success: function(response) {
                    $('.extraLargeModal .modal-content').html(response);
                    $('.modal.extraLargeModal').modal('show');

                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error',
                        icon: 'error',
                        text: 'An error occurred while fetching data',
                    });
                }
            });
        } else {
            Swal.fire({
                title: 'Warning',
                icon: 'warning',
                text: 'Please select at least one inquiry.',
            });
        }
    };


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-crm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/inquiry/pool-list.blade.php ENDPATH**/ ?>