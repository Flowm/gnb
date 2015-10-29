<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 18/10/15
 * Time: 00:32
 
 
 */
include_once ('../main_include.php') ;

#except Account Number 
$account_id = '10000001' ;  
 
echo '<p>Account overview here, with current balance and some basic info</p><br>' ;


$account_holder_info = getAccountOwnerFromID($account_id) ;

drawSingleRecordTable($account_holder_info[0],'Account Holder') ;
 	 
$table_header	 = 'This account is owned by the Legendary '.$account_holder_info[0]["Name"] ;
echo '<h3>'.$table_header.'</h3>';
?>





