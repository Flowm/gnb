<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 15:17
 */

//PHASE 2: We will have to check the current role! Someone could just try to access this page randomly,
// and it would get displayed -> Only a logged in employee can have access to this section

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
    <h1 class="title4">Our CEOs quote of the day:</h1>
    <p class="simpleTextBig"><?php echo $quote_of_the_day ?></p><br><br>
    <p class="simpleTextBig">Big party today, so suit up!</p>
</div>

