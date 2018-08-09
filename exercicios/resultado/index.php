<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Resultado dos exercicios - RedeMath</title>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="../../_css/style.css" />
	<link rel="stylesheet" href="../../_css/animate.css" />
	<link rel="stylesheet" href="../../_css/hover-min.css" />
	<link rel="stylesheet" href="../../_css/resultado.css" />
	<link rel="shortcut icon" href="../../_imagens/LogoRedeMath-icone.png" />
	<script type="text/javascript" src="../../_javascript/jquery.min.js"></script>
	<script type="text/javascript" src="../../_javascript/principal.js"></script>
	<script type="text/javascript" src="../../_javascript/resultado.js"></script>
</head>
<body>
	<div id="cabExe" class="animated bounceInDown"><h1>Resultado</h1></div>
	<?php include_once '../header.php'; ?>

	<div id="resultado" class="animated bounceIn">
		<canvas id="desenhoAcertos" width="180" height="180"></canvas>
		<?php
			require_once "../../Calculadora.class.php";
            require_once '../../resultado.class.php';
			if(isset($_POST['t'])&&isset($_POST['n'])&&isset($_POST['r'])){
				$tempo = $_POST['t'];
				$nivel = $_POST['n'];
				$perguntas = $_POST['r'];
				$p = explode(":" , $perguntas);
				array_pop($p);
				$rtxt = [];
				for($i=0;$i<count($p);$i++){
					if(isset($_POST["questao$i"])){
						$rtxt[$i] = $_POST["questao$i"];
					}else{
						$rtxt[$i] = null;
					}
				}
			}else{
				header("location: ../");
			}
			$perguntas = str_replace("&div;", "/", $perguntas);
			$resultado = new Resultado(explode(":", $perguntas),$nivel,$tempo,$rtxt);
			$resultado->showResult();
		?>
		<input type="button" value="Estudar" id="btnEst" />
		<a href="../" id="btnExer"><input type="button" value="Exercícios" id="btnExe" /></a>
	</div>
	<div id="blq"></div>
    <iframe src="../../menu.html" id="orc"></iframe>
</body>
</html>