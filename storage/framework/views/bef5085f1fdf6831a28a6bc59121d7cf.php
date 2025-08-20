<?php $__env->startSection('title'); ?>
<?php echo app('translator')->get('translation.dashboards'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-16 mb-1">Welcome <?php echo e(Auth::user()->name); ?>!</h4>
                            <p class="text-muted mb-0">List of Modules assiged to you.</p>
                        </div>
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="row">
                <div id="teamlist">
                    <div class="team-list grid-view-filter row" id="team-member-list">
                        <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <div class="col-xl-3 col-md-6">
                            <a href="<?php echo e(route($modul->module_link . '.index')); ?>">
                                <div class="card team-box">
                                    <div class="team-cover"> <img src="<?php echo e(asset('build/images/small/img-1.jpg')); ?>" alt="" class="img-fluid"> </div>
                                    <div class="card-body p-4">
                                        <div class="row align-items-center team-row">
                                            <div class="col-lg-4 col">
                                                <div class="team-profile-img">
                                                    <div class="avatar-lg img-thumbnail rounded-circle flex-shrink-0"><?php echo $modul->icon; ?></div>
                                                    <div class="team-content">

                                                        <h5 class="fs-16 mb-1"><?php echo e($modul->name); ?></h5>

                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col">
                                                <div class="text-end"> <a href="<?php echo e(route($modul->module_link . '.index')); ?>" class="btn btn-light view-btn">View Module</a> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div> <!-- end row-->
        </div> <!-- end .h-100-->
    </div> <!-- end col -->
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-modules', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ashraf/Downloads/20082025/resources/views/index.blade.php ENDPATH**/ ?>