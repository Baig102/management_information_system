@extends('layouts.master-hrm')
@section('title')
    {{-- @lang('translation.hrm') --}}
    HRM
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Dashboards
        @endslot
        @slot('title')
            HRM | Employee Roles
        @endslot
    @endcomponent

@section('css')
    <style>

    </style>
@endsection


    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('hrm.save-employee-role') }}" method="post" enctype="multipart/form-data">
            @csrf
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
                                @foreach ($employeeRoles as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->created_at }}</td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a href="{{ route('hrm.role-acl-list', $role->id) }}" class="dropdown-item text-primary"><i class="ri-eye-fill align-bottom me-2"></i> Access Control</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><!-- end card body -->
            </div>
        </div>
    </div>



@endsection

@section('script')
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
@endsection
