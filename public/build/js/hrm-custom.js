function initializeSelect2WithAjax(selector, ajaxUrl, placeholder) {
    //$(selector).select2('destroy');
    //console.log(selector, ajaxUrl, placeholder);
    $(selector).select2({
        ajax: {
            url: ajaxUrl,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    query: params.term, // search term
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            //id: item.id,
                            id: item.name,
                            text: item.name, // assuming 'name' is the field you want to display
                        };
                    }),
                };
            },
            cache: true,
        },
        placeholder: placeholder,
        minimumInputLength: 2, // minimum number of characters before the AJAX request
    });
}

function view_booking(id) {
    $.ajax({
        type: 'GET',
        url: 'view-booking/' + id, // in here you should put your query
        success: function (r) {
            //console.log(r);
            $('.modal-content').show().html(r);
            $('.modal.fullscreeexampleModal').modal('show');

        }
    });
}

function update_passenger_details(id) {
    $.ajax({
        type: 'GET',
        url: 'view-booking/' + id, // in here you should put your query
        success: function (r) {
            //console.log(r);
            $('.modal-content').show().html(r);
            $('.modal.fullscreeexampleModal').modal('show');

        }
    });
};

function add_installment_plan(id) {
    //$('.modal.fullscreeexampleModal').modal('toggle');
    $.ajax({
        type: 'GET',
        url: 'add-installment-plan/' + id, // in here you should put your query
        success: function (r) {
            //console.log(r);
            $('.extraLargeModal .modal-content').html(r);
            $('.modal.extraLargeModal').modal('show');

        }
    });
};

function edit_installment_plan(id) {
    //$('.modal.fullscreeexampleModal').modal('toggle');
    $.ajax({
        type: 'GET',
        url: 'edit-installment-plan/' + id, // in here you should put your query
        success: function (r) {
            //console.log(r);
            $('.extraLargeModal .modal-content').html(r);
            $('.modal.extraLargeModal').modal('show');

        }
    });
};

function add_payment(id) {
    //$('.modal.fullscreeexampleModal').modal('toggle');
    $.ajax({
        type: 'GET',
        url: 'add-payment/' + id, // in here you should put your query
        success: function (r) {

            $('.extraLargeModal .modal-content').html(r);
            $('.modal.extraLargeModal').modal('show');

        }
    });
};

function edit_payment(booking_id, payment_id) {
    //$('.modal.fullscreeexampleModal').modal('toggle');
    $.ajax({
        type: 'GET',
        url: 'edit-payment/' + booking_id + '/' + payment_id, // in here you should put your query
        success: function (r) {

            $('.extraLargeModal .modal-content').html(r);
            $('.modal.extraLargeModal').modal('show');

        }
    });
};

function add_other_charges(booking_id) {
    //$('.modal.fullscreeexampleModal').modal('toggle');
    $.ajax({
        type: 'GET',
        url: 'add-other-charges/' + booking_id, // in here you should put your query
        success: function (r) {

            $('.extraLargeModal .modal-content').html(r);
            $('.modal.extraLargeModal').modal('show');

        }
    });
};

function add_oc_payment(booking_id, other_charges_id) {
    //$('.modal.fullscreeexampleModal').modal('toggle');
    $.ajax({
        type: 'GET',
        url: 'add-oc-payment/' + booking_id + '/' + other_charges_id, // in here you should put your query
        success: function (r) {

            $('.extraLargeModal .modal-content').html(r);
            $('.modal.extraLargeModal').modal('show');

        }
    });
};

function edit_other_charges(booking_id,other_charges_id) {
    //$('.modal.fullscreeexampleModal').modal('toggle');
    $.ajax({
        type: 'GET',
        url: 'edit-other-charges/' + booking_id + '/' + other_charges_id, // in here you should put your query
        success: function (r) {

            $('.extraLargeModal .modal-content').html(r);
            $('.modal.extraLargeModal').modal('show');

        }
    });
};

function update_ticket_status(booking_id) {
    //$('.modal.fullscreeexampleModal').modal('toggle');
    $.ajax({
        type: 'GET',
        url: 'update-ticket-status/' + booking_id, // in here you should put your query
        success: function (r) {

            $('.extraLargeModal .modal-content').html(r);
            $('.modal.extraLargeModal').modal('show');

        }
    });
};

function update_booking_status(booking_id) {
    //$('.modal.fullscreeexampleModal').modal('toggle');
    $.ajax({
        type: 'GET',
        url: 'update-booking-status/' + booking_id, // in here you should put your query
        success: function (r) {

            $('.extraLargeModal .modal-content').html(r);
            $('.modal.extraLargeModal').modal('show');

        }
    });
};

function add_refund(booking_id) {
    //$('.modal.fullscreeexampleModal').modal('toggle');
    $.ajax({
        type: 'GET',
        url: 'add-refund/' + booking_id, // in here you should put your query
        success: function (r) {
            $('.extraLargeModal .modal-content').html(r);
            $('.modal.extraLargeModal').modal('show');

        }
    });
};

function edit_refund(booking_id, refund_id) {
    //$('.modal.fullscreeexampleModal').modal('toggle');
    $.ajax({
        type: 'GET',
        url: 'edit-refund/' + booking_id + '/' + refund_id, // in here you should put your query
        success: function (r) {
            $('.extraLargeModal .modal-content').html(r);
            $('.modal.extraLargeModal').modal('show');

        }
    });
};
