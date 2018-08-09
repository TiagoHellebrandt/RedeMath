<?php

require_once "class.smtp.php";
require_once "class.phpmailer.php";

class Feedback {
	private $nome;
	private $email;
	private $avalia;
	private $message;
	private $mailer;
	private $HTMLAvalia;
	private $HTMLBody;

	function __construct($nome,$email,$avalia,$message) {
		$this->setNome($nome);
		$this->setEmail($email);
		$this->setAvalia($avalia);
		$this->setMessage($message);
		$this->setHTMLBody();
	}

	public function getNome() {
		return $this->nome;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getAvalia() {
		return $this->avalia;
	}

	public function getMessage() {
		return $this->message;
	}

	private function getHTMLAvalia() {
		return $this->HTMLAvalia;
	}

	private function getHTMLBody() {
		return $this->HTMLBody;
	}

	public function getMailer() {
		return $this->mailer;
	}

	public function setNome($nome) {
		$this->nome = $nome;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function setAvalia($avalia) {
		$this->avalia = $avalia;
		$this->setHTMLAvalia();
	}

	public function setMessage($message) {
		$this->message = $message;
	}

	private function setHTMLBody() {
		$this->HTMLBody = "<table><tbody>";
		$this->HTMLBody .= "<tr><td><b>Nome:</b></td><td> ".$this->getNome()."</td></tr>";
		$this->HTMLBody .= "<tr><td><b>E-mail:</b></td> <td>".$this->getEmail()."</td></tr>";
		$this->HTMLBody .= "<tr><td><b>Avaliação:</b></td> <td>".$this->getHTMLAvalia()."</td></tr>";
		$this->HTMLBody .= "<tr><td><b>Mensagem:</b></td><td>".$this->getMessage()."</td></tr>";
		$this->HTMLBody .= "</tbody></table>";
	}

	private function setHTMLAvalia() {
		if($this->getAvalia()>1) {
			$this->avalia .= " estrelas";
		} else {
			$this->avalia .= " estrela";
		}
	}

	private function setMailer() {
		$this->mailer = new PHPMailer;
	}

	public function start() {
		$this->setMailer();
		$this->mailer->IsSMTP();
		$this->mailer->Host ="mx1.hostinger.com.br";
		$this->mailer->SMTPAuth = true;
		$this->mailer->Username = "feedback@redemath.com";
		$this->mailer->Password = "$1029384756#@";
		$this->mailer->SMTPSecure = "tls";
		$this->mailer->Port = 587;
		$this->mailer->FromName = $this->getNome();
		$this->mailer->From = "feedback@redemath.com";
		$this->mailer->AddAddress("feedback@redemath.com");
		$this->mailer->IsHTML(true);
		$this->mailer->Subject = "Feedback do aplicativo - {$Nome} ".date("H:i")." - ".date("d/m/Y");
		$this->mailer->Body = $this->getHTMLBody();
		return $this->mailer->Send();
	}
}