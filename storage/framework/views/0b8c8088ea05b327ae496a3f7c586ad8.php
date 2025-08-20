
<?php $__env->startSection('title'); ?>
    
    HRM
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Dashboards
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            HRM | Create New ACL
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

<?php $__env->startSection('css'); ?>
    <style>

    </style>
<?php $__env->stopSection(); ?>

    <div class="row d-none">
        <div class="col-12">
            <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Defined Routes (web.php)</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Method</th>
                                    <th>URI</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // Group routes by prefix
                                    $groupedRoutes = [];
                                    foreach (\Route::getRoutes() as $route) {
                                        if (in_array('web', $route->gatherMiddleware())) {
                                            $prefix = $route->getPrefix() ?? '/';
                                            $groupedRoutes[$prefix][] = $route;
                                        }
                                    }
                                ?>

                                <?php $__currentLoopData = $groupedRoutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prefix => $routes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td colspan="4" class="table-active fw-bold">
                                            Prefix: <span class="text-primary"><?php echo e($prefix === '/' ? 'Root' : $prefix); ?></span>
                                        </td>
                                    </tr>
                                    <?php $__currentLoopData = $routes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <?php $__currentLoopData = $route->methods(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge bg-primary"><?php echo e($method); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                            <td><?php echo e($route->uri()); ?></td>
                                            <td><?php echo e($route->getName()); ?></td>
                                            <td>
                                                <?php
                                                    $action = $route->getActionName();
                                                    if (str_contains($action, '@')) {
                                                        $action = str_replace('App\\Http\\Controllers\\', '', $action);
                                                    }
                                                ?>
                                                <?php echo e($action); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>

    <form action="<?php echo e(route('hrm.save-role-acl')); ?>" method="post" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Access Control List Details</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="role" class="form-label">Employee Role</label>
                                <input type="text" class="form-control" id="role" name="role" value="<?php echo e($role->name); ?>" readonly>
                                <input type="hidden" name="role_id" id="role_id" value="<?php echo e($role->id); ?>">
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="module" class="form-label">Module</label>
                                <select class="select2 form-control-sm" id="module" name="module">
                                    <option hidden>select</option>
                                    <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($module->id); ?>" data-link="<?php echo e($module->module_link); ?>"><?php echo e($module->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div><!--end col-->


                    </div>
                    <div class="row">

                        <div class="col-lg-12" id="routes-list">

                        </div><!--end col-->

                    </div>

                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-label waves-effect waves-light"><i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Save ACL</button>
            </div>
        </div>
    </div>

</form>

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

    });

    function get_module() {
        const userId = $('#user').val();  // Get selected user ID
        // Get the module select element
        const modulesSelect = $('#module');

        // Clear previous options and add a loading option
        modulesSelect.empty().append('<option>Loading...</option>');

        // Make an AJAX call to fetch the modules assigned to the selected user
        $.ajax({
            url: `/hrm/get-modules-by-user/${userId}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {

                // console.log('Modules fetched successfully:', data);
                // Clear previous options
                modulesSelect.empty();

                // Check if modules are available
                if (data.modules.length === 0) {
                    // If no modules assigned, show a message
                    modulesSelect.append('<option>No modules assigned</option>');
                } else {
                    modulesSelect.append('<option>Select Module</option>');
                    // Loop through the modules and create options dynamically
                    $.each(data.modules, function(index, module) {
                        modulesSelect.append(
                            $('<option></option>').val(module.id).text(module.name)
                        );
                    });
                }
            },
            error: function(xhr, status, error) {
                // Handle error (e.g., user not found, network issues)
                console.error('Error fetching modules:', error);

                // Clear previous options and add error message
                modulesSelect.empty().append(
                    `<option>Error: ${error}</option>`
                );
            }
        });
    }

    $(document).ready(function() {
        // Trigger when the module selection changes
        $('#module').on('change', function() {
            const moduleId = $(this).val();  // Get selected module ID
            const roleId = $('#role_id').val();  // Get selected user ID
            // console.log('Selected Module ID:', moduleId);

            // Get the routes container element
            const routesContainer = $('#routes-list');

            // Clear previous routes and add a loading indicator
            routesContainer.empty().append('<p>Loading...</p>');

            // Check if roleId or userId is available
            var routeUrl = '/hrm/get-routes-by-module/' + moduleId;
            var userId = null;
            // Conditionally append roleId or userId to the URL based on availability
            if (userId) {
                routeUrl += '/' + userId;
            } else if (roleId) {
                routeUrl += '/' + roleId;
            }


            // Make an AJAX call to fetch routes for the selected module
            $.ajax({
                url:`/hrm/get-routes-by-module/${moduleId}/0/${roleId}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('Routes fetched successfully:', data);
                    // Clear previous content
                    routesContainer.empty();
                    const selectedMenu = data.selected_menu;
                    // Check if routes are available
                    if (Object.keys(data.routes).length === 0) {
                        // If no routes found, show a message
                        routesContainer.html('<p>No routes found for this module.</p>');
                    } else {
                        // Create table structure
                        const table = $('<table class="table table-bordered table-hover"></table>');
                        const thead = $('<thead><tr>' +
                            '<th><input type="checkbox" id="selectAll" /></th>' +
                            '<th>Name</th>' +
                            '<th>URL</th>' +
                            '<th>Route Name</th>' +
                            '<th class="d-none">Action</th>' +
                            '<th class="d-none">Method</th>' +
                            '</tr></thead>');
                        const tbody = $('<tbody></tbody>');

                        // Loop through the routes and create table rows
                        $.each(data.routes, function(index, route) {
                            const tr = $('<tr></tr>');
                            const isChecked = selectedMenu.some(menu => menu.url_name === route.routeName);
                            tr.append(`<td><input type="checkbox" name="routes[]" value="${route.name}__${route.routeName}__${route.url}" ${isChecked ? 'checked' : ''}/></td>`);
                            tr.append(`<td>${route.name}</td>`);
                            tr.append(`<td>${route.url}</td>`);
                            tr.append(`<td>${route.routeName}</td>`);
                            tr.append(`<td class="d-none">${route.action}</td>`);
                            tr.append(`<td class="d-none">${route.route_method}</td>`);
                            tbody.append(tr);
                        });

                        // Append thead and tbody to table
                        table.append(thead).append(tbody);
                        routesContainer.html(table);

                        // Handle select all checkbox
                        $('#selectAll').on('change', function() {
                            $('input[name="routes[]"]').prop('checked', $(this).prop('checked'));
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error (e.g., module not found, network issues)
                    console.error('Error fetching routes:', error);

                    // Clear previous content and add error message
                    routesContainer.empty().append(
                        `<li>Error: ${error}</li>`
                    );
                }
            });
        });
    });


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-hrm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/HRM/employee/role-acl-list.blade.php ENDPATH**/ ?>