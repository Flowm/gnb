<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 27/10/15
 * Time: 12:33
 */

require_once '../bankfunctions.php';
require_once '../gnbmailer.php';
require_once "../user.php";

function genRandString($length) {
	return bin2hex(openssl_random_pseudo_bytes($length));
}

function generateTANs($account_id, $cnt=100) {
	$tans = array();
	for($i=0;$i<$cnt;$i++) {
		$newtan = genRandString(15);
		while(!insertTAN($newtan, $account_id)) {
			$newtan = genString(15);
		}
		$tans[$i] = $newtan;
	}
	return $tans;
}

function approveRegistration($approver_id, $user) {
    if ($user->role == '0') {
        if (approveClient($approver_id, $user->id)) {
			$balance = rand(100,1000);
			$account_id = addAccountForUserWithBalance($user->id, $balance);
			$tans = generateTANs($account_id);
			$name = "$user->firstname $user->lastname";
			$gnbmailer = new GNBMailer();
			return $gnbmailer->sendMail_Approval($user->email, $name, $balance, $tans);
		}
    } else if ($user->role == '1') {
        if (approveEmployee($approver_id, $user->id)) {
			$name = "$user->firstname $user->lastname";
			$gnbmailer = new GNBMailer();
			return $gnbmailer->sendMail_Approval($user->email, $name);
		}
	}
	return false;
}

function approveUserRegistrations($requests) {
    $requests = explode(";",$requests);
    $approver_id = 'PLACEHOLDER';

    foreach ($requests as $request) {
        $exploded = explode(":",$request);
        $id = $exploded[0];
        $role = $exploded[1];
        $data = getUser($id,$role);
        if (!$data) {
            //THIS WOULD BE BAD! NEED TO HANDLE THIS CASE
        }
        else {
            $user = new user($data);
            $result = approveRegistration($approver_id, $user);
            if (!$result) {
                //TODO: handle registration error
            }
        }
    }
}

?>
