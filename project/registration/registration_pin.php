<?php

require_once __DIR__."/../resource_mappings.php";

// Worst case, an unauthenticated user is trying to access this page directly.
// This page is not available to direct access.
/*if (!isset($_SESSION['banking']) || $_SESSION['banking'] != 'app') {
    header("Location:".getPageURL('home'));
    exit();
}
if (!isset($_SESSION['pin'])) {
    header("Location".getPageURL('home'));
}*/

//TODO: REMOVE THIS PART
$pin = mt_rand(0,9);
for ($i = 0; $i < 6; $i++) {
    $pin .= mt_rand(0,9);
}


unset($_SESSION['banking']);
unset($_SESSION['pin']);

?>

<h1 class="title3">Welcome to the Goliath National Bank!</h1>
<p class="simple-text-big simple-text-centered">
    Your request has been received and will be processed shortly.
    A confirmation email will be sent to you, once your registration has been approved.<br>
    Thank you for choosing the Goliath National Bank!
</p>
<hr class="hr-thin">
<p class="simple-text-big simple-text-centered">
    You chose to use our GNB Authenticator. In order to use it, you will need the PIN shown below.
    This PIN is bound to your account and cannot be changed! For security reasons, never tell your PIN to anybody!
    You also won't get a chance to display this PIN again, so be sure to copy it right now and save it.
</p>
<h1 class="title2">Here is your PIN: <?= $pin?></h1>
<p class="simple-text-big simple-text-centered">
    Remember to copy and save this PIN for future use, as it will never be displayed again!
</p>
<p>Here you can download the </p>


