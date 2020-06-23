let reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
$('#registerDate').datepicker({
    dateFormat: 'yy/mm/dd',
});

$(document).ready(function() {
    setDefaultFontSize();
    $("[href]").each(function() {
        if (this.href == window.location.href) {
            $(this).css("color", "#ffa500;");
        }
    });
});

$('#showformregister').click(function() {
    $('#block-form-register').show();
    $('body').css('overflow', 'hidden');
})
$('#btnClose').click(function() {
    $('#block-form-register').hide();
    refreshValidateRegister();
    $('body').css('overflow', 'auto');
})

$('#showformlogin').click(function() {
    $('#block-form-login').show();
    $('body').css('overflow', 'hidden');
})
$('#btnCloseLogin').click(function() {
    $('#block-form-login').hide();
    refreshValidateLogin();
    $('body').css('overflow', 'auto');
})
$('#btnCancel').click(function() {
    $('#block-form-register').hide();
    refreshValidateRegister();
    $('body').css('overflow', 'auto');
})
$('#btnCancelLogin').click(function() {
    refreshValidateLogin();
    $('#block-form-login').hide();
    $('body').css('overflow', 'auto');
})

function refreshValidateRegister() {
    $('#email-error').html(null);
    $('#password-error').html(null);
    $('#name-error').html(null);
    $('#dob-error').html(null);
}

function refreshValidateLogin() {
    $('#email-login-error').html(null);
    $('#password-login-error').html(null);
}

function checkValidateRegister() {
    refreshValidateRegister();
    let email = $("input[name='email']").val();
    let password = $("input[name='password']").val();
    let name = $("input[name='name']").val();
    let dob = $("input[name='dob']").val();
    let check = true;
    if (email.length == 0) {
        $('#email-error').html('Please enter your email.')
        check = false;
    }
    if (!reg.test(email)) {
        $('#email-error').html('Please provide a valid email address.')
        check = false;
    }
    if (email.length > 64) {
        $('#email-error').html('Max length of email is 64 character.')
        check = false;
    }
    if (password == 0) {

        $('#password-error').html('Please enter your password.')
        check = false;
    }
    if (password.length > 72) {

        $('#password-error').html('Max length of password if 72 character.')
        check = false;
    }

    if (name.length > 100) {

        $('#name-error').html('Max length of user name is 100 character.')
        check = false;
    }
    if (name.length == 0) {
        $('#name-error').html('Please enter your user name')
        check = false;
    }
    if (dob == 0) {

        $('#dob-error').html('Please enter your birthday')
        check = false;
    }
    return check;
}

function checkValidationLogin() {
    refreshValidateLogin();
    let email = document.getElementById('email-login').value;
    let password = document.getElementById('password-login').value;
    let check = true;
    if (email.length == 0) {
        $('#email-login-error').html('Please enter your email.')
        check = false;
    }
    if (email.length > 64) {
        $('#email-login-error').html('Max length of email is 64 character.')
        check = false;
    }
    if (!reg.test(email)) {
        $('#email-login-error').html('Please provider a valid email address.')
        check = false;
    }
    if (password == 0) {

        $('#password-login-error').html('Please enter your password.')
        check = false;
    }
    if (password.length > 72) {

        $('#password-login-error').html('Max length of password is 72 character.')
        check = false;
    }
    return check;
}

function changeFontSize(size) {
    document.cookie = "fontSize=;expires=Thu, 01 Jan 1970 00:00:00 UTC;";
    $('#content').css("fontSize", size);
    setCookie('fontSize', size, 7);
    $('.btnfontSize').css('backgroundColor', '#ffffff');
    $('#' + 'fontSize' + size).css('backgroundColor', '#ffa500;');
}

function setCookie(cname, cvalue, exdays) {
    let d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function setDefaultFontSize() {
    let fontSize = getCookie('fontSize');
    if (fontSize != "") {
        $('#content').css("fontSize", fontSize);
    } else {
        setCookie('fontSize', '13px', 7);
        $('#content').css("fontSize", 13);
    }
    $('.btnfontSize').css('backgroundColor', 'none');
    $('#' + `fontSize` + fontSize).css('backgroundColor', '#ffa500;');
}