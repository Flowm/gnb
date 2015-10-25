<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 22/10/15
 * Time: 19:28
 */

session_start();

include "../resource_mappings.php";
include "../bankfunctions.php";
include "../user.php";

ini_set("display_errors",2);
ini_set("error_reporting",E_ALL|E_STRICT);

if (isset($_POST['surname'])) {
    //query by surname
    $search = getClientBySurname($_POST['surname']);
    $result = array();
    for ($i=0; $i < count($search); $i++) {
        $user = new user($search[$i]);
        $result[$i] = $user->getBasicInfo();
    }
    header('Content-type: application/json');
    echo json_encode($result);
}
elseif (isset($_POST['id'])) {
    //query by id
    $search = getClientDetails($_POST['id']);
    $user = new user($search);
    $result = array();
    $result[0] = $user->getBasicInfo();
    header('Content-type: application/json');
    echo json_encode($result);
}
else {
    //Error?
}

?>
