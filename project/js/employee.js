/**
 * Created by lorenzodonini on 17/10/15.
 */

//NAVIGATION FUNCTIONS
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
    //We actually send a post parameter, in which the value is the json object itself
    performSimpleAjaxRequest(JSON.stringify(json), "search_client.php", displaySearchResults);
}

function displaySearchResults(result) {
    var clients = JSON.parse(result);

    //Clearing search field
    var searchField = document.getElementById("search_field");
    searchField.value = '';

    var label = document.getElementById("search_results_label");
    label.innerHTML = '';

    var table = document.getElementById("search_results");
    table.style.visibility = 'visible';

    //Clearing the old table
    while (table.firstChild) {
        table.removeChild(table.firstChild);
    }

    //In case we didn't have any results at all
    if (clients.length == 0) {
        table.style.visibility = 'hidden';
        label.innerHTML = 'No results were found';
        return;
    }

    //Inserting header
    var thead = document.createElement('thead');
    var hRow = document.createElement('tr');
    hRow.classList.add('thead-row-default');
    addHeaderElement(hRow,'ID');
    addHeaderElement(hRow,'Firstname');
    addHeaderElement(hRow,'Lastname');
    addHeaderElement(hRow,'Email');
    addHeaderElement(hRow,'Status');
    addHeaderElement(hRow,'Role');
    addHeaderElement(hRow,'');
    thead.appendChild(hRow);
    table.appendChild(thead);

    var tbody = document.createElement('tbody');
    for (var i=0; i<clients.length; i++) {
        var client = clients[i];
        var row = document.createElement('tr');
        row.classList.add('tbody-row-default');
        addRowElement(row, client['id']);
        addRowElement(row, client['firstname']);
        addRowElement(row, client['lastname']);
        addRowElement(row, client['email']);
        addRowElement(row, client['status']);
        addRowElement(row, client['role']);
        addRowElement(row, "<button class='table-button' onclick='goToClientDetails("+client['id']+")'>Details</button>");
        tbody.appendChild(row);
    }
    table.appendChild(tbody);
}

function addHeaderElement(hRow, value) {
    var hElement = document.createElement('th');
    hElement.innerHTML = value;
    hElement.classList.add('th-default');
    hRow.appendChild(hElement);
}

function addRowElement(row, value) {
    var element = document.createElement('td');
    element.innerHTML = value;
    element.classList.add('td-default');
    row.appendChild(element);
}

function checkAllBoxes() {
    var checkboxes = document.getElementsByName('action_check');
    var checked = document.getElementById('selectAll_check').checked;
    for (var i=0; i<checkboxes.length; i++) {
        checkboxes[i].checked = checked;
    }
}

function goToClientDetails(clientId) {
    var params = {section:"employee_area", frame:"client_details", client_id:clientId};
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
