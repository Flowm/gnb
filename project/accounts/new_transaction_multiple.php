<?php

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("db_functions");
require_once getpageabsolute("util");

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

$token = "";
if (!isset($_SESSION['token'])) {
    $token = md5(uniqid(rand(), TRUE));
    $_SESSION['token'] = $token;
} else {
    $token = $_SESSION['token'];
}

function verifySCSTAN($tan, $pin, $file) {
	$account_id = $_SESSION["account_id"];
	$user_id = $_SESSION["user_id"];
	$user = new user(DB::i()->getUser($user_id));
	$account = new account(DB::i()->getAccountDetails($account_id));
	$auth_type = DB::i()->mapAuthenticationDevice($user->auth_device);

	// Process file
	$filestr = "";
	$handle = fopen($file, "r");
	if ($handle) {
		while (($line = fgets($handle)) !== false) {
			$elements = explode(",", $line);
			if (isset($elements[0]) && isset($elements[1])) {
				$filestr .= "$elements[0]" . "$elements[1]";
			} else {
				return "ERROR: Parsing of input file failed";
			}
		}
		fclose($handle);
	} else {
		return "ERROR: Unknown file error";
	}

	// Check TAN
	$timestamp = verifyAppGeneratedTANData($tan, $filestr, $pin);
	if ($timestamp == null || $timestamp <= $account->last_tan_time) {
		return "ERROR: Invalid TAN";
	} else {
		DB::i()->setLastTANTime($account_id,$timestamp);
	}
	return "SUCCESS";
}

$account_id = $_SESSION["account_id"];

if (isset($_FILES["transactionsCSV"]) && isset($_POST["tan"])) {
	$token = "";
	if (isset($_POST['token']) && isset($_SESSION['token'])) {
	    if ($_POST['token'] != $_SESSION['token']) {
	        die("CSRF detected!");
	    } else {
	        $token = $_SESSION['token'];
	    }
	}

	$user_id = $_SESSION["user_id"];
	$user = new user(DB::i()->getUser($user_id));
	$auth_type = DB::i()->mapAuthenticationDevice($user->auth_device);

	$file = $_FILES["transactionsCSV"];
	$tan = santize_input($_POST["tan"],SANITIZE_STRING_DESC);
	$tan = preg_replace("([^a-zA-Z0-9+\/])", '', $tan);
	$name = session_id();
	$target_file = getPageAbsolute("uploads") . $name;
	$ctransact = getPageAbsolute("ctransact");
	if (move_uploaded_file($file['tmp_name'], $target_file)) {
		echo "<div class='success'>Fileupload successful! Starting batch processing...<br />";

		$cmdln = "";
		if ($auth_type == "SCS") {
			$msg = verifySCSTAN($tan, $user->pin, $target_file);
			if ($msg == "SUCCESS") {
				$cmdln = "$ctransact '$account_id' 'ASMARTCARDTANYO' '$target_file'";
			} else {
				echo $msg;
			}
		} else {
			// NON-SCS TANs get verified in the c parser
			$cmdln = "$ctransact '$account_id' '$tan' '$target_file'";
		}
		if ($cmdln != "") {
			exec($cmdln, $cmdout);
			foreach ($cmdout as $line) {
				echo $line . "<br />";
			}
		}
		echo "</div><br />";
		unlink($target_file);
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
	<input type="hidden" name="token" value="<?=$token?>">
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
