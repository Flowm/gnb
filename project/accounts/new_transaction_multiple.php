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

$error_types = array(0=>'Invalid input!',
	1=>'File upload failed!',
	2=>'ERROR: Parsing of input file failed',
	3=>'ERROR: Unknown file error',
	4=>'ERROR: Invalid TAN');


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
				$filestr .= $elements[0] . $elements[1];
			} else {
				return 2;
			}
		}
		fclose($handle);
	} else {
		return 3;
	}

	// Check TAN
	$timestamp = verifyAppGeneratedTANData($tan, $filestr, $pin);
	if ($timestamp == null || $timestamp <= $account->last_tan_time) {
		return 4;
	} else {
		DB::i()->setLastTANTime($account_id,$timestamp);
	}
	return "SUCCESS";
}

$account_id = $_SESSION["account_id"];

//Executed in case a transaction was started
if (isset($_FILES["transactionsCSV"]) && isset($_POST["tan"])) {
	if (!isset($_POST['token']) || $_POST['token'] != $_SESSION['token']) {
		die("CSRF detected!");
	}
	else {
		$token = "";
		unset($_SESSION['token']);
	}

	//If no TAN was entered by the user, we want a proper error message
	$tan = check_post_input("tan",SANITIZE_STRING_DESC);
	if ($tan != null) {
		$tan = preg_replace("([^a-zA-Z0-9+\/])", '', $tan);

		$user_id = $_SESSION["user_id"];
		$user = new user(DB::i()->getUser($user_id));
		$auth_type = DB::i()->mapAuthenticationDevice($user->auth_device);

		$file = $_FILES["transactionsCSV"];
		$name = session_id();
		$target_file = getPageAbsolute("uploads") . $name;
		$ctransact = getPageAbsolute("ctransact");
		if (move_uploaded_file($file['tmp_name'], $target_file)) {
			echo "<div class='success'>Fileupload successful! Starting batch processing...<br />";

			$cmdln = "";
			if ($auth_type == "SCS") {
				$msg = verifySCSTAN($tan, $user->pin, $target_file);
				if ($msg == "SUCCESS") {
					DB::i()->removeInvalidTanAttempts($user_id);
					$cmdln = "$ctransact '$account_id' 'ASMARTCARDTANYO' '$target_file'";
				} else {
					DB::i()->handleInvalidTan($user_id);
					$error = $msg;
				}
			} else {
				// NON-SCS TANs get verified in the c parser
				if (DB::i()->verifyTANCode($account_id, $tan) == false) {
					$cmdln = "";
					$error = 4;
					DB::i()->handleInvalidTan($user_id);
				} else {
					DB::i()->removeInvalidTanAttempts($user_id);
					$cmdln = "$ctransact '$account_id' '$tan' '$target_file'";
				}
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
			$error = 2;
		}
	}
	else {
		$error = 0;
	}
}
//Transaction hasn't started yet
else {
	$crypto = true;
	$token = base64_encode(openssl_random_pseudo_bytes(32, $crypto));
	$_SESSION['token'] = $token;
}

?>
<div class="simple-text">
	To perform multiple transactions in one request you can upload a batch transaction file.
    <br />
	Format the file according to be following rules:
	<ul>
		<li>Each transaction in a separate line</li>
		<li>Fields separated only by a comma</li>
		<li>No quoting of whitespace required</li>
		<li>The complete input between two commas gets treated as value</li>
	</ul>
	<p class="simple-text-big">Example:<br>DST_ACCOUNT,AMOUNT,DESCRIPTION</p>
</div>
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
		<?php
		if (isset($error)) {
			echo '<div class="formRow"><span id="error" class="error">';
			echo $error_types[$error];
			echo '</span><br></div>';
		}
		?>
		<div class="button-container">
			<button type="button" onclick="uploadFile()" class="simpleButton">Upload</button>
		</div>
	</div>
</form>
