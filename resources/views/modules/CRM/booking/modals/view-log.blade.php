
<div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Booking Logs | Booking Number: {{ $booking->booking_prefix . $booking->booking_number }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">

    {{-- <pre>
        {{ print_r($booking)}}
    </pre> --}}

    <div class="profile-timeline">
        <div class="accordion accordion-flush" id="accordionFlushExample">
            @foreach ($booking->logs as $logKey => $log)
            <div class="accordion-item border-0">
                <div class="accordion-header" id="heading{{$logKey+1}}">
                    <a class="accordion-button p-2 shadow-none collapsed" data-bs-toggle="collapse" href="#collapse{{$logKey+1}}" aria-expanded="false" aria-controls="collapse{{$logKey+1}}">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 avatar-xs">
                                <div class="avatar-title bg-success rounded-circle">
                                    <i class="ri-shopping-bag-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fs-15 mb-0 fw-semibold">{{$log->action}} - <span class="fw-normal">{{$log->created_at}}{{-- {{ date('M-d-Y', strtotime($log->created_at)) }} --}}</span></h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div id="collapse{{$logKey+1}}" class="accordion-collapse collapse" aria-labelledby="heading{{$logKey+1}}" data-bs-parent="#accordionExample">
                    <div class="accordion-body ms-2 ps-5 pt-0">
                        <h6 class="mb-1">{!! $log->description !!}</h6>
                        <p class="text-muted mb-0">{{userDetails($log->created_by)->name}}</p>
                    </div>
                </div>
            </div>
            @endforeach


        </div>
    </div>

    {{-- <div class="profile-timeline">
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item border-0">
                <div class="accordion-header" id="headingOne">
                    <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 avatar-xs">
                                <div class="avatar-title bg-success rounded-circle">
                                    <i class="ri-shopping-bag-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fs-15 mb-0 fw-semibold">Order Placed - <span class="fw-normal">Wed, 15 Dec 2021</span></h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body ms-2 ps-5 pt-0">
                        <h6 class="mb-1">An order has been placed.</h6>
                        <p class="text-muted">Wed, 15 Dec 2021 - 05:34PM</p>

                        <h6 class="mb-1">Seller has processed your order.</h6>
                        <p class="text-muted mb-0">Thu, 16 Dec 2021 - 5:48AM</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item border-0">
                <div class="accordion-header" id="headingTwo">
                    <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 avatar-xs">
                                <div class="avatar-title bg-success rounded-circle">
                                    <i class="mdi mdi-gift-outline"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fs-15 mb-1 fw-semibold">Packed - <span class="fw-normal">Thu, 16 Dec 2021</span></h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body ms-2 ps-5 pt-0">
                        <h6 class="mb-1">Your Item has been picked up by courier partner</h6>
                        <p class="text-muted mb-0">Fri, 17 Dec 2021 - 9:45AM</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item border-0">
                <div class="accordion-header" id="headingThree">
                    <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 avatar-xs">
                                <div class="avatar-title bg-success rounded-circle">
                                    <i class="ri-truck-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fs-15 mb-1 fw-semibold">Shipping - <span class="fw-normal">Thu, 16 Dec 2021</span></h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                    <div class="accordion-body ms-2 ps-5 pt-0">
                        <h6 class="fs-14">RQK Logistics - MFDS1400457854</h6>
                        <h6 class="mb-1">Your item has been shipped.</h6>
                        <p class="text-muted mb-0">Sat, 18 Dec 2021 - 4.54PM</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item border-0">
                <div class="accordion-header" id="headingFour">
                    <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseFour" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 avatar-xs">
                                <div class="avatar-title bg-light text-success rounded-circle">
                                    <i class="ri-takeaway-fill"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fs-14 mb-0 fw-semibold">Out For Delivery</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="accordion-item border-0">
                <div class="accordion-header" id="headingFive">
                    <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseFile" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 avatar-xs">
                                <div class="avatar-title bg-light text-success rounded-circle">
                                    <i class="mdi mdi-package-variant"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fs-14 mb-0 fw-semibold">Delivered</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div> --}}

</div>
<div class="modal-footer">
    <a href="javascript:void(0);" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
</div>
