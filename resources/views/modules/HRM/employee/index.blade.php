{{-- @extends('layouts.master') --}}
@extends('layouts.master-hrm')
@section('title')
    @lang('translation.dashboards')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Dashboards
        @endslot
        @slot('title')
            HRM
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Employees List</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('hrm.employee-register') }}"
                            class="btn btn-soft-success btn-sm float-end btn-label"><i
                                class="ri-numbers-line label-icon align-middle fs-16 me-2"></i> Add Employee</a>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="table table-bordered nowrap table-striped align-middle" style="width:100%"> <!--c-->
                            <thead class="table-light">
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Guardian Name</th>
                                    <th>CNIC</th>
                                    <th>Designation</th>
                                    <th>Department</th>
                                    <th>Official Email</th>
                                    <th>Team Lead</th>
                                    <th>Is Active</th>
                                    <th>Created By</th>
                                    <th>Create Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $emp)
                                    <tr>
                                        <td>{{ $emp->code }}</td>
                                        <td>
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="flex-shrink-0">
                                                    {{-- <img src="{{URL::asset('build/images/users/avatar-3.jpg')}}" alt="" class="avatar-xs rounded-circle" /> --}}
                                                    {{-- <img src="{{ URL::asset('build/images/users/' . $emp->picture) }}" alt="" class="avatar-xs rounded-circle" /> --}}
                                                    <img class="avatar-xs rounded-circle" src="@if ($emp->picture != '') {{ URL::asset('images/uploads/Employee/' . $emp->picture) }}@else{{ URL::asset('build/images/users/avatar-1.jpg') }} @endif" alt="{{ $emp->name }}">
                                                </div>
                                                <div class="flex-grow-1">
                                                    {{ $emp->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $emp->guardian_name }}</td>
                                        <td>{{ $emp->cnic }}</td>
                                        <td>{{ $emp->designation }}</td>
                                        <td>{{ $emp->department->name }}</td>
                                        <td><a href="mailto:{{ $emp->official_email }}">{{ $emp->official_email }}</a></td>
                                        {{-- <td>{{ ($emp->team_lead_id) ?? 'Null' }}</td> --}}
                                        <td>{{ ($emp->teamLead->name) ?? 'Null' }}</td>
                                        <td><span class="badge {{($emp->is_active == 1 ? 'bg-success' : 'bg-danger')}}">{{ ($emp->is_active == 1 ) ? 'Active' : 'In-Active' }}</span></td>
                                        <td>{{ $emp->created_by ?? 'Imported' }}</td>
                                        <td>{{ $emp->created_at }}</td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a href="javascript:void(0)" class="dropdown-item text-primary" onclick="view({{ $emp->id }})"><i class="ri-eye-fill align-bottom me-2"></i> View</a></li>
                                                    <li><a href="javascript:void(0)" class="dropdown-item text-success {{ $emp->user ? "d-none" : ""; }}" onclick="create_user({{ $emp->id }})"><i class="ri-shield-user-line align-bottom me-2"></i> User</a></li>
                                                    <li><a href="javascript:void(0)" class="dropdown-item edit-item-btn text-warning" onclick="edit({{$emp->id}})"><i class="ri-pencil-fill align-bottom me-2"></i> Edit</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection



@section('script')
    <script>
        function create_user(id) {
            $.ajax({
                type: 'GET',
                url: 'employee-user-register/' + id,//'employee-user-register/' + id, // in here you should put your query
                success: function(r) {
                    //console.log(r);
                    $('.modal-content').show().html(r);
                    $('.modal.largeModal').modal('show') // put your modal id
                }
            });
        }

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
@endsection
