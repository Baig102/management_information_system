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
                    <h4 class="card-title mb-0 flex-grow-1">Companies List</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('hrm.create-company') }}"
                            class="btn btn-soft-success btn-sm float-end btn-label"><i
                                class="ri-numbers-line label-icon align-middle fs-16 me-2"></i> Add Company</a>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="table table-bordered nowrap table-striped align-middle" style="width:100%"> <!--c-->
                            <thead class="table-light">
                                <tr>
                                    {{-- <th>Company ID</th> --}}
                                    <th>Company Name</th>
                                    <th>Invoice Prefix</th>
                                    <th>Booking Number</th>
                                    <th>Invoice Logo</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Website</th>
                                    <th>Address</th>
                                    <th>Is B2B</th>
                                    <th>Is Active</th>
                                    <th>Created By</th>
                                    <th>Create Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($companies as $company)
                                    <tr>
                                        {{-- <td>{{ $company->id }}</td> --}}
                                        <td>{{ $company->name }}</td>
                                        <td>{{ $company->invoice_prefix }}</td>
                                        <td>{{ $company->booking_number }}</td>
                                        <td>
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img class="avatar-xs rounded-circle" src="@if ($company->logo != '') {{ URL::asset('images/companyLogos/' . $company->logo) }}@else{{ URL::asset('build/images/users/avatar-1.jpg') }} @endif" alt="{{ $company->name }}">
                                                </div>
                                                <div class="flex-grow-1">
                                                    {{ $company->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $company->phone }}</td>
                                        <td>{{ $company->email }}</td>
                                        <td>{{ $company->website }}</td>
                                        <td>{{ $company->address }}</td>
                                        <td><span class="badge {{($company->is_b2b == 1 ? 'bg-success' : 'bg-danger')}}">{{ ($company->is_b2b == 1 ) ? 'Yes' : 'No' }}</span></td>
                                        <td><span class="badge {{($company->is_active == 1 ? 'bg-success' : 'bg-danger')}}">{{ ($company->is_active == 1 ) ? 'Active' : 'In-Active' }}</span></td>
                                        <td>{{ $company->created_by ?? 'Imported' }}</td>
                                        <td>{{ $company->created_at }}</td>
                                        <td>
                                            <div class="hstack gap-3 flex-wrap">
                                                <a href="{{ route('hrm.company-edit', $company->id) }}" class="link-danger fs-15"><i class="ri-edit-2-line"></i></a>
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
