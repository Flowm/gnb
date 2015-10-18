/**
 * Created by lorenzodonini on 18/10/15.
 */
function onSelectedAccount(page) {
    var option = document.getElementById("account_select");
    var selected = option.options[option.selectedIndex].value;
    var params = {account_select: selected, section: "client_accounts", frame: "account_overview"};
    //Now we perform a post operation in which we tell the server that the user chose a different bank account
    performPostRequest(page, params);
}