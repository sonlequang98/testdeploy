function checkValidate() {
    $('#edit-name-error').html('');
    $('#edit-password-error').html('');
    let name = $("input[name='editName']").val();
    let password = $("input[name='editPassword']").val();
    let check = true;
    if (name.length == 0) {
        $('#edit-name-error').html('Please enter your user name');
        check = false;
    } else {
        if (name.length > 100) {
            $('edit-#name-error').html('Max length of user name is 100 character.');
            check = false;
        }
    }
    if (password.length == 0) {
        $('#edit-password-error').html('Please enter your password.');
        check = false;
    } else {
        if (password.length > 72) {
            $('#edit-password-error').html('Max length of password is 72 character.');
            check = false;
        }
    }
    return check;
}

function changeFormatDate() {
    let date = $('#date').val();
}