<?php

require_once __DIR__."/../resource_mappings.php";

//Worst case, an unauthenticated user is trying to access this page directly
if (!isset($_SESSION["username"]) || !isset($_SESSION["role"])) {
    include(getPageAbsolute('error'));
    exit();
}
//The user is logged in, but tries to access another page directly
else if (!isset($frame)) {
    header("Location:".getPageURL('home'));
    exit();
}
//Vertical privilege escalation attempt -> no go
$role = $_SESSION["role"];
if ($role != "employee") {
    include(getPageAbsolute('error'));
    exit();
}

?>



<p class="simple-label">To obtain details about a client, please look him up here: </p><br>
<form onsubmit="searchForClients(); return false;">
    <div class="simple-container-no-bounds simple-text-big">
        <label for="search_by_type">Search by </label> <select class="select-bar" id="search_by_type" onchange="clearSearchField()">
            <option value="name" selected>Name</option>
            <option value="id">ID</option>
        </select>
        <label for="search_field">Search for: </label><input type="text" name="name" id="search_field" placeholder="Search"><br>
    </div>
    <div class="button-container">
        <button class="simpleButton" type="button" onclick="searchForClients()">Search</button>
    </div>
</form>

<br>
<p id="search_results_label"></p>
<table id="search_results" class="table-default" style="visibility: hidden">
</table>
