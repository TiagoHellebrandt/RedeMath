<?php

require_once "feedback.class.php";
if(isset($_REQUEST["callback"])&&isset($_REQUEST["nome"])&&isset($_REQUEST["email"])&&isset($_REQUEST["avalia"])&&isset($_REQUEST["message"])&&isset($_REQUEST["pass"])&&($_REQUEST["pass"]=="ZsX1CfV2")) {
	$callback = $_REQUEST["callback"];
	$nome = $_REQUEST["nome"];
	$email = $_REQUEST["email"];
	$avalia = $_REQUEST["avalia"];
	$message = $_REQUEST["message"];
	$feedback = new Feedback($nome,$email,$avalia,$message);
	$res = json_encode($feedback->start());
	echo "$callback($res)";
}