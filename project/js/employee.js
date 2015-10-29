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
    return handleRegistrations('approveRegistration');
}

function rejectRegistration() {
    return handleRegistrations('rejectRegistration');
}

function handleRegistrations(action) {
    var selected = document.getElementsByName("action_check");
    var ids = "";
    var added = false;
    for (var i=0; i<selected.length; i++) {
        if (selected[i].checked) {
            if (added) {
                ids += ";";
            }
            ids += selected[i].id;
            added = true;
        }
    }
    var params = {section:"employee_area", frame:"manage_registration", action:action, users:ids};
    return performPostRequest("employee.php", params);
}

//THE FOLLOWING FUNCTIONS ARE NEEDED INSIDE THE manage_clients.php FILE
function clearSearchField() {
    var searchField = document.getElementById("search_field");
    searchField.value = '';
}

function searchForClients() {
    var searchType = document.getElementById("search_by_type");
    if (searchType.selectedIndex == -1) {
        return null;
    }
    var type = searchType.options[searchType.selectedIndex].value;
    var searchField = document.getElementById("search_field");
    var json = Object();
    json[type] = searchField.value;
    performSimpleAjaxRequest(JSON.stringify(json), "search_client.php", displaySearchResults);
}

function displaySearchResults(result) {
    var clients = JSON.parse(result);

    //Clearing search field
    var searchField = document.getElementById("search_field");
    searchField.value = '';

    var table = document.getElementById("search_results");
    //Clearing the old table
    while (table.rows != undefined && table.rows.length > 0) {
        table.deleteRow(0);
    }

    //In case we didn't have any results at all
    if (clients.length == 0) {
        var dummyRow = table.insertRow(0);
        dummyRow.insertCell(0).innerHTML = 'No results were found!';
        return;
    }

    //Inserting header
    var hRow = table.insertRow(0);
    hRow.insertCell(0).innerHTML = 'ID';
    hRow.insertCell(1).innerHTML = 'Firstname';
    hRow.insertCell(2).innerHTML = 'Lastname';
    hRow.insertCell(3).innerHTML = 'Email';
    hRow.insertCell(4).innerHTML = 'Status';
    hRow.insertCell(5).innerHTML = 'Role';
    hRow.insertCell(6).innerHTML = '';

    for (var i=0; i<clients.length; i++) {
        var client = clients[i];
        var row = table.insertRow(table.rows.length);
        row.insertCell(0).innerHTML = client['id'];
        row.insertCell(1).innerHTML = client['firstname'];
        row.insertCell(2).innerHTML = client['lastname'];
        row.insertCell(3).innerHTML = client['email'];
        row.insertCell(4).innerHTML = client['status'];
        row.insertCell(5).innerHTML = client['role'];
        row.insertCell(6).innerHTML = "<button onclick='goToManageClient("+client['id']+")'>Details</button>";
    }
}

function checkAllBoxes() {
    var checkboxes = document.getElementsByName('action_check');
    var checked = document.getElementById('selectAll_check').checked;
    for (var i=0; i<checkboxes.length; i++) {
        checkboxes[i].checked = checked;
    }
}

function goToManageClient(clientId) {
    var params = {section:"employee_area", frame:"manage_client", client_id:clientId};
    return performPostRequest("employee.php",params);
}

function goToTransferDetails(transferId) {
    var params = {section:"employee_area", frame:"transfer_details", transfer_id:transferId};
    return performPostRequest("employee.php",params);
}

function approveTransfer() {
    return handleTransfers('approveTransfer');
}

function rejectTransfer() {
    return handleTransfers('rejectTransfer');
}

function handleTransfers(action) {
    var selected = document.getElementsByName("action_check");
    var ids = "";
    var added = false;
    for (var i=0; i<selected.length; i++) {
        if (selected[i].checked) {
            if (added) {
                ids += ";";
            }
            ids += selected[i].id;
            added = true;
        }
    }
    var params = {section:"employee_area", frame:"manage_transfer",action:action, transactions:ids};
    return performPostRequest("employee.php",params);
}

function logout() {
    location.href="../logout.php";
}