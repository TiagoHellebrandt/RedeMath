<?php
	$para = "thellebrandtsilva@gmail.com";
	$assunto = "Reletorio de erro na RedeMath";
	$ex = $_REQUEST["ex"];
	$nome = $_REQUEST["nome"];
	$email = $_REQUEST["email"];
	$msg = $_REQUEST["msg"];
	$corpo = "<strong>Reletorio de erro na RedeMath</strong><br /><br />";
	$corpo .= "<strong>Expressão: </strong> $ex<br />";
	$corpo .= "<strong>Nome: </strong> $nome<br />";
	$corpo .= "<strong>E-mail: </strong> $email<br />";
	$corpo .= "<strong>Comentário: </strong> $msg";
	$header = "Content-Type: text/html; charset= utf-8\n";
	$header .= "From: $email Reply-to: $email\n";

	mail($para,$assunto,$corpo,$header);
	echo "<script type='text/javascript'>window.history.go(-1);</script>";
?>