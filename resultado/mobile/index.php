<?php

require_once "../../Calculadora.class.php";

if(isset($_REQUEST["callback"])&&isset($_REQUEST["ex"])&&isset($_REQUEST["pass"])&&$_REQUEST["pass"]=="Q9w1E8r2") {
	$callback = $_REQUEST["callback"];
	$ex = $_REQUEST["ex"];
	$calculadora = new Calculadora($ex);
	$result["res"] = $calculadora->showCalc();
	$result = json_encode($result);
	echo "$callback($result)";
}