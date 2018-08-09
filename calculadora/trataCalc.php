<?php

if(isset($_POST["e"])&&isset($_POST["ae"])) {
    require_once "TrataCalc.class.php";
    $trataCalc = new TrataCalc($_POST["e"],$_POST["ae"]);
    $trataCalc->show();
}