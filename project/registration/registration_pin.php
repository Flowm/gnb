<?php

require_once __DIR__."/../resource_mappings.php";

// Worst case, an unauthenticated user is trying to access this page directly.
// This page is not available to direct access.
if (!isset($_SESSION['banking']) || $_SESSION['banking'] != 'app') {
    header("Location:".getPageURL('home'));
    exit();
}
if (!isset($_SESSION['pin'])) {
    header("Location".getPageURL('home'));
    exit();
}

$random_pin = $_SESSION['pin'];
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
    This PIN is bound to your account and cannot be changed!<br>
    You won't get a chance to display this PIN again, so be sure to copy it right now and save it somewhere safe.
    Also, for security reasons, never tell your PIN to anybody!
</p>
<h1 class="title1">Here is your PIN: <?= $random_pin?></h1>
<p class="simple-text-big simple-text-centered">
    Remember to copy and save this PIN for future use, as it will never be displayed again!
</p>
<hr class="hr-thin">
<p class="simple-text-big simple-text-centered">On the link below you can download the GNB Authenticator.
    Java Runtime Environment 1.7 is required in order to run it.<br>
    <a href="<?= getPageURL('scs') ?>">Download SmartCardSimulator here!</a>
</p>


