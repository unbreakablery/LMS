$(document).ready(function() {
    $('li.sub-category').click(function() {
        $('li.sub-category').removeClass('active');
        $(this).addClass('active');

        var catName = $(this).attr('data-name');
        var catId = $(this).attr('data-id');
        var pCatId = $(this).attr('data-p-cat');

        $('#cat_id').val(catId);
        $('#cat_name').val(catName);
        $('#p_cat').val(pCatId);
    });
    $('button#btn-new').click(function() {
        $('#cat_id').val('');
        $('#cat_name').val('');
        $('#p_cat').val(1);
        $('#cat_name').focus();

        $('li.sub-category').removeClass('active');
    });
    $('button#btn-delete').click(function() {
        var catId = $('#cat_id').val();
        if (catId == undefined || catId == 0 || catId == 1 || catId == null || catId == '') {
            showMessage('danger', 'Error, You can\'t delete this category!');
            return false;
        }

        window.location.href = "/category/delete/" + catId;
    });
});