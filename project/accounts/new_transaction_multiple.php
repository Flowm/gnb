<?php

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("utilityfunctions");

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

if (empty($_SESSION["account_id"]))
	die("Please choose an account");

$account_id = $_SESSION["account_id"];

if (isset($_FILES["transactionsCSV"]) && isset($_POST["tan"])) {
	$file = $_FILES["transactionsCSV"];
	$tan = santize_input($_POST["tan"],SANITIZE_STRING_VAR);
	$tan = preg_replace("([^a-zA-Z0-9+\/])", '', $tan);
	$name = session_id();
	$target_file = getPageAbsolute("uploads") . $name;
	$ctransact = getPageAbsolute("ctransact");
	if (move_uploaded_file($file['tmp_name'], $target_file)) {
		echo "<div class='success'>Fileupload successful! Starting batch processing...<br />";
		$cmdln = "$ctransact '$account_id' '$tan' '$target_file'";
		exec($cmdln, $cmdout);
		unlink($target_file);
		foreach ($cmdout as $line) {
			echo $line . "<br />";
		}
		echo "</div><br />";
	} else {
		echo "<div class='error'>Fileupload failed!</div><br />";
	}
}

?>
<p class="simple-text">
	To perform multiple transactions in one request you can upload a batch transaction file.
    <br />
	Format the file according to be following rules:
	<ul>
		<li>Each transaction in a separate line</li>
		<li>Fields separated only by a comma</li>
		<li>No quoting of whitespace required</li>
		<li>The complete input between two commas gets treated as value</li>
		<li>Example:</li>
		<p class="simple-text-big">DST_ACCOUNT,AMOUNT,DESCRIPTION</p>
	</ul>
</p>
<br />
<p class="simple-text">Note: All Transactions over 10,000 will require manual approval by an employee</p>
<form id="uploadForm" method="post" enctype="multipart/form-data" class="simple-text">
	<div class="transaction-container">
		<div class="formRow">
			<div class="formLeftColumn">
				<label for="transactionsCSV" class="simple-label">Transaction file </label>
			</div>
			<div class="formRightColumn">
				<input type="file" name="transactionsCSV" id="transactionsCSV"><br>
			</div>
		</div>
		<div class="formRow">
			<div class="formLeftColumn">
				<label for="tan" class="simple-label">TAN code </label>
			</div>
			<div class="formRightColumn">
				<input type="text" name="tan" id="tan" placeholder="TAN"><br>
			</div>
		</div>
		<div class="button-container">
			<button type="button" onclick="uploadFile()" class="simpleButton">Upload</button>
		</div>
	</div>
</form>
