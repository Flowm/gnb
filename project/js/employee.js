/**
 * Created by lorenzodonini on 17/10/15.
 */
function goToOverview(frame) {
    var params = {section:"employee_overview"};
    if (frame != undefined) {
        params["frame"] = frame;
    }
    return performPostRequest("employee.php",params);
}

function goToEmployeeArea(frame) {
    var params = {section:"employee_area"};
    if (frame != undefined) {
        params["frame"] = frame;
    }
    return performPostRequest("employee.php",params);
}

function goToMyAccounts(frame, account) {
    var params = {section:"my_accounts"};
    if (frame != undefined) {
        params["frame"] = frame;
    }
    if (account != undefined) {
        params["account"] = account;
    }
    return performPostRequest("employee.php",params);
}

function approveRegistration() {
    var selected = document.getElementsByName("action_check");
    var ids = "";
    for (var i=0; i<selected.length; i++) {
        ids += selected[i].id;
        if ((i + 1) < selected.length) {
            ids += ";";
        }
    }
    var params = {section:"employee_area", frame:"manage_registration", approved_registrations:ids};
    return performPostRequest("employee.php",params);
}

function rejectRegistration() {
    //TODO: TO IMPLEMENT
}

function logout() {
    location.href="../logout.php";
}