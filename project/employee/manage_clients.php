<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 22/10/15
 * Time: 08:37
 */
?>

<p class="simpleTextBig">To obtain details about a client, please look him up here: </p>
<form>
    <div class="simple-container-no-bounds simpleTextBig">
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
