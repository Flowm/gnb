<?php

require_once __DIR__."/../resource_mappings.php";

// Worst case, an unauthenticated user is trying to access this page directly.
// This page is not available to direct access.
if (!isset($_SESSION['banking']) || $_SESSION['banking'] != 'email') {
    header("Location:".getPageURL('home'));
    exit();
}

unset($_SESSION['banking']);

?>

<h1 class="title3">Welcome to the Goliath National Bank!</h1>
<p class="simple-text-big simple-text-centered">
    Your request has been received and will be processed shortly.
    A confirmation email will be sent to you, once your registration has been approved.<br>
    Thank you for choosing the Goliath National Bank!
</p>
<hr class="hr-thin">
<p class="simple-text-big simple-text-centered">
    Here is your PIN: 000000
</p>
