<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Geometria - RedeMath</title>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="shortcut icon" href="../_imagens/LogoRedeMath-icone.png" />
	<link rel="stylesheet" href="../_css/style.css" />
	<link rel="stylesheet" type="text/css" href="../_css/animate.css" />
	<link rel="stylesheet" type="text/css" href="../_css/geometria.css" />
	<script type="text/javascript" src="../_javascript/jquery.min.js"></script>
	<script type="text/javascript" src="../_javascript/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../_javascript/principal.js"></script>
	<?php $letras=range("a","z");echo "<script>var letras=[];"; for($i=0;$i<count($letras);$i++){echo "letras.push('".$letras[$i]."');";} echo "</script>"; ?>
	<script type="text/javascript" src="../_javascript/geometria.js"></script>
</head>
<body>
	<div>
		<div id="cabExe" class="animated bounceInDown">
			<ul id="tools">
				<li id="toolPan"></li>
				<li id="toolLine"></li>
				<li id="toolSelect"></li>
				<li id="toolRemove"></li>
			</ul>
			<h1>Geometria</h1>
		</div>
		<?php include_once '../header.php'; ?>
		<div id="palco"></div>
		<div id="tamanho">
			<h2>Defina o tamanho:</h2>
			<input type="number" placeholder="Tamanho" id="txtTam" min="1"><br />
			<input type="button" value="Cancelar" id="tamCnc" style="left: 7px;">
			<input type="button" value="Concluir" id="tamOk" style="right: 7px;">
		</div>
		<div id="blq"></div>
		<iframe src="../menu.html" id="orc"></iframe>
	</div>
</body>
</html>