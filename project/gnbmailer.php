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
}

$gnbmailer = new GNBMailer();
$gnbmailer->sendMail('florian.mauracher@tum.de', 'Florian Mauracher', "TESTMAIL", "foo", "<h1>foobar</h1>");
