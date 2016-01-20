<?php

require_once __DIR__."/../resource_mappings.php";

$error_types = array(
	0=>'All fields are mandatory!',
    1=>'The repeated password does not match the original one!',
    2=>'Invalid email address format',
    3=>'The email you entered is already associated to an account',
    4=>'Your password must be between 8 and 20 characters long and must contain at least 1 number and 1 letter!',
    5=>'Invalid role entered',
    6=>'Invalid banking option entered',
    7=>'Invalid CAPTCHA code please try again',
    );

$logo_svg = getMedia('logo_svg');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <link rel="stylesheet" type="text/css" href="../style/gnb.css">
    <script type="text/javascript" src="../js/registration.js"></script>
    <link rel="icon" type="image/png" href="../media/gnb_icon.png" />
</head>
<body>
<div class="mainContainer">
    <img src="<?php echo $logo_svg ?>" alt="GNB Logo" class="logo_big">
    <div class="simple-container">
        <h1 class="title3">In order to register correctly, you will need to fill in the form below</h1>
        <form method="post" action="registration_request.php" id="registrationForm">
            <div class="formRow">
                <div class="formLeftColumn">
                    <span class="simple-text-big">What are you?</span>
                </div>
                <div class="formRightColumn">
                    <input type="radio" id="type1" name="type" value="client" checked
                           onchange="toggleBankingMethod(true)">
                    <label for="type1"><span></span>Client</label>
                    <input type="radio" id="type2" name="type" value="employee"
                           onchange="toggleBankingMethod(false)">
                    <label for="type2"><span></span>Employee</label><br>
                </div>
            </div>
            <div class="formRow">
                <div class="formLeftColumn">
                    <label for="email" class="simple-label">Enter your email address: </label>
                </div>
                <div class="formRightColumn">
                    <input type="email" id="email" name="email" placeholder="Email"><br>
                </div>
            </div>
            <div class="formRow">
                <div class="formLeftColumn">
                    <label for="firstname" class="simple-label">Enter your first name: </label>
                </div>
                <div class="formRightColumn">
                    <input type="text" id="firstname" name="firstname" placeholder="Name"><br>
                </div>
            </div>
            <div class="formRow">
                <div class="formLeftColumn">
                    <label for="lastname" class="simple-label">Enter your last name: </label>
                </div>
                <div class="formRightColumn">
                    <input type="text" id="lastname" name="lastname" placeholder="Lastname"><br>
                </div>
            </div>
            <div class="formRow">
                <div class="formLeftColumn">
                    <label for="password" class="simple-label">Enter a valid password: </label>
                </div>
                <div class="formRightColumn">
                    <input type="password" id="password" name="password" placeholder="Password"><br>
                </div>
            </div>
            <div class="formRow">
                <div class="formLeftColumn">
                    <label for="password_repeat" class="simple-label">Repeat the password: </label>
                </div>
                <div class="formRightColumn">
                    <input type="password" id="password_repeat" name="password_repeat" placeholder="Password repeat"><br>
                </div>
            </div>
            <div class="formRow">
                <div class="formLeftColumn">
                    <label for="capcha_text" class="simple-label">Enter the Capcha </label>
                </div>
                <div class="formRightColumn">
                    <input type="text" id="captcha_code" name="captcha_code" placeholder=""><br>
                </div>
            </div>
            <div class="formRow">
                <div class="formLeftColumn">
					<label for="capcha_image" class="simple-label">
						<img id="captcha" src="<?=getPageURL("secimg_show")?>" alt="CAPTCHA Image" />
                	
						<a href="#" onclick="document.getElementById('captcha').src = '<?=getPageURL("secimg_show")?>' + '?' + Math.random(); return false">
							<img src="<?=getMedia("capcha_reload")?>" height=17 width=17/>
						</a>
					</label>
                </div>
                
                
            </div>


            <!-- PHASE 3 CODE -->
            <div id="bankingMethod">
                <hr class="hr-thin">
                <div class="simple-container" id="bankingMethod">

                    <p class="simple-text simple-text-centered">
                        In order to perform money transfers in our bank, you will need your own TANs. You can either receive
                        these TANs directly via an encrypted email, or become one of our futuristic clients and
                        use our awesome app which makes bank transfers legendary easy.
                    </p>
                </div>
                <div class="formRow">
                    <span class="simple-text-big">Choose your preferred online banking method*: </span>
                </div>
                <div class="formRow">
                    <div class="formSingleColumn">
                        <input type="radio" id="banking1" name="banking" value="email" checked>
                        <label for="banking1"><span></span>Send me the TANs via encrypted email</label>
                    </div>
                </div>
                <div class="formRow">
                    <div class="formSingleColumn">
                        <input type="radio" id="banking2" name="banking" value="app">
                        <label for="banking2"><span></span>I want to use your awesome app</label><br>
                    </div>
                </div>
                <div class="formRow">
                    <p class="simple-text simple-text-italic simple-text-centered">
                        *Once made, this choice will be <b>IRREVERSIBLE</b>.
                    </p>
                </div>
            </div>
            <!-- END PHASE 3 CODE -->

            <hr class="hr-thin">
            <div class="formRow">
            <span id="error" class="error">
                <?php
                $error = null;
                if (isset($_GET) && isset($_GET["error"])) {
                    $error = $_GET["error"];
                    if (isset($error_types[$error])) {
                        echo $error_types[$error];
                    }
                }
                ?></span><br>
            </div>
            <div class="button-container">
                <button type="button" onclick="register()" class="simpleButton">Register</button>
            </div>
        </form>
    </div>
    <div class="footerContainer">
        <hr class="hr-thin">
        <p class="simple-text simple-text-italic">This is not a real bank. All rights reserved.</p>
    </div>
</div>
</body>
</html>
