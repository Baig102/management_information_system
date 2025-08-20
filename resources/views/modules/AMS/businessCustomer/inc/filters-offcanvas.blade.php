<form action="#" method="post" id="flights-search">
    @csrf
    <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="filters-offcanvas">
        <div class="d-flex align-items-center bg-primary bg-gradient p-3 offcanvas-header">
            <h5 class="m-0 me-2 text-white">Find Flights</h5>

            <button type="button" class="btn-close btn-close-white ms-auto" id="customizerclose-btn"
                data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div data-simplebar class="h-100">
                <div class="p-4">
                    <h6 class="mb-0 fw-semibold text-uppercase">Search Flights</h6>
                    <p class="text-muted">Find and compare cheap flights</p>

                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="code_of_employee" class="form-label">Code of Employee</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="code" class="form-control form-control-icon" id="code"
                                value="{{ old('code') }}" placeholder="Code of Employee">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="name" class="form-label">Name of Employee</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="name" class="form-control form-control-icon" id="name"
                                value="{{ old('name') }}" placeholder="Name of Employee">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="guardian_name" class="form-label">Guardian Name</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="guardian_name" class="form-control form-control-icon"
                                id="guardian_name" value="{{ old('guardian_name') }}" placeholder="Guardian Name">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="phone" class="form-label">Phone</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="phone" name="phone" class="form-control form-control-icon" id="phone"
                                value="{{ old('phone') }}" placeholder="Phone">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="cnic" class="form-label">CNIC</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="cnic" class="form-control form-control-icon cleave-cnic"
                                id="cnic" value="{{ old('cnic') }}" placeholder="CNIC">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="employee_role" class="form-label">Employment Role</label>
                        </div>
                        <div class="col-lg-9">
                            <select class="form-select mb-3" name="employee_role" id="employee_role"
                                aria-label="Select Employment Type">
                                <option value="">Select Role</option>
                                <option value="Principal">Principal</option>
                                <option value="Management Staff">Management Staff</option>
                                <option value="Teacher">Teacher</option>
                                <option value="Accountant">Accountant</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    {{-- <div class="text-end">
                        <button type="submit" class="btn btn-primary">Add Leave</button>
                    </div> --}}

                </div>
            </div>

        </div>
        <div class="offcanvas-footer border-top p-3 text-center">
            <div class="row">
                <div class="col-6">
                    <a href="#" class="btn btn-light w-100" id="reset-layout">Reset</a>
                </div>
                <div class="col-6">
                    <button type="submit" class="btn btn-primary w-100" id="flights-search">Search</button>
                </div>
            </div>
        </div>
    </div>
</form>
