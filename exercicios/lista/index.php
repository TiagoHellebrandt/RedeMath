<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Lista de exercicios - RedeMath</title>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="../../_css/style.css" />
	<link rel="stylesheet" href="../../_css/animate.css" />
	<link rel="stylesheet" href="../../_css/hover-min.css" />
	<link rel="stylesheet" href="../../_css/lista.css" />
	<link rel="shortcut icon" href="../../_imagens/LogoRedeMath-icone.png" />
	<link rel="stylesheet" type="text/css" href="../../_css/radiciacao.css" />
	<script type="text/javascript" src="../../_javascript/jquery.min.js"></script>
	<script type="text/javascript" src="../../_javascript/principal.js"></script>
	<script type="text/javascript" src="../../_javascript/lista.js"></script>
	<script type="text/javascript" src="../../_javascript/listaTempo.js"></script>
</head>
<body>
	<div id="cabExe" class="animated bounceInDown">
		<img src="../../_imagens/tempo.png" />
		<span>00:00:00</span>
		<h1>Exercícios</h1>
		<form method="post" action="../resultado/" id="formCorrige">
			<input type="text" id="respostas" name="r" readonly="true" />
			<input type="text" id="tm" name="t" readonly="true" />
			<input type="text" id="nv" name="n" readonly="true" />
		</form>
	</div>
	<?php include_once '../header.php'; ?>
    <div id="conteudo">
    	<div id="barra"><div id="barraProgresso"></div></div>

	    <?php
                require_once '../../Exercicios.class.php';
	    	require_once "listaexe.php";
	    	if(isset($_POST['oprs'])&&isset($_POST['nvl'])&&isset($_POST['qtd'])&&isset($_POST['tmp'])){
                    $ops = $_POST['oprs'];
                    $nivel = $_POST['nvl'];
                    $qtd = $_POST['qtd'];
                    $tempo = $_POST['tmp'];
	    	}else{
                    header ("location: ../");
	    	}
                $exercicios = new Exercicios($ops, $nivel, $qtd);
	    	$exercicios->show();
	    	if($tempo==""){
	    		echo "<script>setTempo(0,0,0);</script>";
	    	}else{
	    		$orgTempo = explode(":", $tempo);
	    		echo "<script>setTempo(".$orgTempo[0].",".$orgTempo[1].",".$orgTempo[2].");</script>";
	    	}
	    	echo "<script type='text/javascript'>setProgresso(".(100/$qtd).");</script>";
	    ?>
        <input type="submit" value="Proximo" form="formCorrige" id="avancar" />
    </div>
    <div id="blq"></div>
    <iframe src="../../menu.html" id="orc"></iframe>
</body>
</html>