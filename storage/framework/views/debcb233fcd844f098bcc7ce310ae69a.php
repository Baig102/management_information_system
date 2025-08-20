
<?php $__env->startSection('title'); ?>
    
    HRM
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Dashboards
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            HRM | Employee Roles
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

<?php $__env->startSection('css'); ?>
    <style>

    </style>
<?php $__env->stopSection(); ?>


    <div class="row">
        <div class="col-lg-12">
            <form action="<?php echo e(route('hrm.save-employee-role')); ?>" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2">
                        <h4 class="card-title mb-0 flex-grow-1">Employee Role</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="employee_role" class="form-label">Employee Role <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Employee Role" id="employee_role" name="name">
                                </div>
                            </div><!--end col-->
                        </div>
                    </div><!-- end card body -->
                    <div class="card-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary btn-label waves-effect waves-light"><i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Save Role</button>
                        </div>
                    </div>
                </div>
            </form><!-- end card -->
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Employee Role List</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="table table-bordered nowrap table-striped align-middle" style="width:100%"> <!--c-->
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $employeeRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($role->id); ?></td>
                                        <td><?php echo e($role->name); ?></td>
                                        <td><?php echo e($role->created_at); ?></td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a href="<?php echo e(route('hrm.role-acl-list', $role->id)); ?>" class="dropdown-item text-primary"><i class="ri-eye-fill align-bottom me-2"></i> Access Control</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- end card body -->
            </div>
        </div>
    </div>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    $(function(){
        $(".select2").select2();
        $(".flatpickr-date").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
        var cleave = new Cleave('#cnic', {
            delimiter: '-',
            blocks: [5, 7, 1],
            uppercase: true
        });
    })
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-hrm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/HRM/employee/roles.blade.php ENDPATH**/ ?>