
<div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Booking Logs | Booking Number: <?php echo e($booking->booking_prefix . $booking->booking_number); ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">

    

    <div class="profile-timeline">
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <?php $__currentLoopData = $booking->logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logKey => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="accordion-item border-0">
                <div class="accordion-header" id="heading<?php echo e($logKey+1); ?>">
                    <a class="accordion-button p-2 shadow-none collapsed" data-bs-toggle="collapse" href="#collapse<?php echo e($logKey+1); ?>" aria-expanded="false" aria-controls="collapse<?php echo e($logKey+1); ?>">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 avatar-xs">
                                <div class="avatar-title bg-success rounded-circle">
                                    <i class="ri-shopping-bag-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fs-15 mb-0 fw-semibold"><?php echo e($log->action); ?> - <span class="fw-normal"><?php echo e($log->created_at); ?></span></h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div id="collapse<?php echo e($logKey+1); ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo e($logKey+1); ?>" data-bs-parent="#accordionExample">
                    <div class="accordion-body ms-2 ps-5 pt-0">
                        <h6 class="mb-1"><?php echo $log->description; ?></h6>
                        <p class="text-muted mb-0"><?php echo e(userDetails($log->created_by)->name); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


        </div>
    </div>

    

</div>
<div class="modal-footer">
    <a href="javascript:void(0);" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
</div>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/view-log.blade.php ENDPATH**/ ?>