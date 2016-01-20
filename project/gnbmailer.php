<?php

require_once "resource_mappings.php";
require_once getPageAbsolute("phpmailer");

class GNBMailer {
	public $mail;

	public function __construct() {
		$this->mail = new PHPMailer();
		$this->mail->IsSMTP();
		$this->mail->Host        = "smtp.postmarkapp.com";
		$this->mail->SMTPDebug   = 0;
		$this->mail->SMTPAuth    = TRUE;
		$this->mail->SMTPSecure  = "tls";
		$this->mail->Port        = 587;
		$this->mail->Username    = "9cf4340a-59e9-4470-9f3e-3eb94d4123aa";
		$this->mail->Password    = "9cf4340a-59e9-4470-9f3e-3eb94d4123aa";
		$this->mail->Priority    = 3;
		$this->mail->CharSet     = "UTF-8";
		$this->mail->Encoding    = "8bit";
		$this->mail->From        = "goliath.nb@frcy.org";
		$this->mail->FromName    = "Barney Stinson";
		$this->mail->WordWrap    = 900;
	}

	public function send() {
		$this->mail->Send();
		$this->mail->SmtpClose();

		if ( $this->mail->IsError() ) {
			echo "<p class='simpleTextBig simple-text-centered'>Operation failed:<br />" . $this->mail->ErrorInfo . "</p>";
			return 1;
		} else {
			echo "<p class='simpleTextBig simple-text-centered'>Email was successfully sent</p>";
			return 0;
		}
	}

	public function sendMail($to, $to_name, $subject, $body_txt, $body_html="" , $tans_pdf="" ) {
		$this->mail->AddAddress($to, $to_name);
		$this->mail->Subject = $subject;
		if (!empty($tans_pdf)){
			$this->mail->addAttachment($tans_pdf) ;
		}

		if ($body_html=="") {
			$this->mail->Body = $body_txt;
		} else {
			$this->mail->isHTML(TRUE);
			$this->mail->Body = $body_html;
			$this->mail->AltBody = $body_txt;
		}
		return $this->send();
	}

	public function sendMail_Registration($cust_addr, $cust_name) {
		$subject = "Registration request for GNB received!";
		$body_txt = $this->getTemplate_Registration($cust_name);
		$body_html = $this->getHTMLMail($body_txt);

		$this->sendMail($cust_addr, $cust_name, $subject, $body_txt, $body_html);
	}

	public function sendMail_Approval($cust_addr, $cust_name, $cust_balance=0, $tans_pdf='') {
		$subject = "Welcome to GNB!";
		$body_txt = $this->getTemplate_Approval($cust_name, $cust_balance );
		$body_html = $this->getHTMLMail($body_txt);
		
		$this->sendMail($cust_addr, $cust_name, $subject, $body_txt, $body_html, $tans_pdf);
		if (!empty($tans_pdf)){
			unlink($tans_pdf) ;
		}
	}
	
	public function sendMail_PasswordReset($cust_addr,$cust_name, $cust_resetlink) {
		$subject = "GNB Password Reset";
		$body_txt = $this->getTemplate_PasswordReset($cust_name,$cust_resetlink);
		$body_html = $this->getHTMLMail($body_txt);
		
		$this->sendMail($cust_addr, $cust_name, $subject, $body_txt, $body_html );
	}

	// PRIVATE
	private function getTemplate_Registration($cust_name) {
		ob_start();
		include "templates/mail_registration.template";
		$template = ob_get_clean();
		return $template;
	}

	private function getTemplate_Approval($cust_name, $cust_balance ) {
		ob_start();
		include "templates/mail_approval.template";
		$template = ob_get_clean();
		return $template;
	}

	private function getTemplate_PasswordReset($cust_name,$cust_resetlink) {
		ob_start();
		include "templates/mail_pwrecovery.template";
		$template = ob_get_clean();
		return $template;
	}

	private function getHTMLMail($body_txt) {
		$logo_path = realpath(dirname(__FILE__)) . '/media/gnb_logo.png';
		$logo_type = pathinfo($logo_path, PATHINFO_EXTENSION);
		$logo_data = file_get_contents($logo_path);
		$logo_base64 = 'data:image/' . $logo_type . ';base64,' . base64_encode($logo_data);

		ob_start();
		include "templates/mail_html.template";
		$html_mail = ob_get_clean();
		return $html_mail;
	}
}

#$gnbmailer = new GNBMailer();
#$gnbmailer->sendMail_Approval('mahmoud.shadid@gmail.com', 'Mahmoud Naser',150,'/var/www/gnb/project/holder/gnb_5.pdf');
#$gnbmailer->sendMail_Approval('mahmoud.shadid@gmail.com', 'Mahmoud Naser',150,'');
#$gnbmailer->sendMail_Approval('florian.mauracher@tum.de', 'Florian Mauracher',150,'/var/www/gnb/project/accounts/GNB_unencrupted.pdf');
//$gnbmailer->sendMail_Approval('alexander.lill@tum.de', 'Alexander Lill');
