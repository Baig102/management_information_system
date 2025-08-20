{{-- @extends('layouts.master') --}}
@extends('layouts.master-ams')
@section('title')
    @lang('translation.dashboards')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Dashboards
        @endslot
        @slot('title')
            Business Customers
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Business Customers List</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('ams.businessCustomer.add') }}"
                            class="btn btn-soft-success btn-sm float-end btn-label"><i
                                class="ri-numbers-line label-icon align-middle fs-16 me-2"></i> Add Business Customer</a>
                    </div>
                </div><!-- end card header -->
                {{-- <pre>
                    {{ print_r($vendors) }}
                </pre> --}}
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
                                    <th>Is Active</th>
                                    <th>Created By</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($businessCustomers as $businessCustomer)
                                    <tr>
                                        <td>{{ $businessCustomer['id'] }}</td>
                                        <td>{{ $businessCustomer['name'] }}</td>
                                        <td>{{ $businessCustomer['email'] }}</td>
                                        <td>{{ $businessCustomer['phone'] }}</td>
                                        <td>{{ $businessCustomer['website'] }}</td>
                                        <td>{{ $businessCustomer['address'] }}</td>
                                        <td><span class="badge {{($businessCustomer['is_active'] == 1 ? 'bg-success' : 'bg-danger')}}">{{ ($businessCustomer['is_active'] == 1 ) ? 'Active' : 'In-Active' }}</span></td>
                                        <td>{{userDetails( $businessCustomer['created_by'])->name}}</td>
                                        <td>{{ $businessCustomer['created_at'] }}</td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a href="javascript:void(0)" class="dropdown-item text-primary" onclick="view({{ $businessCustomer['id'] }})"><i class="ri-eye-fill align-bottom me-2"></i> View</a></li>
                                                    <li><a href="{{ route('ams.businessCustomer.edit', $businessCustomer['id']) }}" class="dropdown-item edit-item-btn text-warning"><i class="ri-pencil-fill align-bottom me-2"></i> Edit</a>
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
