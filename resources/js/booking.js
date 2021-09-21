$(document).ready(function() {
    var bookingsTable = $('#bookings-table').DataTable({
        responsive: true,
        orderCellsTop: true,
        fixedHeader: false,
        info: true,
        paging: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[ 8, "desc" ]],
        columnDefs: [
            { orderable: false, targets: 'no-sort' },
            // {
            //     "targets": [ 8 ],
            //     "visible": false,
            //     "searchable": false
            // },
        ],
        language: {
            emptyTable: 'No bookings',
            info: 'Showing _START_ to _END_ of _TOTAL_ bookings',
            lengthMenu: 'Show _MENU_ bookings'
        }
    });
    bookingsTable.columns().iterator('column', function (ctx, idx) {
        $(bookingsTable.column(idx).header()).find('.bookings-table-header').append('<span class="sort-icon"/>');
    });

    $('.date').datepicker({
        format: 'yyyy-mm-dd',
        todayBtn: "linked",
        todayHighlight: true,
        clearBtn: true,
        autoclose: true
    });

    // View Equipment
    $(document).on('click', 'a.equipment-link', function() {
        var eId = $(this).attr('data-id');
        
        if (empty(eId)) {
            showMessage('danger', 'Error: Equipment ID is empty!');
            return false;
        }
        
        loader('show');

        $.ajax({
            url:        host + "/equipment/get",
            dataType:   "json",
            type:       "post",
            data:       {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: eId
                        },
            success: function( data ) {
                loader('hide');

                if (data.success == true) {
                    $('#view-equipment-modal #equ_code').val(data.equipment.equ_code);
                    $('#view-equipment-modal #equ_name').val(data.equipment.equ_name);
                    $('#view-equipment-modal #equ_desc').val(!empty(data.equipment.equ_desc) ? data.equipment.equ_desc : '');

                    if (empty(data.equipment.equ_image)) {
                        $('#view-equipment-modal #equ_image').addClass('d-none');
                    } else {
                        $('#view-equipment-modal #equ_image').removeClass('d-none');
                        $('#view-equipment-modal #equ_image').attr('src', host + '/images/equipments/' + data.equipment.equ_image);
                    }
                    $('#view-equipment-modal #equ_status').val(data.equipment.status_name);
                    $('#view-equipment-modal #cat_name').val(data.equipment.category.cat_name);
                    $('#view-equipment-modal #created_at').val(data.equipment.created_at);
                    $('#view-equipment-modal #updated_at').val(data.equipment.updated_at);
                    
                    $('#view-equipment-modal').modal({
                        backdrop: 'static'
                    });
                } else {
                    showMessage('danger', data.message);
                }
            }
        });
    });

    // Delete Booking
    $(document).on('click', 'button.btn-delete-booking', function() {
        var bId = $(this).closest('tr').attr('data-id');
        
        $('#delete-booking-modal #booking_id').val(bId);
        $('#delete-booking-modal .booking-info').html('Booking ID: ' + bId);

        $('#delete-booking-modal').modal({
            backdrop: 'static'
        });
    });
    $('button#btn-delete-booking-confirm').click(function() {
        var bId = $('#delete-booking-modal #booking_id').val();
        
        if (empty(bId)) {
            showMessage('danger', 'Error: please choose other booking.');
            return false;
        }

        loader('show');

        $.ajax({
            url:        host + "/booking/manage/remove",
            dataType:   "json",
            type:       "delete",
            data:       {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: bId
                        },
            success: function( data ) {
                loader('hide');

                if (data.success == true) {
                    $('#delete-booking-modal').modal('hide');
                    window.location.href = host + "/booking/manage/list";
                } else {
                    showMessage('danger', data.message);
                }
            }
        });
    });

    // Edit Booking
    var selectedBooking = null;
    function clearEditBookingModal()
    {
        $('#edit-booking-modal #book_id').val('');
        $('#edit-booking-modal #equ_name').val('');
        $('#edit-booking-modal #booking_date').val('');
        $('#edit-booking-modal #user_name').val('');
        $('#edit-booking-modal #booking_start').val('');
        $('#edit-booking-modal #booking_end').val('');
        $('#edit-booking-modal #status').val('0');
    }
    $(document).on('click', 'button.btn-change-booking-status', function() {
        selectedBooking = $(this).closest('tr');
        clearEditBookingModal();

        var bId = $(this).closest('tr').attr('data-id');
        
        if (empty(bId)) {
            showMessage('danger', 'Error: Booking ID is empty!');
            return false;
        }
        
        loader('show');

        $.ajax({
            url:        host + "/booking/get",
            dataType:   "json",
            type:       "post",
            data:       {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: bId
                        },
            success: function( data ) {
                loader('hide');

                if (data.success == true) {
                    $('#edit-booking-modal #booking_id').val(data.booking.id);
                    $('#edit-booking-modal #equ_name').val(data.booking.equipment.equ_name);
                    $('#edit-booking-modal #booking_date').val(data.booking.booking_date);
                    $('#edit-booking-modal #user_name').val(data.booking.user.first_name + ' ' + data.booking.user.last_name);
                    $('#edit-booking-modal #booking_start').val(data.booking.booking_start);
                    $('#edit-booking-modal #booking_end').val(data.booking.booking_end);
                    $('#edit-booking-modal #status').val(data.booking.status);

                    $('#edit-booking-modal').modal({
                        backdrop: 'static'
                    });
                } else {
                    showMessage('danger', data.message);
                }
            }
        });
    });

    $('button#btn-update-booking').click(function() {
        var bId = $('#edit-booking-modal #booking_id').val();
        var bStatus = $('#edit-booking-modal #status').val();
        if (empty(bId)) {
            showMessage('danger', 'Error: Empty Booking ID.');
            return false;
        }
        
        loader('show');

        $.ajax({
            url:        host + "/booking/manage/update-status",
            dataType:   "json",
            type:       "post",
            data:       {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: bId,
                            status: bStatus
                        },
            success: function( data ) {
                loader('hide');

                if (data.success == true) {
                    $(selectedBooking).find('td:nth-child(6)').text(data.booking.status_name);
                    var message = "Booking(ID: B-" + data.booking.id + ", Equipment: " + data.booking.equipment.equ_name;
                    message += ") was changed status - ";
                    if (data.booking.status == '0') {
                        $(selectedBooking).find('td:nth-child(6)').removeClass('text-success');
                        $(selectedBooking).find('td:nth-child(6)').removeClass('text-danger');
                        $(selectedBooking).find('td:nth-child(6)').removeClass('text-secondary');
                        $(selectedBooking).find('td:nth-child(6)').addClass('text-primary');
                        message += "In Booking";
                    } else if (data.booking.status == '1') {
                        $(selectedBooking).find('td:nth-child(6)').removeClass('text-primary');
                        $(selectedBooking).find('td:nth-child(6)').removeClass('text-danger');
                        $(selectedBooking).find('td:nth-child(6)').removeClass('text-secondary');
                        $(selectedBooking).find('td:nth-child(6)').addClass('text-success');
                        message += "Approved/Booked";
                    } else if (data.booking.status == '2') {
                        $(selectedBooking).find('td:nth-child(6)').removeClass('text-primary');
                        $(selectedBooking).find('td:nth-child(6)').removeClass('text-success');
                        $(selectedBooking).find('td:nth-child(6)').removeClass('text-secondary');
                        $(selectedBooking).find('td:nth-child(6)').addClass('text-danger');
                        message += "Rejected";
                    } else {
                        $(selectedBooking).find('td:nth-child(6)').removeClass('text-primary');
                        $(selectedBooking).find('td:nth-child(6)').removeClass('text-success');
                        $(selectedBooking).find('td:nth-child(6)').removeClass('text-danger');
                        $(selectedBooking).find('td:nth-child(6)').addClass('text-secondary');
                        message += "Cancelled";
                    }

                    $('#edit-booking-modal').modal('hide');
                    showMessage('success', message);
                } else {
                    showMessage('danger', data.message);
                }
            }
        });
    });

    // Cancel Booking
    $(document).on('click', 'button.btn-cancel-booking', function() {
        var bId = $(this).closest('tr').attr('data-id');
        var eName = $(this).closest('tr').find('td:nth-child(2)').text();
        
        $('#cancel-booking-modal #booking_id').val(bId);
        $('#cancel-booking-modal .booking-info').html('Booking ID: ' + bId + ', Equipment Name: ' + eName);

        $('#cancel-booking-modal').modal({
            backdrop: 'static'
        });
    });
    $('button#btn-cancel-booking-confirm').click(function() {
        var bId = $('#cancel-booking-modal #booking_id').val();
        
        if (empty(bId)) {
            showMessage('danger', 'Error: please choose other booking.');
            return false;
        }

        loader('show');

        $.ajax({
            url:        host + "/booking/cancel",
            dataType:   "json",
            type:       "post",
            data:       {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: bId
                        },
            success: function( data ) {
                loader('hide');

                if (data.success == true) {
                    $('#cancel-booking-modal').modal('hide');
                    window.location.href = host + "/booking";
                } else {
                    showMessage('danger', data.message);
                }
            }
        });
    });

    // Return Equipment
    $(document).on('click', 'button.btn-return-equipment', function() {
        var bId = $(this).closest('tr').attr('data-id');
        var eName = $(this).closest('tr').find('td:nth-child(2)').text();
        
        $('#return-equipment-modal #booking_id').val(bId);
        $('#return-equipment-modal .equ-info').html('Equipment Name: ' + eName);

        $('#return-equipment-modal').modal({
            backdrop: 'static'
        });
    });
    $('button#btn-return-equipment-confirm').click(function() {
        var bId = $('#return-equipment-modal #booking_id').val();
        
        if (empty(bId)) {
            showMessage('danger', 'Error: please choose other booking.');
            return false;
        }

        loader('show');

        $.ajax({
            url:        host + "/booking/return",
            dataType:   "json",
            type:       "post",
            data:       {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: bId
                        },
            success: function( data ) {
                loader('hide');

                if (data.success == true) {
                    $('#return-equipment-modal').modal('hide');
                    window.location.href = host + "/booking";
                } else {
                    showMessage('danger', data.message);
                }
            }
        });
    });

    // Change Booking Period
    $(document).on('click', 'button.btn-change-booking', function() {
        selectedBooking = $(this).closest('tr');
        clearEditBookingModal();

        var bId = $(this).closest('tr').attr('data-id');
        
        if (empty(bId)) {
            showMessage('danger', 'Error: Booking ID is empty!');
            return false;
        }
        
        loader('show');

        $.ajax({
            url:        host + "/booking/get",
            dataType:   "json",
            type:       "post",
            data:       {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: bId
                        },
            success: function( data ) {
                loader('hide');

                if (data.success == true) {
                    $('#edit-booking-modal #booking_id').val(data.booking.id);
                    $('#edit-booking-modal #equ_name').val(data.booking.equipment.equ_name);
                    $('#edit-booking-modal #booking_date').val(data.booking.booking_date);
                    $('#edit-booking-modal #user_name').val(data.booking.user.first_name + ' ' + data.booking.user.last_name);
                    $('#edit-booking-modal #booking_start').val(data.booking.booking_start);
                    $('#edit-booking-modal #booking_end').val(data.booking.booking_end);
                    $('#edit-booking-modal #status').val(data.booking.status_name);

                    $('#edit-booking-modal').modal({
                        backdrop: 'static'
                    });
                } else {
                    showMessage('danger', data.message);
                }
            }
        });
    });

    $('button#btn-change-booking-period').click(function() {
        var bId = $('#edit-booking-modal #booking_id').val();
        var bStart = $('#edit-booking-modal #booking_start').val();
        var bEnd = $('#edit-booking-modal #booking_end').val();
        if (empty(bId)) {
            showMessage('danger', 'Error: Empty Booking ID.');
            return false;
        }
        if (empty(bStart)) {
            showMessage('danger', 'Error: Enter booking start date.');
            $('#edit-booking-modal #booking_start').focus();
            return false;
        }
        if (empty(bEnd)) {
            showMessage('danger', 'Error: Enter booking end date.');
            $('#edit-booking-modal #booking_end').focus();
            return false;
        }
        if (bStart > bEnd) {
            showMessage('danger', 'Error: Start date should be less than end date.');
            $('#edit-booking-modal #booking_start').focus();
            return false;
        }
        
        loader('show');

        $.ajax({
            url:        host + "/booking/update-period",
            dataType:   "json",
            type:       "post",
            data:       {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: bId,
                            booking_start: bStart,
                            booking_end: bEnd
                        },
            success: function( data ) {
                loader('hide');

                if (data.success == true) {
                    window.location.href = host + "/booking";
                } else {
                    showMessage('danger', data.message);
                }
            }
        });
    });
});