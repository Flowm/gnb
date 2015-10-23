<?php

class GNBMailer {
	public $mail;

	public function __construct() {
		require_once 'lib/phpmailer/PHPMailerAutoload.php';

		$this->mail = new PHPMailer();
		$this->mail->IsSMTP();
		$this->mail->Host        = "smtp-mail.outlook.com";
		$this->mail->SMTPDebug   = 0;
		$this->mail->SMTPAuth    = TRUE;
		$this->mail->SMTPSecure  = "tls";
		$this->mail->Port        = 587;
		$this->mail->Username    = 'goliath.nb@outlook.com';
		$this->mail->Password    = 'yqycvqvvjlsdyuaf';
		$this->mail->Priority    = 3;
		$this->mail->CharSet     = 'UTF-8';
		$this->mail->Encoding    = '8bit';
		$this->mail->From        = 'goliath.nb@outlook.com';
		$this->mail->FromName    = 'Barney Stinson';
		$this->mail->WordWrap    = 900;
	}

	public function send() {
		$this->mail->Send();
		$this->mail->SmtpClose();

		if ( $this->mail->IsError() ) {
			echo "ERROR<br />$mail->ErrorInfo<br />";
			return 1;
		} else {
			echo "OK<br /><br />";
			return 0;
		}
	}

	public function sendMail($to, $to_name, $subject, $body_txt, $body_html="") {
		$this->mail->AddAddress($to, $to_name);
		$this->mail->Subject = $subject;

		if ($body_html=="") {
			$this->mail->Body = $body_txt;
		} else {
			$this->mail->isHTML(TRUE);
			$this->mail->Body = $body_html;
			$this->mail->AltBody = $body_txt;
		}
		return $this->send();
	}

	public function sendWelcomeMail($cust_addr, $cust_name) {
		$subject = "Welcome to GNB!";
		$body_txt = <<<EOTXT
Hello $cust_name!

We are pleased to welcome you at GNB.

It's gonna be legen... wait for it... dary! Legendary!

Sincerley Barney Stinson
CEO of GNB
EOTXT;
		$body_html = $this->getHTMLMail($body_txt);
		$this->sendMail($cust_addr, $cust_name, $subject, $body_txt, $body_html);
	}

	public function sendApprovalMail($cust_addr, $cust_name, $cust_tans) {
		$subject = "Your registration to GNB was approved!";
		$body_txt = <<<EOTXT
Hello $cust_name!

We are pleased to announce, that your registration to GNB was approved!
Below you'll find 100 TANs for your Bank account:
$cust_tans

It's gonna be legen... wait for it... dary! Legendary!

Sincerley Barney Stinson
CEO of GNB
EOTXT;
		$body_html = $this->getHTMLMail($body_txt);
		$this->sendMail($cust_addr, $cust_name, $subject, $body_txt, $body_html);
	}

	//PRIVATE
	private function getHTMLMail($body_txt) {
		$path = 'media/gnb_logo.png';
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

		$html_mail = <<<EOHTML
<html>
	<head>
		<style type="text/css">
			body {
				background-color: #e0ddd6;
			}
			#frame {
				width: 500px;
				margin: 0 auto;
				border-radius: 8px;
				background-color: #ffffff;
			}
			#content {
				font-family: Arial, Helvetica, sans-serif;
				white-space: pre;
				color: #454545;
				font-size: 16px;
				padding: 30px 25px 30px 24px;
				line-height: 24px;
			}
			#logo {
				display: block;
				margin: auto;
				padding: 50px 0px 0px 0px;
				width: 60%;
			}
		</style>
	</head>
	<body>
		<div id="frame">
			<img id="logo" src="$base64" alt="GNB"/>
			<div id="content">
$body_txt
			</div>
		</div>
	</body>
</html>
EOHTML;
		return $html_mail;
	}
}

$gnbmailer = new GNBMailer();
//$gnbmailer->send('florian.mauracher@tum.de', 'Florian Mauracher', "TESTMAIL", "foo", "<h1>foobar</h1>");
$gnbmailer->sendWelcomeMail('florian.mauracher@tum.de', 'Florian Mauracher');
//$gnbmailer->sendWelcomeMail('alexander.lill@tum.de', 'Alexander Lill');
