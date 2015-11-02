/**
 * Created by lorenzodonini on 18/10/15.
 */
function onSelectedAccount(section, frame, client_id) {
    if (typeof(section) === 'undefined') {
        section = 'my_accounts';
    }
    if (typeof (frame) === 'undefined') {
        frame = 'account_overview';
    }
    var option = document.getElementById("account_select");
    var selected = option.options[option.selectedIndex].value;
    var params = {account: selected, section: section, frame: frame};
    if (typeof (client_id) !== 'undefined') {
        params['client_id'] = client_id;
    }
    //Now we perform a post operation in which we tell the server that the user chose a different bank account
    performPostRequest(window.location.href, params);
}

function uploadFile() {
    var form = document.getElementById('uploadForm');
    form.setAttribute("action", window.location.href);
    var params = {section:"my_accounts", frame:"new_transaction_multiple"};
    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenfield = document.createElement("input");
            hiddenfield.setAttribute("type","hidden");
            hiddenfield.setAttribute("name", key);
            hiddenfield.setAttribute("value", params[key]);

            form.appendChild(hiddenfield);
        }
    }
    form.submit();
}

function verifyTransaction() {
    var form = document.getElementById('transactionForm');
    form.setAttribute("action", window.location.href);
    var params = {section:"my_accounts", frame:"verify_transaction"};
    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenfield = document.createElement("input");
            hiddenfield.setAttribute("type","hidden");
            hiddenfield.setAttribute("name", key);
            hiddenfield.setAttribute("value", params[key]);

            form.appendChild(hiddenfield);
        }
    }
    form.submit();
}
