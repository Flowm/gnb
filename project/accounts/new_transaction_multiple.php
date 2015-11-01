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
	$allowed_ext = array("txt","csv");
	if (in_array($file_ext, $allowed_ext) === false) {
		echo "<div id='fail'>Fileupload failed</div>";
	}

	$target_file = getPageAbsolute("uploads") . basename($file["name"]);
	$ctransact = getPageAbsolute("ctransact");
	if (move_uploaded_file($file['tmp_name'], $target_file)) {
		echo "<div id='success'>Fileupload successful<br />";
		$cmdln = "$ctransact '$account_id' '$target_file'";
		exec($cmdln, $cmdout);
		foreach ($cmdout as $line) {
			echo $line . "<br/>";
		}
		echo "</div>";
	}
}

?>
<p>To perform multiple transactions in one request you can upload a batch transaction file.
Format the file according to be following example (each transaction in a new line):</p>
<p>Destination Account ID,Amount,Description,TAN Code</p>

<p>Note: Any Transaction over 10,000 will be need to be processed which may take up to 48 hours</p>

<form id="uploadForm" method="post" enctype="multipart/form-data">
	Select transaction file to upload:
	<input type="file" name="transactionsCSV" id="transactionsCSV">
	<button type="button" onclick="uploadFile()">Upload</button>
</form>
