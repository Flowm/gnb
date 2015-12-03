<?php

session_start();

require_once __DIR__."/../resource_mappings.php";

//Worst case, an unauthenticated user is trying to access this page directly
if (!isset($_SESSION["username"]) || !isset($_SESSION["role"])) {
    include(getPageAbsolute('error'));
    exit();
}
//Vertical privilege escalation attempt -> no go
$role = $_SESSION["role"];
if ($role != "employee") {
    include(getPageAbsolute('error'));
    exit();
}

require_once getpageabsolute("db_functions");
require_once getpageabsolute("user");

$json = file_get_contents('php://input');

if ($json != null) {
    $search = json_decode($json, true);
    if (isset($search['name'])) {
        //query by name
        $search = DB::i()->getClientsByName($search['name']);
        $result = array();
        for ($i=0; $i < count($search); $i++) {
            $user = new user($search[$i]);
            $result[$i] = $user->getBasicInfo();
        }
        header('Content-type: application/json');
        echo json_encode($result);
    }
    else if (isset($search['id'])) {
        //query by id
		$search = DB::i()->getClient($search['id']);
		if ($search != false) {
	        $user = new user($search);
			$result = array();
			$result[0] = $user->getBasicInfo();
			header('Content-type: application/json');
			echo json_encode($result);
		} else {
			// No client found
		}
    }
    else {
        //ERROR?
    }
}
else {
    //ERROR?
}

?>
