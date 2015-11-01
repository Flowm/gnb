/**
 * Created by lorenzodonini on 26/10/15.
 */

function register() {
    if(checkContents()) {
        var form = document.getElementById('registrationForm');
        form.submit();
    }
}

function checkContents() {
    var errorText = document.getElementById('error');
    var type1 = document.getElementById('type1');
    var type2 = document.getElementById('type2');
    if (!type1.checked && !type2.checked) {
        errorText.innerHTML = 'All fields are mandatory!';
        return false;
    }
    var email = document.getElementById('email');
    var firstname = document.getElementById('firstname');
    var lastname = document.getElementById('lastname');
    var password = document.getElementById('password');
    var passwordRepeat = document.getElementById('password_repeat');
    if (email.value == ''
            || firstname.value == ''
            || lastname.value == ''
            || password.value == ''
            || passwordRepeat.value == '') {
        errorText.innerHTML = 'All fields are mandatory!';
        return false;
    }
    var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (!re.test(email.value)) {
        errorText.innerHTML = 'Invalid email address format';
        return false;
    }
    if(password.value != passwordRepeat.value) {
        errorText.innerHTML = 'The repeated password does not match the original one!';
        return false;
    }
    return true;
}