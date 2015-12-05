/**
 * Created by lorenzodonini on 26/10/15.
 */

function register() {
    if(checkContents()) {
        var form = document.getElementById('registrationForm');
        form.submit();
    }
}

function checkEmail(email, errorText) {
    var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (!re.test(email.value)) {
        errorText.innerHTML = 'Invalid email address format';
        return false;
    }
    return true;
}

function checkPassword(pass, repeat, errorText) {
    if(pass.value != repeat.value) {
        errorText.innerHTML = 'The repeated password does not match the original one!';
        return false;
    }
    //var re = /#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[#.-_,$%&!]).*$#/;
    //var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/;
    var numbers = /[0-9]+/;
    var chars = /[a-zA-Z]+/;
    var result = true;
    if (pass.value.length < 8 || pass.value.length > 20) {
        result = false;
    }
    if (!numbers.test(pass.value)) {
        result = false;
    }
    if (!chars.test(pass.value)) {
        result = false;
    }
    if (!result) {
        errorText.innerHTML = 'Your password must be between 8 and 20 characters long ' +
            'and must contain at least 1 number and 1 letter!';
    }
    return result;
}

function toggleBankingMethod(bankingMethod) {
    if (bankingMethod == undefined) {
        return;
    }
    bankingMethodDiv = document.getElementById('bankingMethod');
    if (bankingMethod) {
        bankingMethodDiv.style.display = 'block';
    }
    else {
        bankingMethodDiv.style.display = 'none';
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
    if (!checkEmail(email,errorText)) {
        return false;
    }
    return checkPassword(password, passwordRepeat, errorText);
}