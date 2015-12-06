<?php

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("utilityfunctions");

//Worst case, an unauthenticated user is trying to access this page directly
if (!isset($_SESSION["username"]) || !isset($_SESSION["role"])) {
    include(getPageAbsolute('error'));
    exit();
}
//The user is logged in, but tries to access another page directly
else if (!isset($section)) {
    header("Location:".getPageURL('home'));
    exit();
}
//Vertical privilege escalation attempt -> no go
$role = $_SESSION["role"];
if ($role != "client") {
    include(getPageAbsolute('error'));
    exit();
}

require_once getPageAbsolute("user");


$BARNEY_QUOTES = array("In my body, where the shame gland should be, there is
                    a second awesome gland. True story.",
    "Suits are full of joy. They’re the sartorial
                equivalent of a baby’s smile.",
    "It’s gonna be legend-... wait for it… and I hope
                you’re not lactose intolerant because the second half of that word is DAIRY!",
    "Okay, pep talk! You can do this, but to be more
                accurate, you probably can’t. You’re way out of practice and she’s way too hot
                for you. So, remember, it’s not about scoring. It’s about believing you can do
                it, even though you probably can’t. Go get ‘em, tiger!",
    "Think of me like Yoda, but instead of being little and
                green I wear suits and I’m awesome. I’m your bro—I’m Broda!");


$quote_of_the_day = null;

if (isset($_SESSION['quote'])) {
    $quote_of_the_day = $_SESSION['quote'];
}
else {
    $quote_of_the_day = $BARNEY_QUOTES[rand(0, count($BARNEY_QUOTES)-1)];
    $_SESSION['quote'] = $quote_of_the_day;
}

?>

<div class="simple-container-no-bounds simple-text-centered">
    <h1 class="title2">Our CEO's quote of the day:</h1>
    <p class="simple-text-big"><?php echo $quote_of_the_day ?></p><br><br>
    <p class="simple-text-big">Big party today, so suit up!</p>
</div>

<?php
//If the client uses TANs sent by email, we need to show him the password to decrypt the PDF
$user = new user(DB::i()->getUser($_SESSION["user_id"]));
if (DB::i()->mapAuthenticationDevice($user->auth_device) == 'TANs') {
    echo '<br><hr class="hr-thin"><br>';
    echo '<div class="simple-container-no-bounds simple-text-centered">';
    echo '<p class="simple-text-big">Below you can see the password '.
        'needed to decrypt the PDF you received via email:<br></p>';
    echo '<h1 class="title2">'.$user->getPDFHash().'</h1>';
	echo '</div>';
}

?>
