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
    var params = {section:"../accounts/my_accounts"};
    if (frame != undefined) {
        params["frame"] = frame;
    }
    if (account != undefined) {
        params["account"] = account;
    }
    return performPostRequest("employee.php",params);
}

function logout() {
    location.href="../logout.php";
}