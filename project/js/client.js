/**
 * Created by lorenzodonini on 17/10/15.
 */
function goToOverview(frame) {
    var params = {section:"client_overview"};
    if (frame != undefined) {
        params["frame"] = frame;
    }
    performPostRequest("client.php",params);
    //location.href = "client.php?section=client_overview";
}

function goToAccounts(frame, account) {
    var params = {section:"client_accounts"};
    if (frame != undefined) {
        params["frame"] = frame;
    }
    if (account != undefined) {
        params["account"] = account;
    }
    performPostRequest("client.php",params);
    //location.href = "client.php?section=client_accounts";
}

function logout() {
    location.href="logout.php";
}