/**
 * Created by lorenzodonini on 17/10/15.
 */
function goToLoginPage() {
    location.href="login.php";
}

function goToRegistrationPage() {
    location.href="registration/registration.php";
}

function refreshAwesomeData() {
    var destination = 'awesome_data.php';
    var request = {request:"all"};

    performSimpleAjaxRequest(request, destination, refreshCallback);
}

function refreshCallback(data) {
    //TODO: TO IMPLEMENT
}