<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Inquiry Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    <div class="row">
        <div class="col lg-12">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex bg-body">
                    <h4 class="card-title mb-0 flex-grow-1">Details of Inquiry</h4>
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-2">
                            <label for="company" class="form-label">Company</label>
                            <input type="text" class="form-control" id="company"
                                value="<?php echo e($inquiry->company_name); ?>" readonly="">
                        </div>
                        <div class="col-lg-2">
                            <label for="source" class="form-label">Source</label>
                            <input type="text" class="form-control" id="source" value="<?php echo e($inquiry->source); ?>"
                                readonly="">
                        </div>
                        <div class="col-lg-2">
                            <label for="passenger" class="form-label">Passenger</label>
                            <input type="text" class="form-control" id="passenger"
                                value="<?php echo e($inquiry->lead_passenger_name); ?>" readonly="">
                        </div>
                        <div class="col-lg-2">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" value="<?php echo e((($inquiry->inquiry_assignment_status == 2 && $inquiry->inquiry_assigned_to == Auth::id()) || Auth::user()->role <= 2) ? $inquiry->email : "*******"); ?>"
                                readonly="">
                        </div>
                        <div class="col-lg-2">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone"
                                value="<?php echo e((($inquiry->inquiry_assignment_status == 2 && $inquiry->inquiry_assigned_to == Auth::id()) || Auth::user()->role <= 2) ? $inquiry->contact_number : "*******"); ?>" readonly="">
                        </div>
                        <div class="col-lg-2">
                            <label for="best_time_to_contact" class="form-label">Best Time To Contact</label>
                            <input type="text" class="form-control" id="best_time_to_contact"
                                value="<?php echo e($inquiry->best_time_to_contact); ?>" readonly="">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-lg-4 h-100">
                            <div class="card ribbon-box border shadow-none mb-lg-0 left material-shadow">
                                <div class="card-body">
                                    <div class="ribbon ribbon-info ribbon-shape">
                                        <?php echo e($inquiry->flight_type == 'R' ? 'Return' : 'One Way'); ?>

                                        
                                        <i class="ri-arrow-left-right-line align-middle"></i>
                                        Airline: <?php echo e($inquiry->airline); ?>


                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-lg-5">
                                            <h6 class="mb-2 text-primary">Departure Details</h6>
                                            <p class="mb-2 fw-medium text-truncate"><i
                                                    class="ri-flight-takeoff-line"></i>
                                                <?php echo e($inquiry->departure_airport); ?></p>
                                            <p class="mb-0"><i class="ri-calendar-todo-line"></i>
                                                <?php echo e($inquiry->departure_date); ?> </p>
                                        </div>
                                        <div class="col-lg-2">
                                            <spain class="text-danger display-6"><i class="ri-plane-line"></i></spain>
                                        </div>
                                        <div class="col-lg-5">
                                            <h6 class="mb-2 text-warning">Arrival Details</h6>
                                            <p class="mb-2 fw-medium text-truncate"><i class="ri-flight-land-line"></i>
                                                <?php echo e($inquiry->arrival_airport); ?></p>
                                            <p class="mb-0"><i class="ri-calendar-todo-line"></i>
                                                <?php echo e($inquiry->arrival_date); ?> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 h-100">
                            <div class="card ribbon-box border shadow-none mb-lg-0 left material-shadow">
                                <div class="card-body">
                                    <p class="mb-0"><i
                                            class="ri-coupon-line align-middle text-muted fs-12 me-2"></i>Nights In
                                        Makkah: <?php echo e($inquiry->nights_in_makkah); ?></p>
                                    <p class="mb-0"><i
                                            class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span
                                            class="fw-semibold">Nights In Madina:</span>
                                        <?php echo e($inquiry->nights_in_madina); ?></p>
                                    <p class="mb-0"><i
                                            class=" ri-shield-user-line align-middle text-muted fs-12 me-2"></i><span
                                            class="fw-semibold"># Of Travelers:</span>
                                        <?php echo e($inquiry->no_of_adult_travelers); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card ribbon-box border shadow-none mb-lg-0 left material-shadow">
                                <div class="card-body">
                                    <?php if($inquiry->inquiry_assignment_status == 2): ?>
                                        <p class="mb-0"><i
                                                class="ri-coupon-line align-middle fs-12 me-2 "></i>Inquiry Status:
                                            <span
                                                class="<?php echo e($inquiry->inquiry_assignment_status == 1 ? 'text-danger' : 'text-success'); ?>"><?php echo e($inquiry->inquiry_assignment_status == 1 ? 'Pending' : 'Assigned'); ?></span>
                                        </p>
                                        <p class="mb-0"><i
                                                class="ri-calendar-check-fill align-middle fs-12 me-2"></i><span
                                                class="fw-semibold">Assignment On:</span>
                                            <?php echo e($inquiry->inquiry_assignment_status == 1 ? 'Nill' : $inquiry->inquiry_assignment_on); ?>

                                        </p>

                                        <p class="mb-0 text-success"><i
                                                class="ri-calendar-check-fill align-middle fs-12 me-2"></i><span
                                                class="fw-semibold">Assigned To:</span> <?php echo e(userDetails($inquiry->inquiry_assigned_to)->name); ?></p>

                                        <p class="mb-0 text-info"><i
                                                class="ri-calendar-check-fill align-middle fs-12 me-2"></i><span
                                                class="fw-semibold">Assigned By:</span>
                                            <?php echo e($inquiry->inquiryAssigments->agent_id == $inquiry->inquiryAssigments->assigned_by ? 'Self Assigned' : 'Administrator Assigned'); ?>

                                        </p>
                                        <p class="mb-0 text-warning"><i
                                                class="ri-calendar-check-fill align-middle fs-12 me-2"></i><span
                                                class="fw-semibold">Comments:</span><?php echo e($inquiry->inquiryAssigments->comments); ?>

                                        </p>
                                    <?php else: ?>
                                        <p class="mb-0"><i
                                                class="ri-coupon-line align-middle fs-12 me-2 "></i>Inquiry Status:
                                            <span class="text-danger"
                                                id="inquiryStatus_<?php echo e($inquiry->id); ?>">Pending</span>
                                        </p>
                                    <?php endif; ?>

                                </div>
                            </div>

                        </div>
                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
    </div>

    <div class="row">
        <div class="col lg-12">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex p-2 bg-info">
                    <h4 class="card-title mb-0 flex-grow-1">Communication Details</h4>
                    <?php if($inquiry->inquiry_assignment_status == 2): ?>
                    <div class="flex-shrink-0">
                        <a href="javascript:void(0)" onclick="inquiry_communicate(<?php echo e($inquiry->id); ?>, <?php echo e($inquiry->inquiryAssigments->id); ?>)" class="btn btn-soft-success btn-sm float-end btn-label"><i class="ri-shield-user-line label-icon align-middle fs-16 me-2"></i> Communicate</a>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <?php if($inquiry->inquiry_assignment_status == 2): ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive table-card">
                                    <table class="table table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col">Id</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Comments</th>
                                                <th scope="col">Communicated On</th>
                                                <th scope="col">Communicated By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $inquiry->inquiryAssigments->actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><a href="#" class="fw-semibold"><?php echo e($action->id); ?></a></td>
                                                <td><?php echo e($action->inquiry_status); ?></td>
                                                <td><?php echo e($action->comments); ?></td>
                                                <td><?php echo e($action->created_at); ?></td>
                                                <td><?php echo e(userDetails($action->created_by)->name); ?></td>
                                                
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="5">No Record Found</td>
                                            </tr>
                                            <?php endif; ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- danger Alert -->
                        <div class="alert alert-danger material-shadow" role="alert">
                            <strong> No! </strong> Record Found...
                        </div>
                    <?php endif; ?>


                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
    </div>


</div>

<div class="modal-footer">
    <div class="hstack gap-2 justify-content-end">
        <input type="hidden" name="inquiry_id" id="inquiry_id" value="<?php echo e($inquiry->id); ?>">

        
    </div>
    <a href="javascript:void(0);" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i
            class="ri-close-line me-1 align-middle"></i> Close</a>
    
</div>

<script>

</script>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/inquiry/modals/view.blade.php ENDPATH**/ ?>