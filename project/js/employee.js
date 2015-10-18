/**
 * Created by lorenzodonini on 17/10/15.
 */
function goToOverview(frame) {
    var params = {section:"employee_overview"};
    if (frame != undefined) {
        params["frame"] = frame;
    }
    return performPostRequest("employee.php",params);
    //location.href = "employee.php?section=employee_overview";
}

function goToManageClients(frame) {
    var params = {section:"manage_clients"};
    if (frame != undefined) {
        params["frame"] = frame;
    }
    return performPostRequest("employee.php",params);
    //location.href = "employee.php?section=manage_clients";
}

function goToAccounts(frame, account) {
    var params = {section:"client_accounts"};
    if (frame != undefined) {
        params["frame"] = frame;
    }
    if (account != undefined) {
        params["account"] = account;
    }
    return performPostRequest("employee.php",params);
    //location.href = "employee.php?section=client_accounts";
}

function logout() {
    location.href="logout.php";
}