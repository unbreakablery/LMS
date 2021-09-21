$(document).ready(function() {
    var equipmentsTable = $('#equipments-table').DataTable({
        responsive: true,
        orderCellsTop: true,
        fixedHeader: false,
        info: true,
        paging: true,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        order: [[ 0, "asc" ]],
        columnDefs: [
            { orderable: false, targets: 'no-sort' }
        ],
        language: {
            emptyTable: 'No Equipments',
            info: 'Showing _START_ to _END_ of _TOTAL_ equipments',
            lengthMenu: 'Show _MENU_ equipments'
        }
    });
    equipmentsTable.columns().iterator('column', function (ctx, idx) {
        $(equipmentsTable.column(idx).header()).find('.equipments-table-header').append('<span class="sort-icon"/>');
    });

    $('.date').datepicker({
        format: 'yyyy-mm-dd',
        todayBtn: "linked",
        todayHighlight: true,
        clearBtn: true,
        autoclose: true
    });

    // Upload image browser
    $(document).on("click", "#new-equipment-modal .browse", function() {
        var file = $(this).parents().find("#new-equipment-modal .file");
        file.trigger("click");
    });
    $('#new-equipment-modal input[type="file"]').change(function(e) {
        var fileName = e.target.files[0].name;
        $("#new-equipment-modal #file").val(fileName);
        
        var reader = new FileReader();
        reader.onload = function(e) {
            // get loaded data and render thumbnail.
            document.getElementById("preview").src = e.target.result;
        };
        // read the image file as a data URL.
        reader.readAsDataURL(this.files[0]);
    });

    $(document).on("click", "#edit-equipment-modal .browse", function() {
        var file = $(this).parents().find("#edit-equipment-modal .file");
        file.trigger("click");
    });
    $('#edit-equipment-modal input[type="file"]').change(function(e) {
        var fileName = e.target.files[0].name;
        $("#edit-equipment-modal #edit_equ_image_file").val(fileName);
        
        var reader = new FileReader();
        reader.onload = function(e) {
            // get loaded data and render thumbnail.
            document.getElementById("edit_equ_image_preview").src = e.target.result;
        };
        // read the image file as a data URL.
        reader.readAsDataURL(this.files[0]);
    });

    // New Equipment
    function clearNewEquipmentModal()
    {
        $('#new-equipment-modal #equ_code').val('');
        $('#new-equipment-modal #equ_name').val('');
        $('#new-equipment-modal #equ_desc').val('');
        $('#new-equipment-modal #equ_image').val('');
        $('#new-equipment-modal #file').val('');
        $('#new-equipment-modal #preview').attr('src', '/images/equipments/empty-image.png');
        $('#new-equipment-modal #cat_id').html('');
    }
    $('button.btn-add-equipment').click(function() {
        clearNewEquipmentModal();

        loader('show');

        $.ajax({
            url:        "/category/list",
            dataType:   "json",
            type:       "post",
            data:       {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },
            success: function( data ) {
                loader('hide');

                if (data.success == true) {
                    data.categories.forEach(element => {
                        $('#new-equipment-modal #cat_id').append('<option value="' + element.id + '">' + element.cat_name + '</option>');
                    });
                    
                    $('#new-equipment-modal').modal({
                        backdrop: 'static'
                    });
                } else {
                    showMessage('danger', data.message);
                }
            }
        });
    });

    $('button#btn-save-new-equipment').click(function() {
        if (empty($('#new-equipment-modal #equ_code').val())) {
            showMessage('danger', 'Error: please enter equipment code.');
            return false;
        }
        if (empty($('#new-equipment-modal #equ_name').val())) {
            showMessage('danger', 'Error: please enter equipment name.');
            return false;
        }

        var formData = new FormData($('form#new-equipment-form')[0]);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        loader('show');

        $.ajax({
            url:        "/equipment/manage/store",
            type:       "post",
            data:       formData,
            contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
            processData: false, // NEEDED, DON'T OMIT THIS
            success: function( data ) {
                loader('hide');

                if (data.success == true) {
                    $('#new-equipment-modal').modal('hide');
                    window.location.href = "/equipment/manage/list";
                } else {
                    showMessage('danger', data.message);
                }
            }
        });
    });

    // View Equipment
    $(document).on('click', 'button.btn-view-equipment', function() {
        var eId = $(this).closest('tr').attr('data-id');
        
        if (empty(eId)) {
            showMessage('danger', 'Error: Equipment ID is empty!');
            return false;
        }
        
        loader('show');

        $.ajax({
            url:        "/equipment/get",
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
                        $('#view-equipment-modal #equ_image').attr('src', '/images/equipments/' + data.equipment.equ_image);
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

    // Delete Equipment
    $(document).on('click', 'button.btn-delete-equipment', function() {
        var eId = $(this).closest('tr').attr('data-id');
        var eCode = $(this).closest('tr').find('td:nth-child(1)').text();
        var eName = $(this).closest('tr').find('td:nth-child(2)').text();
        
        $('#delete-equipment-modal #equ_id').val(eId);
        $('#delete-equipment-modal .equ-info').html('Code: ' + eCode + ', Name: ' + eName);

        $('#delete-equipment-modal').modal({
            backdrop: 'static'
        });
    });
    $('button#btn-delete-equipment-confirm').click(function() {
        var eId = $('#delete-equipment-modal #equ_id').val();
        
        if (empty(eId)) {
            showMessage('danger', 'Error: please choose other equipment.');
            return false;
        }

        loader('show');

        $.ajax({
            url:        "/equipment/manage/remove",
            dataType:   "json",
            type:       "delete",
            data:       {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: eId
                        },
            success: function( data ) {
                loader('hide');

                if (data.success == true) {
                    $('#delete-equipment-modal').modal('hide');
                    window.location.href = "/equipment/manage/list";
                } else {
                    showMessage('danger', data.message);
                }
            }
        });
    });

    // Edit Equipment
    function clearEditEquipmentModal()
    {
        $('#edit-equipment-modal #equ_code').val('');
        $('#edit-equipment-modal #equ_name').val('');
        $('#edit-equipment-modal #equ_desc').val('');
        $('#edit-equipment-modal #equ_image').val('');
        $('#edit-equipment-modal #edit_equ_image_file').val('');
        $('#edit-equipment-modal #edit_equ_image_preview').attr('src', '/images/equipments/empty-image.png');
        $('#edit-equipment-modal #equ_status').val('');
        $('#edit-equipment-modal #cat_id').html('');
    }
    $(document).on('click', 'button.btn-edit-equipment', function() {
        clearEditEquipmentModal();

        var eId = $(this).closest('tr').attr('data-id');
        
        if (empty(eId)) {
            showMessage('danger', 'Error: Equipment ID is empty!');
            return false;
        }
        
        loader('show');

        $.ajax({
            url:        "/equipment/get",
            dataType:   "json",
            type:       "post",
            data:       {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: eId
                        },
            success: function( data ) {
                loader('hide');

                if (data.success == true) {
                    $('#edit-equipment-modal #equ_id').val(data.equipment.id);
                    $('#edit-equipment-modal #equ_code').val(data.equipment.equ_code);
                    $('#edit-equipment-modal #equ_old_code').val(data.equipment.equ_code);
                    $('#edit-equipment-modal #equ_name').val(data.equipment.equ_name);
                    $('#edit-equipment-modal #equ_desc').val(!empty(data.equipment.equ_desc) ? data.equipment.equ_desc : '');

                    if (!empty(data.equipment.equ_image)) {
                        $('#edit-equipment-modal #edit_equ_image_file').val(data.equipment.equ_image);
                        $('#edit-equipment-modal #edit_equ_image_preview').attr('src', '/images/equipments/' + data.equipment.equ_image);
                    }
                    $('#edit-equipment-modal #equ_status').val(data.equipment.status_name);
                    // $('#edit-equipment-modal #cat_name').val(data.equipment.category.cat_name);
                    var cat_id = data.equipment.cat_id;
                    $.ajax({
                        url:        "/category/list",
                        dataType:   "json",
                        type:       "post",
                        data:       {
                                        _token: $('meta[name="csrf-token"]').attr('content'),
                                    },
                        success: function( data ) {
                            if (data.success == true) {
                                data.categories.forEach(element => {
                                    $('#edit-equipment-modal #cat_id').append('<option value="' + element.id + '">' + element.cat_name + '</option>');
                                });
                                $('#edit-equipment-modal #cat_id').val(cat_id);
                            } else {
                                showMessage('danger', data.message);
                            }
                        }
                    });
                                        
                    $('#edit-equipment-modal').modal({
                        backdrop: 'static'
                    });
                } else {
                    showMessage('danger', data.message);
                }
            }
        });
    });

    $('button#btn-update-equipment').click(function() {
        if (empty($('#edit-equipment-modal #equ_code').val())) {
            showMessage('danger', 'Error: please enter equipment code.');
            return false;
        }
        if (empty($('#edit-equipment-modal #equ_name').val())) {
            showMessage('danger', 'Error: please enter equipment name.');
            return false;
        }

        var formData = new FormData($('form#edit-equipment-form')[0]);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        loader('show');

        $.ajax({
            url:        "/equipment/manage/update",
            type:       "post",
            data:       formData,
            contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
            processData: false, // NEEDED, DON'T OMIT THIS
            success: function( data ) {
                loader('hide');

                if (data.success == true) {
                    $('#edit-equipment-modal').modal('hide');
                    window.location.href = "/equipment/manage/list";
                } else {
                    showMessage('danger', data.message);
                }
            }
        });
    });

    // Request booking
    var selectedEquipment = null;
    $(document).on('click', 'button.btn-request-booking', function() {
        var eId = $(this).closest('tr').attr('data-id');
        $('#request-booking-modal #equ_id').val(eId);
        selectedEquipment = $(this).closest('tr');

        $('#request-booking-modal #booking_start').datepicker("setDate", new Date());
        $('#request-booking-modal #booking_end').datepicker("setDate", new Date());

        $('#request-booking-modal').modal({
            backdrop: 'static'
        });
    });
    $('button#btn-request-booking-confirm').click(function() {
        var eId = $('#request-booking-modal #equ_id').val();
        var bookingStart = $('#request-booking-modal #booking_start').val();
        var bookingEnd = $('#request-booking-modal #booking_end').val();

        loader('show');

        $.ajax({
            url:        "/booking/request",
            dataType:   "json",
            type:       "post",
            data:       {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: eId,
                            booking_start: bookingStart,
                            booking_end: bookingEnd
                        },
            success: function( data ) {
                loader('hide');

                if (data.success == true) {
                    $(selectedEquipment).find('td:nth-child(4)').text(data.equipment.status_name);
                    if (data.equipment.equ_status == '0') {
                        $(selectedEquipment).find('td:nth-child(4)').removeClass('text-danger');
                        $(selectedEquipment).find('td:nth-child(4)').addClass('text-success');
                    } else if (data.equipment.equ_status == '1') {
                        $(selectedEquipment).find('td:nth-child(4)').removeClass('text-success');
                        $(selectedEquipment).find('td:nth-child(4)').addClass('text-danger');
                        $(selectedEquipment).find('button.btn-request-booking').remove();
                    } else {
                        $(selectedEquipment).find('td:nth-child(4)').removeClass('text-success');
                        $(selectedEquipment).find('td:nth-child(4)').removeClass('text-danger');
                        $(selectedEquipment).find('button.btn-request-booking').remove();
                    }

                    $('#request-booking-modal').modal('hide');
                } else {
                    showMessage('danger', data.message);
                }
            }
        });
    });
});