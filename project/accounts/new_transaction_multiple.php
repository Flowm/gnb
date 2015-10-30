<?php

require_once __DIR__."/../resource_mappings.php";

if (isset($_FILES['transactionsCSV'])) {
	$file = $_FILES['transactionsCSV'];

	$file_ext = strtolower(end(explode('.',$file['name'])));
	$allowed_ext = array("txt","csv");
	if (in_array($file_ext, $allowed_ext) === false) {
		echo "<div id='fail'>FILEUPLOAD FAILED</div>";
	}

	$target_file = getPageAbsolute("uploads") . basename($file["name"]);
	if (move_uploaded_file($file['tmp_name'], $target_file)) {
		echo "<div id='success'>FILEUPLOAD SUCCESSFUL</div>";
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
