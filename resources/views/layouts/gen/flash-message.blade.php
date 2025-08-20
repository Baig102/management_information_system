
@if (Session::get('success') || Session::get('error') || Session::get('warning') || Session::get('info') || $errors->any())

<div class="row mb-4">

    @if ($message = Session::get('success'))
        <!-- Success Alert -->
        <div class="alert alert-success alert-dismissible alert-label-icon rounded-label fade show" role="alert">
            <i class="ri-notification-off-line label-icon"></i><strong>Success</strong> - {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <!-- Danger Alert -->
        <div class="alert alert-danger alert-dismissible alert-label-icon rounded-label fade show" role="alert">
            <i class="ri-error-warning-line label-icon"></i><strong>Error</strong> - {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('warning'))
        <!-- Warning Alert -->
        <div class="alert alert-warning alert-dismissible alert-label-icon rounded-label fade show" role="alert">
            <i class="ri-alert-line label-icon"></i><strong>Warning</strong> - {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('info'))
        <!-- Info Alert -->
        <div class="alert alert-info alert-dismissible alert-label-icon rounded-label fade show" role="alert">
            <i class="ri-airplay-line label-icon"></i><strong>Info</strong> - {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <!-- Danger Alert -->
        <div class="alert alert-danger alert-dismissible alert-label-icon rounded-label fade show" role="alert">
            <i class="ri-error-warning-line label-icon"></i><strong>Errors! </strong> - Please check the form below for
            errors
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <!-- Danger Alert -->
            <div class="alert alert-danger alert-border-left alert-dismissible fade show p-1" role="alert">
                <i class="ri-error-warning-line me-3 align-middle"></i> <strong>Danger</strong> -
                {{ $error }}
                <button type="button" class="btn-close p-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endforeach
    @endif
</div>
@endif
