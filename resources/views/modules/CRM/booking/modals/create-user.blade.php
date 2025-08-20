<form method="POST" action="{{ route('hrm.employee-user-register') }}">

    <div class="modal-header border-bottom border-2 py-2 bg-soft-info">
        <h5 class="modal-title" id="fullscreeexampleModalLabel">Create User | Type Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">

        @csrf

        <div class="row mb-3 d-none">

            <label for="employee" class="col-md-4 col-form-label text-md-end">{{ __('Employee Id') }}</label>

            <div class="col-md-6">
                <input id="employee" type="text" class="form-control @error('employee_id') is-invalid @enderror" name="employee_id" value="{{ $emp_data->id }}" required autocomplete="off" readonly>
            </div>
        </div>

        <div class="row mb-3">

            <!-- Input with Icon Right -->
            <div>
                <label for="Role" class="form-label">Role</label>
                <div class="form-icon right">
                    <select class="form-select mb-3" aria-label=".form-select-lg example" data-choice="active">
                        <option value="1">Super Admin</option>
                        <option value="2">Admin</option>
                        <option value="3">Manager</option>
                        <option value="4">A.M / T.L</option>
                        <option value="5" selected>User / Agent</option>
                    </select>
                    <i class="ri-mail-unread-line"></i>
                </div>
            </div>
            <div>
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <div class="form-icon right">
                    <input id="password" type="password" class="form-control @error('password') is-invalid  @enderror" name="password" required autofocus autocomplete="new-password">
                    <i class="ri-mail-unread-line"></i>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div>
                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                <div class="form-icon right">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                    autocomplete="new-password">
                    <i class="ri-mail-unread-line"></i>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        {{-- <div class="row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary"> {{ __('Register') }} </button>
            </div>
        </div> --}}
        <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
        <button type="submit" class="btn btn-primary ">Register</button>
    </div>
</form>
<script>
    $(document).ready(function() {
    $('.js-example-basic-single').select2();
    })
</script>
