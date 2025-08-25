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
                    <h4 class="card-title mb-0 flex-grow-1">Chart Of Account Search</h4>
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
                <form action="{{ route('ams.chartOfAccounts.index') }}" method="get" id="accountSearchForm"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body bg-light">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Main Group</label>
                                <select class="form-select main_group" id="main_group" name="main_group">
                                    <option value="">Select Main Group</option>
                                    <option value="Balance Sheet">Balance Sheet</option>
                                    <option value="Profit And Loss Account">Profit And Loss Account</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Sub 1 Group</label>
                                <select class="form-select sub1_group" id="sub1_group" name="sub1_group">
                                    <option value="">Select Sub 1 Group</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Sub 2 Group</label>
                                <select class="form-select sub2_group" id="sub2_group" name="sub2_group">
                                    <option value="">Select Sub 2 Group</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Detailed Group</label>
                                <select class="form-select detailed_group" id="detailed_group" name="detailed_group">
                                    <option value="">Select Detailed Group</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 hstack gap-2 justify-content-end">
                                <button type="button" onclick="window.location.reload()" class="btn btn-danger">
                                    <i class="ri-restart-line me-1 align-bottom"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-equalizer-fill me-1 align-bottom"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Chart Of Accounts List</h4>
                </div>
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
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/ams-custom.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#accountSearchForm').on('submit', function (e) {
                e.preventDefault();
                $('#buttons-datatables').html('<tr><td colspan="12" class="text-center">Loading...</td></tr>');
                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('ams.chartOfAccounts.index') }}",
                    type: "GET",
                    data: formData,
                    success: function (response) {
                        var newTable = $(response).find('#buttons-datatables').html();
                        $('#buttons-datatables').html(newTable);
                        if ($.fn.DataTable.isDataTable('#buttons-datatables')) {
                            $('#buttons-datatables').DataTable().destroy();
                        }
                        $('#buttons-datatables').DataTable({
                            dom: 'Bfrtip',
                            buttons: ['copy', 'csv', 'print'],
                            order: [[0, 'desc']],
                        });
                    },
                    error: function () {
                        $('#buttons-datatables').html('<tr><td colspan="12" class="text-center">Error loading data</td></tr>');
                    }
                });
            });

            $('button[type="reset"]').on('click', function () {
                $('#main_group, #sub1_group, #sub2_group, #detailed_group').val('');

                $('#accountSearchForm').trigger('submit');
            });
        });
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