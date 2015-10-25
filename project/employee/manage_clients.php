<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 22/10/15
 * Time: 08:37
 */
?>

To obtain details about a client, please look him up here: <br>
<form>
    <label for="search_by_type">Search by </label> <select id="search_by_type" onchange="clearSearchField()">
        <option value="surname" selected>Surname</option>
        <option value="id">ID</option>
    </select>
    <label for="search_field">Search for: </label><input type="text" name="surname" id="search_field"><br>
    <button type="button" onclick="searchForClients()">Search</button>
</form>

<br>
<p id="search_results_label"></p>
<table id="search_results">
</table>
