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
    AMS
    @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Chart Of Accounts List</h4>
                    <div class="flex-shrink-0">
                        @if (Auth::user()->role <= 3)
                            <button type="button" id="exportToExcelBtn" class="btn btn-soft-info btn-sm material-shadow-none">
                                <i class="ri-file-list-3-line align-middle"></i> Export to Excel
                            </button>&nbsp;
                        @endif
                        <a href="{{ route('ams.chartOfAccounts.add') }}"
                            class="btn btn-soft-success btn-sm float-end btn-label"><i
                                class="ri-numbers-line label-icon align-middle fs-16 me-2"></i> Add Chart of Account</a>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="table table-bordered nowrap table-striped align-middle"
                            style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr #</th>
                                    <th>Account Head</th>
                                    <th>Main Group</th>
                                    <th>Sub Group 1</th>
                                    <th>Sub Group 2</th>
                                    <th>Detailed Group</th>
                                    <th>Sales</th>
                                    <th>Vender</th>
                                    <th>Business Customer</th>
                                    <th>Created By</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accounts as $account)
                                    <tr>
                                        <td>{{ $account['id'] }}</td>
                                        <td>{{ $account['account_head'] }}</td>
                                        <td>{{ $account['main_group'] }}</td>
                                        <td>{{ $account['sub_group_1'] }}</td>
                                        <td>{{ $account['sub_group_2'] }}</td>
                                        <td>{{ $account['detailed_group'] }}</td>
                                        <td>{{ $account['sales'] }}</td>
                                        <td>{{ $account->vendor['name'] ?? '-' }}</td>
                                        <td>{{ $account->customer['name'] ?? '-' }}</td>
                                        <td>{{userDetails($account['created_by'])->name}}</td>
                                        <td>{{ $account['created_at'] }}</td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="javascript:void(0)" class="dropdown-item text-danger"
                                                            onclick="deleteRecord({{ $account['id'] }})">
                                                            <i class="ri-delete-bin-fill align-bottom me-2"></i> Delete
                                                        </a>
                                                    </li><a href="{{ route('ams.chartOfAccounts.edit', $account['id']) }}"
                                                        class="dropdown-item edit-item-btn text-warning"><i
                                                            class="ri-pencil-fill align-bottom me-2"></i> Edit</a>
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
        function deleteRecord(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                customClass: {
                    confirmButton: "btn btn-primary w-xs me-2 mt-2",
                    cancelButton: "btn btn-danger w-xs mt-2"
                },
                buttonsStyling: false,
                showCloseButton: true,
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: '{{ url("ams/chartOfAccounts/delete") }}/' + id,
                        type: 'DELETE',
                        success: function (response) {
                            if (response.success) {
                                $('#row_' + id).remove();
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Chart of Account has been deleted.",
                                    icon: "success",
                                    customClass: {
                                        confirmButton: "btn btn-primary w-xs mt-2"
                                    },
                                    buttonsStyling: false
                                }).then(function () {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "Failed!",
                                    text: response.message || "Failed to delete chart of account.",
                                    icon: "error",
                                    customClass: {
                                        confirmButton: "btn btn-primary w-xs mt-2"
                                    },
                                    buttonsStyling: false
                                });
                            }
                        },
                        error: function () {
                            Swal.fire({
                                title: "Error!",
                                text: "An error occurred while deleting the chart of account.",
                                icon: "error",
                                customClass: {
                                    confirmButton: "btn btn-primary w-xs mt-2"
                                },
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });
        }

        $('#exportToExcelBtn').on('click', function () {
            let ids = [];
            $('#buttons-datatables tbody tr').each(function () {
                ids.push($(this).find('td:first').text()); 
            });
            $.ajax({
                url: "{{ route('ams.chartOfAccounts.export') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    account_ids: ids
                },
                xhrFields: {
                    responseType: 'blob' 
                },
                success: function (data) {
                    let blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "chart_of_accounts.xlsx";
                    link.click();
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        });
    </script>
@endsection