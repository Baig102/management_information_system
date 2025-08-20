<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.dashboards'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Dashboards
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            AMS
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Vendors List</h4>
                    <div class="flex-shrink-0">
                        <a href="<?php echo e(route('ams.vendor.add')); ?>"
                            class="btn btn-soft-success btn-sm float-end btn-label"><i
                                class="ri-numbers-line label-icon align-middle fs-16 me-2"></i> Add Vendor</a>
                    </div>
                </div><!-- end card header -->
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="table table-bordered nowrap table-striped align-middle" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr #</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Website</th>
                                    <th>Address</th>
                                    <th>Supplies</th>
                                    <th>Is Active</th>
                                    <th>Created By</th>
                                    <th>Created On</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($vendor['id']); ?></td>
                                        <td><?php echo e($vendor['name']); ?></td>
                                        <td><?php echo e($vendor['email']); ?></td>
                                        <td><?php echo e($vendor['phone']); ?></td>
                                        <td><?php echo e($vendor['website']); ?></td>
                                        <td><?php echo e($vendor['address']); ?></td>
                                        <td><?php echo e(collect($vendor['supplies'])->pluck('supplies')->implode(', ')); ?></td>
                                        <td><span class="badge <?php echo e(($vendor['is_active'] == 1 ? 'bg-success' : 'bg-danger')); ?>"><?php echo e(($vendor['is_active'] == 1 ) ? 'Active' : 'In-Active'); ?></span></td>
                                        <td><?php echo e(userDetails( $vendor['created_by'])->name); ?></td>
                                        <td><?php echo e($vendor['created_at']); ?></td>
                                        
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
<?php $__env->stopSection(); ?>



<?php $__env->startSection('script'); ?>
    <script>
        function view(id) {
            $.ajax({
                type: 'GET',
                url: 'view-employee-details/' + id, // in here you should put your query
                success: function(r) {
                    //console.log(r);
                    $('.modal-content').show().html(r);
                    $('.modal.fullscreeexampleModal').modal('show') // put your modal id

                }
            });
        }

        function edit(id) {
            //console.log(id);
            $.ajax({
                type: 'GET',
                url: 'edit-employee-details/' + id, // in here you should put your query
                success: function(r) {
                    //console.log(r);
                    $('.modal-content').show().html(r);
                    $('.modal.fullscreeexampleModal').modal('show') // put your modal id

                }
            });
        }

        function delete_record(id) {
            //console.log(id);

            Swal.fire({
                title: "Are you sure to delete?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
                cancelButtonClass: "btn btn-danger w-xs mt-2",
                confirmButtonText: "Yes, delete it!",
                buttonsStyling: !1,
                showCloseButton: !0,
            }).then(function(t) {
                if (t.value) {
                    $.ajax({
                        url: 'lesson-plan/' + id, //'career/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // Handle success response
                            // console.log(response);
                            if (response.code == '200') {
                                $('#row_' + id).remove();
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Lesson Plan has been deleted.",
                                    icon: "success",
                                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                                    buttonsStyling: !1
                                });
                            } else {
                                Swal.fire({
                                    title: "Failed!",
                                    text: "Failed to delete.",
                                    icon: "error",
                                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                                    buttonsStyling: !1
                                });
                            }

                        },
                        error: function(xhr) {
                            // Handle error response
                            console.log(xhr.responseText);
                        }
                    });
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-ams', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/AMS/vendor/index.blade.php ENDPATH**/ ?>