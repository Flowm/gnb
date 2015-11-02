<?php

require_once __DIR__."/../resource_mappings.php";

if (empty($_SESSION["user_id"]))
	die("User missing");
if (empty($_SESSION["account_id"]))
	die("Please choose an account");

$account_id = $_SESSION["account_id"];
if (isset($_FILES['transactionsCSV'])) {
	$file = $_FILES['transactionsCSV'];
	$name = $file['name'];

	$file_arr = explode('.',$name);
	$file_ext = strtolower(array_pop($file_arr));
	$allowed_ext = array("txt","TXT","csv","CSV");
	if (in_array($file_ext, $allowed_ext)) {
		$target_file = getPageAbsolute("uploads") . basename($file["name"]);
		$ctransact = getPageAbsolute("ctransact");
		if (move_uploaded_file($file['tmp_name'], $target_file)) {
			echo "<div id='success'>Fileupload successful!<br />";
			$cmdln = "$ctransact '$account_id' '$target_file'";
			exec($cmdln, $cmdout);
			foreach ($cmdout as $line) {
				echo $line . "<br/>";
			}
			echo "</div>";
		} else {
			echo "<div id='fail'>Fileupload failed!</div>";
		}
	} else {
		// Checking by file extention is just to prevent user errors, it offers 
		// no real security benefit
		echo "<div id='fail'>Only CSV or TXT documents allowed!</div>";
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
		<p class="simple-text-big">DST_ACCOUNT,AMOUNT,DESCRIPTION,TAN</p>
	</ul>
</p>
<br />
<p class="simple-text">Note: All Transactions over 10,000 will require manual approval by an employee</p>
<form id="uploadForm" method="post" enctype="multipart/form-data" class="simple-text">
	Select transaction file to upload:
	<input type="file" name="transactionsCSV" id="transactionsCSV">
	<button type="button" onclick="uploadFile()" class="simpleButton">Upload</button>
</form>
