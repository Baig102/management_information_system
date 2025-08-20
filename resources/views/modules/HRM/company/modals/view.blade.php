<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Employee Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    <div class="row">
        <div class="col-lg-6 col-xl-6 col-md-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Details of Employee</h4>
                    <div class="flex-shrink-0">
                        {{-- <button type="button" class="btn btn-soft-primary btn-sm"> Export Report </button> --}}
                        <button type="button" class="btn btn-soft-primary btn-sm">
                            @if ($data->is_active == 1)
                                Active
                            @else
                                In-Active
                            @endif
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    {{-- <pre>
                        {{ print_r($data) }}
                        {{ print_r(session()->all()) }}
                    </pre> --}}

                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="mb-0">{{ $data->name }}</h4>
                            <h6 class="text-muted text-uppercase fw-semibold text-truncate fs-12 mb-3">Father Name: {{ $data->guardian_name }}</h6>

                        </div><!-- end col -->
                        <div class="col-6">
                            <div class="text-center">
                                {{-- <img src="{{ asset('assets') }}/images/illustrator-1.png" class="img-fluid" alt=""> --}}
                                <img src="{{ asset('/') }}uploads/Employee/{{ $data->picture }}" class="img-thumbnail rounded-circle avatar-lg" alt="{{ $data->code }}">
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->
                    <div class="mt-3 pt-2">
                        <div class="progress progress-lg rounded-pill">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 25%"
                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            <div class="progress-bar bg-info" role="progressbar" style="width: 18%" aria-valuenow="18"
                                aria-valuemin="0" aria-valuemax="100"></div>
                            <div class="progress-bar bg-success" role="progressbar" style="width: 22%"
                                aria-valuenow="22" aria-valuemin="0" aria-valuemax="100"></div>
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 16%"
                                aria-valuenow="16" aria-valuemin="0" aria-valuemax="100"></div>
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 19%" aria-valuenow="19"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div><!-- end -->

                    <div class="mt-3 pt-2">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-secondary"><i
                                    class="ri-bill-line align-middle me-2"></i> Employee ID: {{ $data->code }} </li>
                            <li class="list-group-item list-group-item-secondary"><i
                                    class="ri-file-copy-2-line align-middle me-2"></i>EMPLOYEE TYPE:
                                {{ $data->employment_type }}</li>
                            <li class="list-group-item list-group-item-secondary"><i
                                    class="ri-file-copy-2-line align-middle me-2"></i>EMPLOYEE DESIGNATION:
                                {{ $data->designation }}</li>
                            <li class="list-group-item list-group-item-secondary"><i
                                    class="ri-file-copy-2-line align-middle me-2"></i>SALARY: {{ $data->basic_salary }}</li>
                            <li class="list-group-item list-group-item-secondary"><i
                                    class="ri-question-answer-line align-middle me-2"></i>USERNAME: {{ $data->official_email }}
                            </li>
                            <li class="list-group-item list-group-item-secondary"><i
                                    class="ri-secure-payment-line align-middle me-2"></i> PASSWORD: *********</li>

                            <li class="list-group-item"><i class="ri-bill-line align-middle me-2"></i>HOME ADDRESS:
                                {{ $data->address }}</li>
                            <li class="list-group-item"><i class="ri-file-copy-2-line align-middle me-2"></i>PERSONAL PHONE:
                                {{ $data->personal_phone }}</li>
                            <li class="list-group-item"><i class="ri-question-answer-line align-middle me-2"></i>PERSONAL EMAIL:
                                {{ $data->personal_email }}</li>
                            <li class="list-group-item"><i class="ri-file-copy-2-line align-middle me-2"></i>OFFICIAL PHONE:
                                {{ $data->official_phone }}</li>
                            <li class="list-group-item"><i class="ri-question-answer-line align-middle me-2"></i>OFFICIAL EMAIL:
                                {{ $data->official_email }}</li>


                            <li class="list-group-item list-group-item-secondary"><i
                                    class="ri-bill-line align-middle me-2"></i>EEMPLOYEE CNIC: {{ $data->cnic }}</li>
                            <li class="list-group-item list-group-item-secondary"><i
                                    class="ri-file-copy-2-line align-middle me-2"></i>EDUCATION:
                                {{ $data->education }}</li>

                            <li class="list-group-item"><i class="ri-file-copy-2-line align-middle me-2"></i>GENDER:
                                {{ $data->gender }}</li>
                            <li class="list-group-item"><i class="ri-file-copy-2-line align-middle me-2"></i>RELIGION:
                                {{ $data->religion }}</li>
                            <li class="list-group-item"><i class="ri-file-copy-2-line align-middle me-2"></i>DATE OF
                                BIRTH: {{ $data->dob }}</li>
                            <li class="list-group-item"><i class="ri-file-copy-2-line align-middle me-2"></i>JOINING
                                DATE: {{ $data->joining_date }}</li>
                            <li class="list-group-item"><i class="ri-file-copy-2-line align-middle me-2"></i>RESIGNING
                                DATE: {{ $data->resigning_date }}</li>
                            <li class="list-group-item"><i class="ri-file-copy-2-line align-middle me-2"></i>BLOOD
                                GROUP: {{ $data->blood_group }}</li>
                            <li class="list-group-item"><i class="ri-file-copy-2-line align-middle me-2"></i>EXPERIENCE:
                                {{ $data->experience }}</li>

                        </ul>
                    </div><!-- end -->

                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-lg-6 col-xl-6 col-md-6">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Attendance Report</h4>
                </div><!-- end card header -->
                <div class="card-body">

                </div><!-- end cardbody -->
            </div><!-- end card -->
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Salary Record</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table align-middle table-borderless table-centered table-nowrap mb-0">
                            <thead class="text-muted table-light">
                                <tr>
                                    <th scope="col">Sr#</th>
                                    <th scope="col">Salary Month</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Bonus</th>
                                    <th scope="col">Detuction</th>
                                    <th scope="col">Net Salary</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>2023-04 2023</td>
                                    <td>2023-04-10</td>
                                    <td>8</td>
                                    <td>0</td>
                                    <td>20 PKR</td>
                                </tr><!-- end -->
                            </tbody><!-- end tbody -->
                        </table><!-- end table -->
                    </div><!-- end -->
                </div><!-- end cardbody -->
            </div><!-- end card -->

            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Employee Documents</h4>
                </div><!-- end card header -->
                <div class="card-body">

                    <div class="row g-3">

                    </div>
                </div><!-- end cardbody -->
            </div><!-- end card -->

        </div><!-- end col -->
    </div>
</div>
<div class="modal-footer">
    <div class="hstack gap-2 justify-content-end">
        {{-- <button type="button"
            class="btn btn-sm btn-outline-success btn-icon waves-effect waves-light"
            onclick="create_user({{ $data->id }})"><i class="ri-shield-user-line fs-5"></i></button> --}}
        {{-- <button type="button" class="btn btn-sm btn-outline-primary btn-icon waves-effect waves-light"
            onclick="view_employee({{ $data->id }})"><i
                class="ri-search-eye-line fs-5"></i></button> --}}
        <a href=""
            class="btn btn-sm btn-outline-info btn-icon waves-effect waves-light" target="__blank"><i
                class="ri-printer-cloud-fill fs-5"></i></a>
        <a href="" title="Print Employee Card"
            class="btn btn-sm btn-outline-dark btn-icon waves-effect waves-light" target="__blank"><i
                class="ri-bank-card-fill fs-5"></i></a>
        <button type="button"
            class="btn btn-sm btn-outline-warning btn-icon waves-effect waves-light"><i
                class="ri-edit-circle-fill fs-5"></i></button>
        <button type="button"
            class="btn btn-sm btn-outline-danger btn-icon waves-effect waves-light"><i
                class=" ri-delete-bin-2-fill fs-5"></i></button>
    </div>
    <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i
            class="ri-close-line me-1 align-middle"></i> Close</a>
    {{-- <button type="button" class="btn btn-primary ">Save changes</button> --}}
</div>
