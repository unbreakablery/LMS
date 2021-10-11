$(document).ready(function() {
    var trackingsTable = $('#bookings-table').DataTable({
        responsive: true,
        orderCellsTop: true,
        fixedHeader: false,
        info: true,
        paging: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[ 0, "desc" ]],
        columnDefs: [
            { orderable: false, targets: 'no-sort' },
        ],
        language: {
            emptyTable: 'No trackings',
            info: 'Showing _START_ to _END_ of _TOTAL_ trackings',
            lengthMenu: 'Show _MENU_ trackings'
        }
    });
    trackingsTable.columns().iterator('column', function (ctx, idx) {
        $(trackingsTable.column(idx).header()).find('.bookings-table-header').append('<span class="sort-icon"/>');
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
                    $('#view-equipment-modal #equ_total_qnt').val(data.equipment.equ_total_qnt);
                    $('#view-equipment-modal #equ_current_qnt').val(data.equipment.equ_current_qnt);
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
});