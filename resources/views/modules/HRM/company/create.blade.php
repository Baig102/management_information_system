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
            HRM | Create New Company
        @endslot
    @endcomponent

@section('css')
    <style>

    </style>
@endsection

<form action="{{ route('hrm.save-company') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Company Details</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('hrm.company-list') }}"
                            class="btn btn-soft-success btn-sm float-end btn-label"><i
                                class="ri-numbers-line label-icon align-middle fs-16 me-2"></i> View Companies</a>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="name" class="form-label">Company Name</label>
                                <input type="text" class="form-control" placeholder="Enter Company Name" id="name" name="name">
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="invoice_prefix" class="form-label">Invoice Prefix</label>
                                <input type="text" class="form-control" placeholder="Enter Invoice Prefix" id="invoice_prefix" name="invoice_prefix" max="5">
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="booking_number" class="form-label">Booking Number</label>
                                <input type="number" class="form-control" placeholder="Enter Booking Number" id="booking_number" name="booking_number">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <!-- Default File Input Example -->
                            <div class="mb-3">
                                <label for="logo" class="form-label">Invoice Logo <span class="text-danger">W:250xH:75</span></label>
                                <input class="form-control" type="file" id="logo" name="logo">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" placeholder="Phone Number" id="phone" name="phone">
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" placeholder="example@gamil.com" id="email" name="email">
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="text" class="form-control" placeholder="Enter Website" id="website" name="website">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" placeholder="Enter address" id="address" name="address">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2 mb-3">
                            <label for="isB2B" class="form-label">Is B2B</label>
                            <!-- Custom Switches -->
                            <div class="form-check form-switch form-switch-custom form-switch-primary">
                                <input class="form-check-input" type="checkbox" name="is_b2b" role="switch" id="isB2B">
                                <label class="form-check-label" for="isB2B">Yes</label>
                            </div>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label for="" class="form-label">Is Active</label>
                            <!-- Custom Switches -->
                            <div class="form-check form-switch form-switch-custom form-switch-primary">
                                <input class="form-check-input" type="checkbox" name="is_active" role="switch" id="isActive" checked>
                                <label class="form-check-label" for="isActive">Yes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Company Bank Details</h4>
                </div><!-- end card header -->
                <div class="card-body" id="bank-details-container">
                    <div class="row" id="bank_details_1">
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Bank Name" id="bank_name" name="bank[1][bank_name]" max="100" required>
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="account_number" class="form-label">Account Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Account Number" id="account_number" name="bank[1][account_number]" max="50" required>
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="account_holder_name" class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Account Holder Name" id="account_holder_name" name="bank[1][account_holder_name]" max="100" required>
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="sort_code" class="form-label">Short Code</label>
                                <input type="text" class="form-control" placeholder="Enter Short Code" id="sort_code" name="bank[1][sort_code]" max="20">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="branch_name" class="form-label">Branch Name</label>
                                <input type="text" class="form-control" placeholder="Enter Branch Name" id="branch_name" name="bank[1][branch_name]">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="bank_address" class="form-label">Bank Address</label>
                                <input type="text" class="form-control" placeholder="Enter Bank Address" id="bank_address" name="bank[1][bank_address]">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="remarks" class="form-label">Remarks</label>
                                <input type="text" class="form-control" placeholder="Enter Remarks" id="remarks" name="bank[1][remarks]">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2 mb-3">
                            <label for="" class="form-label">Is Active</label>
                            <!-- Custom Switches -->
                            <div class="form-check form-switch form-switch-custom form-switch-primary">
                                <input class="form-check-input" type="checkbox" name="bank[1][is_active]" role="switch" id="isActive" checked>
                                <label class="form-check-label" for="isActive">Yes</label>
                            </div>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <a href="javascript:void(0)" onclick="add_bank_row()" class="btn btn-soft-success btn-sm btn-label"><i class="ri-bank-fill label-icon align-middle fs-16 me-2"></i> Add Bank</a>
                        </div>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Invoice Terms & Conditions <span class="text-danger">*</span></h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="invoice_terms_conditions" class="form-label">Terms & Conditions</label>
                            <textarea class="form-control ckeditor-classic" id="invoice_terms_conditions" name="invoice_terms_conditions" rows="10" placeholder="Enter Terms & Conditions"></textarea>
                        </div>
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
                <button type="submit" class="btn btn-primary btn-label waves-effect waves-light"><i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Save Company</button>
            </div>
        </div>
    </div>

</form>

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
        // Remove a Bank Row

    })

    let bankCounter = 1; // Starting with 1 for the first bank details
    function add_bank_row() {
        // Add New Bank Row
        bankCounter++;

        let newRow = `
            <div class="row mt-2" id="bank_details_${bankCounter}">
                <div class="col-lg-2">
                    <div class="mb-3">
                        <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter Bank Name" name="bank[${bankCounter}][bank_name]" max="100" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-3">
                        <label for="account_number" class="form-label">Account Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter Account Number" name="bank[${bankCounter}][account_number]" max="50" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-3">
                        <label for="account_holder_name" class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter Account Holder Name" name="bank[${bankCounter}][account_holder_name]" max="100" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-3">
                        <label for="sort_code" class="form-label">Sort Code</label>
                        <input type="text" class="form-control" placeholder="Enter Sort Code" name="bank[${bankCounter}][sort_code]" max="20">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-3">
                        <label for="branch_name" class="form-label">Branch Name</label>
                        <input type="text" class="form-control" placeholder="Enter Branch Name" name="bank[${bankCounter}][branch_name]">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-3">
                        <label for="bank_address" class="form-label">Bank Address</label>
                        <input type="text" class="form-control" placeholder="Enter Bank Address" name="bank[${bankCounter}][bank_address]">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <input type="text" class="form-control" placeholder="Enter Remarks" name="bank[${bankCounter}][remarks]">
                    </div>
                </div>
                <div class="col-lg-2 mb-3">
                    <label for="" class="form-label">Is Active</label>
                    <div class="form-check form-switch form-switch-custom form-switch-primary">
                        <input class="form-check-input" type="checkbox" name="bank[${bankCounter}][is_active]" role="switch" checked>
                        <label class="form-check-label" for="isActive">Yes</label>
                    </div>
                </div>
                <div class="col-lg-2 mb-3">
                    <button type="button" class="btn btn-danger btn-label waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4" onclick="remove_row(bank_details_${bankCounter})"><i class="ri-indeterminate-circle-line label-icon align-middle fs-16 me-2"></i>Remove</button>
                </div>

            </div>
        `;

        // Append the new row to the container
        $('#bank-details-container').append(newRow);

    };

    // $(document).on('click', '.remove-row', function() {
    //         const rowId = $(this).data('row');
    //         $(`#bank-details-${rowId}`).remove();
    //     });

    function remove_row(row_id) {
        var $row = $(row_id);
        //console.log(row_id, data_type);
        $row.remove();
    };

</script>
@endsection
