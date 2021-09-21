/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************!*\
  !*** ./resources/js/setting.js ***!
  \*********************************/
$(document).ready(function () {
  $('a.a-btn-remove-account').click(function () {
    $('#delete-account-modal').modal({
      backdrop: 'static'
    });
  });
  $('button#btn-delete-account').click(function () {
    window.location.href = host + '/settings/remove/current-user';
  });
  $('button#btn-change-password').click(function () {
    $('input#new_password').val('');
    $('input#confirm_password').val('');
    $('#change-password-modal').modal({
      backdrop: 'static'
    });
  });
  $('button#btn-update-password').click(function () {
    var newPassword = $('input#new_password').val();
    var confirmPassword = $('input#confirm_password').val();

    if (newPassword == undefined || newPassword == null || newPassword == '') {
      showMessage('danger', 'Please enter new password.');
      $('input#new_password').focus();
      return false;
    }

    if (confirmPassword == undefined || confirmPassword == null || confirmPassword == '') {
      showMessage('danger', 'Please enter confirm password.');
      $('input#confirm_password').focus();
      return false;
    }

    if (newPassword.length < 8) {
      showMessage('danger', 'Password length should be greater than 8.');
      return false;
    }

    if (newPassword != confirmPassword) {
      showMessage('danger', 'Password doesn\'t match.');
      return false;
    }

    loader('show');
    $.ajax({
      url: host + "/settings/store/password",
      type: "put",
      dataType: "json",
      data: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        password: newPassword
      },
      success: function success(response) {
        loader('hide');
        $('input#new_password').val('');
        $('input#confirm_password').val('');

        if (response.success) {
          $('#change-password-modal').modal('hide'); // Show message

          showMessage('success', 'Password was updated successfully!');
        } else {
          // Show message
          showMessage('danger', 'Error, Please retry!');
        }
      },
      error: function error(XMLHttpRequest, textStatus, errorThrown) {
        loader('hide');
        $('input#new_password').val('');
        $('input#confirm_password').val('');
        $('#change-password-modal').modal('hide'); // Show message

        showMessage('danger', 'Error, Please retry!');
      }
    });
  });
});
/******/ })()
;