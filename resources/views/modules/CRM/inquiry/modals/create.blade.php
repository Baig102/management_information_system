<form id="addInquiryForm">
    @csrf
    <div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
        <h5 class="modal-title" id="fullscreeexampleModalLabel">Add New Inquiry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">

        <div class="row mb-3">
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Inquiry Date & Time</label>
                <div class="input-group">
                    <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                    <input type="text" name="inquiry_date_time" value="" placeholder="DD-MM-YYYY" class="form-control flatpickr-date-time" data-provider="flatpickr" id="inquiry_date_time" autocomplete="off" required>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Full Name</label>
                <div class="input-group">
                    <span class="input-group-text px-2 py-1"><i class="ri-shield-user-line fs-5"></i></span>
                    <input type="text" class="form-control full_name" id="full_name" name="full_name" value="" placeholder="Enter full name" required autocomplete="off">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Email</label>
                <div class="input-group">
                    <span class="input-group-text px-2 py-1"><i class="ri-mail-line fs-5"></i></span>
                    <input type="email" class="form-control email" id="email" name="email" value="" placeholder="Enter email" step="0.01" required autocomplete="off">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Phone #</label>
                <div class="input-group">
                    <span class="input-group-text px-2 py-1"><i class="ri-phone-line fs-5"></i></span>
                    <input type="text" class="form-control phone_number" id="phone_number" name="phone_number" value="" placeholder="Enter phone number" required autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Departure From</label>
                <div class="input-group">
                    <select class="departure_from" id="departure_from" name="departure_from" required></select>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Destination</label>
                <div class="input-group">
                    <select class="destination" id="destination" name="destination" required></select>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Departure Date</label>
                <div class="input-group">
                    <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                    <input type="text" name="departure_date" value="" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="departure_date" autocomplete="off" required>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Return Date</label>
                <div class="input-group">
                    <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                    <input type="text" name="return_date" value="" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="return_date" autocomplete="off" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Best Time To Call</label>
                <div class="input-group">
                    <span class="input-group-text px-2 py-1"><i class="ri-timer-flash-line fs-5"></i></span>
                    <input type="text" class="form-control best_time_to_call" id="best_time_to_call" name="best_time_to_call" value="" placeholder="Best Time To Call" autocomplete="off">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Source</label>
                <select name="source" class="form-select mb-3" aria-label="Source">
                    <option value="Facebook">Facebook</option>
                    <option value="Website">Website</option>
                    <option value="Via Phone Call">Via Phone Call</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Company</label>
                <select name="company" class="form-select mb-3" aria-label="Company">
                    <option value="{{ $companies->id.'_'.$companies->name }}">{{ $companies->name }}</option>
                </select>
            </div>
        </div>
        {{-- <div class="row mb-3">
            <div class="col-lg-12">
                <label>Payment Comments</label>
                <textarea name="comments" id="comments" class="ckeditor-classic"></textarea>
            </div>
        </div> --}}


    </div>
    <div class="modal-footer">

        <button type="submit" class="btn btn-primary btn-sm" id="saveInstallmentPlan"><i class="ri-save-3-line me-1 align-middle"></i>Save</button>
        <a href="javascript:void(0);" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
    </div>
</form>


<script>
    $(".flatpickr-date-time").flatpickr({
        altInput: true,
        //altFormat: "F j, Y",
        //dateFormat: "Y-m-d",
        dateFormat: "Y-m-d H:i:s",
        //minDate: "today",
        //defaultDate: "today",
        enableTime: true,
        time_24hr: true,
    });
    $(".flatpickr-date").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        //minDate: "today",
        defaultDate: "today",
    });

    // Initialize Select2 when the modal is shown
    $('#extraLargeModal').on('shown.bs.modal', function () {
        initializeSelect2WithAjax('#departure_from', '{{ route('crm.get-airports') }}', 'Search for departure from');
        initializeSelect2WithAjax('#destination', '{{ route('crm.get-airports') }}', 'Search for destination');
    });

    $(document).ready(function() {

        ClassicEditor.create(document.querySelector('#comments')).catch(error => {
            //console.error(error);
        })
        // initializeSelect2WithAjax('#departure_from', '{{ route('get-airports') }}', 'Search for airports');
        // Validate form before submitting
        $('#addInquiryForm').submit(function(event) {
            //console.log($(this).serialize());
            // Perform custom validation here

            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: "{{ route('inquiry.save-inquiry') }}",
                data: $(this).serialize(),
                success: function(response) {
                    //console.log(response);

                    $('.modal.extraLargeModal').modal('toggle');

                    Swal.fire({
                        title: response.title,
                        icon: response.icon,
                        text: response.message,
                    });
                },
                error: function(xhr, status, error) {
                    //console.log(xhr, status, error);
                    // Show error message
                    //alert(xhr.responseJSON.message);

                    Swal.fire({
                        //title: response.title,
                        //icon: response.icon,
                        text: xhr.responseJSON.message,
                    });
                }
            });
            return;
        });
    });
</script>
