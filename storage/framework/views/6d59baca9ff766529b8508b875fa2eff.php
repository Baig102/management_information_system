
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.crm'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Dashboards
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Inquiry
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
                <h4 class="card-title mb-0 flex-grow-1">Inquiry Search</h4>
                <div class="flex-shrink-0">
                    <?php if(Auth::user()->role <=3): ?>
                        <button type="button" id="exportToExcelBtn" class="btn btn-soft-info btn-sm material-shadow-none">
                            <i class="ri-file-list-3-line align-middle"></i> Export to Excel
                        </button>
                    <?php endif; ?>
                    <a href="<?php echo e(route('crm.create-inquiry')); ?>" class="btn btn-soft-success btn-sm float-end btn-label  mx-1"><i class="ri-database-fill label-icon align-middle fs-16 me-2"></i> Add Inquiry</a>
                </div>

            </div><!-- end card header -->
            <form action="<?php echo e(route('crm.inquiry-search')); ?>" method="post" id="inquirySearchForm" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-lg-2">
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
                        <div class="col-lg-2 mb-3">
                            <label for="source" class="form-label">Inquiry Source</label>
                            <div class="input-group">
                                
                                <select class="select2 form-control-sm" id="source" name="source"
                                data-placeholder="Select Inquiry Status">
                                    <option></option>
                                    <option value="Web Site" <?php echo e(isset($searchParams['source']) && $searchParams['source'] == 'Web Site' ? 'selected' : ''); ?>>Web Site</option>
                                    <option value="Facebook" <?php echo e(isset($searchParams['source']) && $searchParams['source'] == 'Facebook' ? 'selected' : ''); ?>>Facebook</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 mb-3">
                            <label for="inquiry_from_date" class="form-label">Inquiry From Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="inquiry_from_date" value="<?php echo e($searchParams['inquiry_from_date'] ?? ''); ?>" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="inquiry_from_date" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 mb-3">
                            <label>Inquiry To Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="inquiry_to_date" value="<?php echo e($searchParams['inquiry_to_date'] ?? ''); ?>" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="inquiry_to_date" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label for="inquiry_assignment_status" class="form-label">Inquiry Status</label>
                            <select class="select2 form-control-sm" id="inquiry_assignment_status" name="inquiry_assignment_status"
                                data-placeholder="Select Inquiry Status">
                                <option></option>
                                <option value="1">Pending</option>
                                <option value="2">Assigned</option>
                            </select>
                        </div>

                        <div class="col-lg-2">
                            <label for="agent" class="form-label">Select Agent</label>
                            <select class="select2 form-control-sm" id="agent" name="inquiry_assigned_to"
                                data-placeholder="Select Agent">
                                <option></option>
                                <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($agent->id); ?>" <?php echo e(isset($searchParams['inquiry_assigned_to']) && $searchParams['inquiry_assigned_to'] == $agent->id ? 'selected' : ''); ?>> <?php echo e($agent->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2 col-md-6 mb-3">
                            <label for="lead_passenger_name" class="form-label">Passenger Name</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="lead_passenger_name" value="" placeholder="Passenger Name" class="form-control" id="lead_passenger_name" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 mb-3">
                            <label for="email" class="form-label">Passenger Email</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="email" value="" placeholder="Passenger Email" class="form-control" id="email" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="numnber" name="contact_number" value="" placeholder="Passenger Number" class="form-control" id="contact_number" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6 mb-3">
                            <label for="inquiry_assigned_from_date" class="form-label">Inquiry Assigned From Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="inquiry_assigned_from_date" value="<?php echo e($searchParams['inquiry_assigned_from_date'] ?? ""); ?>" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="inquiry_assigned_from_date" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 mb-3">
                            <label for="inquiry_assigned_to_date" class="form-label">Inquiry Assigned To Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="inquiry_assigned_to_date" value="<?php echo e($searchParams['inquiry_assigned_to_date'] ?? ""); ?>" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="inquiry_assigned_to_date" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <label for="inquiry_action" class="form-label">Inquiry Actions</label>
                            <select class="select2 form-control-sm" id="inquiry_action" name="inquiry_action"
                                data-placeholder="Select Inquiry Action">
                                <option></option>
                                <option value="Follow up">Follow up</option>
                                <option value="Pending">Pending</option>
                                <option value="Booked with us">Booked with us</option>
                                <option value="Booked with other Comapny">Booked with other Comapny</option>
                                <option value="Not Interesting">Not Interesting</option>
                                <option value="Just Looking">Just Looking</option>
                                <option value="Call not Attending">Call not Attending</option>
                                <option value="On Voice Mail">On Voice Mail</option>
                                <option value="Wrong Number">Wrong Number</option>
                                <option value="Fake">Fake</option>
                                <option value="Added On Whatsapp">Added On Whatsapp</option>
                                <option value="Payment Done">Payment Done</option>
                                <option value="No Response">No Response</option>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <?php if(Auth::user()->role <=3): ?>
                            <a href="<?php echo e(route('crm.fetch.facebook.leads')); ?>" id="refreshInquiry" class="btn btn-soft-warning material-shadow-none"> <i class="ri-refresh-line align-middle"></i> Refresh Inquiry </a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('crm.inquiry-list')); ?>" class="btn btn-danger"> <i class="ri-restart-line me-1 align-bottom"></i> Reset </a>
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
                    <h4 class="card-title mb-0 flex-grow-1">Inquiry List</h4>
                    <?php if(Auth::user()->role <=3): ?>
                     <!-- Your Export to Excel button -->
                    
                    <button type="button" id="bulkAssignBtn" class="btn btn-soft-success btn-sm material-shadow-none" onclick="bulk_assign_inquiry()">
                        <i class="ri-file-list-3-line align-middle"></i> Bulk Assign
                    </button>
                    <?php endif; ?>
                </div><!-- end card header -->
                <div class="card-body">

                    <div class="table-responsive">
                        
                        <!-- <table id="alternative-pagination" class="alternative-pagination  table nowrap dt-responsive align-middle table-hover table-bordered align-middle mb-0 fs-12" data-ordering="false"> -->
                        <table id="alternative-pagination" class="table nowrap dt-responsive align-middle table-hover table-bordered align-middle mb-0 fs-12 table-sm" data-ordering="false">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Sr #</th>
                                    <th scope="col">Company & Source Details</th>
                                    <th scope="col">Passenger Details</th>
                                    <th scope="col" style="width: 20%">Flight Details</th>
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

                                        /*if (Auth::user()->role < 3 ||
                                            ($inquiry->inquiry_assignment_status == 2 && (
                                                $inquiry->inquiry_assigned_to == Auth::id() ||
                                                (Auth::user()->role == 4 && in_array($inquiry->inquiry_assigned_to, $agents->toArray()))
                                            ))
                                        ) {
                                            $phone_number = $inquiry->contact_number;
                                            $email = $inquiry->email;
                                        }*/
                                        if (Auth::user()->role < 3 || $inquiry->inquiry_assignment_status == 2 ) {
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
                                            <p class="mb-0"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Ad Set:</span> <?php echo e($inquiry->adset_name ?? "-"); ?></p>
                                            
                                            <p class="mb-0 text-warning"><i class="ri-calendar-todo-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Created At:</span> <?php echo e(date("M-d-Y", strtotime($inquiry->created_at))); ?></p>
                                            <?php if($inquiry->inquiry_assigned_to != null): ?>
                                                <hr>
                                                <p class="mb-0 <?php echo e(($inquiry->inquiryAssigments->recent_status_on) ? "text-success":"text-danger"); ?>"><i class="ri-message-3-line align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Recent Action:</span> <?php echo e($inquiry->inquiryAssigments->recent_status ?? 'No Comments'); ?></p>
                                                <p class="mb-0 <?php echo e(($inquiry->inquiryAssigments->recent_status_on) ? "text-success":"text-danger"); ?>"><i class="ri-calendar-todo-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Recent Action On:</span> <?php echo e(($inquiry->inquiryAssigments->recent_status_on != null) ? date("M-d-Y", strtotime($inquiry->inquiryAssigments->recent_status_on)) : '-'); ?></p>
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
                                            <p class="mb-0"><i class="ri-pie-chart-box-line align-middle fs-12 me-2 "></i>Inquiry Type: <span class="<?php echo e(($inquiry->is_pool == 0 && $inquiry->is_pooled_at != null) ? "text-danger" : "text-success"); ?>"><?php echo e(($inquiry->is_pool == 0 && $inquiry->is_pooled_at != null) ? "Pool Inquiry" : "Fresh Inquiry"); ?></span></p>

                                            <?php if($inquiry->inquiry_assignment_status == 2): ?>
                                                <p class="mb-0"><i class="ri-coupon-line align-middle fs-12 me-2 "></i>Inquiry Status: <span class="<?php echo e(($inquiry->inquiry_assignment_status == 1) ? "text-danger" : "text-success"); ?>"><?php echo e(($inquiry->inquiry_assignment_status == 1) ? "Pending" : "Assigned"); ?></span></p>
                                                <p class="mb-0"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Assignment On:</span> <?php echo e(($inquiry->inquiry_assignment_status == 1) ? "Nill" : $inquiry->inquiry_assignment_on); ?></p>
                                                
                                                <p class="mb-0 text-success"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Assigned To:</span> <?php echo e(userDetails($inquiry->inquiry_assigned_to)->name); ?></p>
                                                <p class="mb-0 text-info"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Assigned By:</span> <?php echo e(($inquiry->inquiryAssigments->agent_id == $inquiry->inquiryAssigments->assigned_by) ? "Self Assigned": userDetails($inquiry->inquiryAssigments->assigned_by)->name); ?></p>
                                            <?php else: ?>
                                                <p class="mb-0"><i class="ri-coupon-line align-middle fs-12 me-2 "></i>Inquiry Status: <span class="text-danger" id="inquiryStatus_<?php echo e($inquiry->id); ?>">Pending</span></p>
                                            <?php endif; ?>
                                            <?php if(Auth::user()->role < 3): ?>
                                            <button tabindex="0" class="btn btn-sm btn-primary btn-label mt-2" role="button" data-bs-toggle="popover" data-bs-trigger="focus" title="Inquiry Page Url" data-bs-content='<a href="<?php echo e($inquiry->inquiry_page_url); ?>" target="_blank"><?php echo e($inquiry->inquiry_page_url); ?></a>'>
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-links-line label-icon align-middle fs-16 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        View Link
                                                    </div>
                                                </div>
                                            </button>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if(Auth::user()->role < 3): ?>
                                                <div class="form-check form-switch form-switch-custom form-switch-secondary">
                                                    <input class="form-check-input inquiry-checkbox" type="checkbox" name="bulk_assign" value="<?php echo e($inquiry->id); ?>" role="switch" id="bulkAssign_<?php echo e($inquiry->id); ?>" data-company="<?php echo e($inquiry->company_id); ?>" <?php echo e(($inquiry->inquiry_assignment_status == 2) ? "disabled" : ""); ?>>
                                                </div>
                                            <?php endif; ?>
                                            <div class="dropdown">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false"> <i class="ri-equalizer-fill"></i> </button>
                                                <ul class="dropdown-menu dropdown-menu-end">

                                                    <li><button type="button" class="dropdown-item text-primary" onclick="view_inquiry(<?php echo e($inquiry->id); ?>)"><i class="ri-eye-fill align-bottom me-2"></i> View</button></li>
                                                    <?php if($inquiry->inquiry_assignment_status == 1 || (Auth::user()->role <= 3)): ?>

                                                    <li><button type="button" class="dropdown-item text-warning <?php echo e(($inquiry->inquiry_assignment_status == 2) ? "disabled" : ""); ?>" id="assignInquiry_<?php echo e($inquiry->id); ?>" onclick="assign_inquiry(<?php echo e($inquiry->id); ?>)"><i class="ri-shield-user-line align-bottom me-2"></i> Assign</button></li>

                                                    <?php if(Auth::user()->role < 3): ?>

                                                    <li><button type="button" class="dropdown-item text-info <?php echo e(($inquiry->inquiry_assignment_status == 2) ? "" : "disabled"); ?>" id="ReAssignInquiry_<?php echo e($inquiry->id); ?>" onclick="re_assign_inquiry(<?php echo e($inquiry->id); ?>)"><i class="ri-reply-line align-bottom me-2"></i> Re-Assign</button></li>

                                                    <?php endif; ?>

                                                    <li><button type="button" class="dropdown-item text-success <?php echo e(($inquiry->inquiry_assignment_status == 2) ? "disabled" : ""); ?>" id="pickupInquiry_<?php echo e($inquiry->id); ?>" onclick="pickup_inquiry(<?php echo e($inquiry->id); ?>)"><i class="ri-shield-star-line align-bottom me-2"></i> Pickup</button></li>

                                                    <li><button type="button" class="dropdown-item text-danger" onclick="delete_inquiry(<?php echo e($inquiry->id); ?>)"><i class="ri-delete-bin-2-line align-bottom me-2"></i> Delete</button></li>
                                                    <?php endif; ?>
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

        // Get the default date from the server if it exists (inquiry_from_date)
        var inquiryFromDate = "<?php echo e($searchParams['inquiry_from_date'] ?? ''); ?>";

        var today = new Date();
        // Set oneMonthPrior to the first day of the current month
        var oneMonthPrior = new Date(today.getFullYear(), today.getMonth(), 1);

        // Initialize Flatpickr for the start date input
        var startDatePicker = flatpickr("#inquiry_from_datee", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            //defaultDate: oneMonthPrior,
            defaultDate: inquiryFromDate ? inquiryFromDate : oneMonthPrior, // Use inquiry_from_date if exists, otherwise default to 1st of the month,
            onChange: function(selectedDates, dateStr, instance) {
                // Set the selected date of the start date input as the min date for the end date input
                endDatePicker.set('minDate', selectedDates[0]);
            }
        });

        // Initialize Flatpickr for the end date input
        var endDatePicker = flatpickr("#inquiry_to_datee", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                // Set the selected date of the end date input as the max date for the start date input
                startDatePicker.set('maxDate', selectedDates[0]);
            }
        });

        // Capture the state of checkboxes across pages
        let selectedInquiries = [];

        // Function to update hidden input value
        function updateHiddenInput() {
            $('#selectedInquiries').val(selectedInquiries.join(','));
        }

        // Handle checkbox selection
        $(document).on('change', '.inquiry-checkbox', function() {
            const inquiryId = $(this).val();
            if ($(this).is(':checked')) {
                // Add to selected
                selectedInquiries.push(inquiryId);
            } else {
                // Remove from selected
                selectedInquiries = selectedInquiries.filter(id => id !== inquiryId);
            }
            updateHiddenInput();
        });
    })

    /*function refreshInquiryyy(){
        console.log('OK');
        $.ajax({
            type: 'GET',
            url: 'https://panel.bestumrahpackagesuk.com/jobs/m8KdVk5TL08092024.php', // in here you should put your query
            success: function (r) {
                console.log('Success');
                console.log(r);
            }
        });
    };*/

    function assign_inquiry(inquiry_id){
        $.ajax({
            type: 'GET',
            url: 'assign-inquiry/' + inquiry_id, // in here you should put your query
            success: function (r) {
                $('.extraLargeModal .modal-content').html(r);
                $('.modal.extraLargeModal').modal('show');

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

                    // location.reload();
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
                    url: '<?php echo e(route("crm.pickup-inquiry", "")); ?>/' + inquiry_id,
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function (response) {
                        console.log(response);

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

    function delete_inquiry(inquiry_id){
        Swal.fire({
            title: 'Are you sure?',
            text: "Once deleted, you won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete Inquiry!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform AJAX request to approve the refund
                 $.ajax({
                    url: '<?php echo e(route("crm.delete-inquiry", "")); ?>/' + inquiry_id,
                    type: 'DELETE',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function (response) {
                        console.log(response);

                        Swal.fire(
                            'Approved!',
                            response.message,
                            'success'
                        );
                        $('#inquiryRow_'+inquiry_id).remove();

                    },
                    error: function (response) {
                        Swal.fire(
                            'Error!',
                            response.responseJSON.message,
                            'error'
                        );
                    }
                });
            }
        });
    };

    $(document).ready(function() {
        // Handle the export button click event
        $('#exportToExcelBtn').on('click', function() {
            // Serialize the entire form to capture all filter fields
            var formData = $('#inquirySearchForm').serialize(); // Assuming your form has an ID "bookingSearchForm"

            // Send AJAX request to export filtered data to Excel
            $.ajax({
                url: "<?php echo e(route('crm.export-inquiry-to-excel')); ?>",
                method: 'POST',
                data: formData, // Pass the serialized form data
                success: function(response) {
                    //console.log(response);
                    // If the request was successful, download the file
                    if (response.url) {
                        window.location.href = response.url;
                    } else {
                        alert('Failed to generate the Excel file.');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('An error occurred while exporting the data.');
                }
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize all popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl, {
                html: true // Allow HTML inside popover content
            });
        });
    });

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-crm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/inquiry/list.blade.php ENDPATH**/ ?>