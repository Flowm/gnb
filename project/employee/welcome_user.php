<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 27/10/15
 * Time: 12:33
 */

function welcomeUser($user) {
    //TODO: IMPLEMENT LOGIC
}

function approveRegistration($approver_id, $user) {
    //TODO: WANNA CHANGE THIS LOGIC!! THIS SUCKS
    if ($user->role == '0') {
        //Client
        $result = approveClient($approver_id, $user->id);
        if (!$result) {
            return false;
        }
        else {
            welcomeUser($user);
        }
    }
    else if ($user->role == '1') {
        //Employee
        $result = approveEmployee($approver_id, $user->id);
        if (!$result) {
            return false;
        }
        else {
            welcomeUser($user);
        }
    }
    return true;
}

function approveUserRegistrations($requests) {
    $requests = explode(";",$requests);
    $approver_id = 'PLACEHOLDER';

    foreach ($requests as $request) {
        $exploded = explode(":",$request);
        $id = $exploded[0];
        $role = $exploded[1];
        $data = getUserDetails($id,$role);
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