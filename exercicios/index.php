<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Monte e faça sua lista de exercícios grátis - RedeMath</title>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="../_css/style.css" />
	<link rel="stylesheet" href="../_css/animate.css" />
	<link rel="stylesheet" href="../_css/exercicios.css" />
	<link rel="stylesheet" href="../_css/hover-min.css" />
	<link rel="shortcut icon" href="../_imagens/LogoRedeMath-icone.png" />
	<script type="text/javascript" src="../_javascript/jquery.min.js"></script>
	<script type="text/javascript" src="../_javascript/principal.js"></script>
	<script type="text/javascript" src="../_javascript/exercicios.js"></script>
</head>
<body>
	<div id="cabExe" class="animated bounceInDown"><h1>Exercícios</h1></div>
	<?php include_once '../header.php'; ?>
    <div id="conteudo">
		<div id="varConfig">
			<input type="text" readonly="true" id="oprsList" name="oprs" form="frmComecar" />
			<input type="text" readonly="true" id="nvlList" name="nvl" form="frmComecar" />
			<input type="text" readonly="true" id="tmpList" name="tmp" form="frmComecar" />
		</div>
		<div id="configExe" class="animated bounceIn">
			
			<div id="configCont">
				<h2>1. Escolha os conteúdos</h2>
				<table class="configMaior">
					<tr>
						<td class="IOp"><img src="../_imagens/soma-operacoes-icone-redemath.png" alt="Soma"><br/>Soma</td>
						<td class="IOp"><img src="../_imagens/subtracao-operacoes-icone-redemath.png" alt="Subtração"><br/>Subtração</td>
						<td class="IOp"><img src="../_imagens/multiplicacao-operacoes-icone-redemath.png" alt="Multiplicação"><br/>Multiplicação</td>
						<td class="IOp"><img src="../_imagens/divisao-operacoes-icone-redemath.png" alt="Divisão"><br/>Divisão</td>
					</tr>
					<tr>
						<td><img src="../_imagens/equacao1-operacoes-icone-redemath.png" alt="Equação do 1° grau"><br/>Equação do 1°</td>
						<td><img src="../_imagens/mmc-icone-redemath-pequeno.png" alt="mmc"><br/>MMC</td>
						<td><img src="../_imagens/mdc-operacoes-icone-redemath.png" alt="mdc"><br/>MDC</td>
                                                <td><img src="../_imagens/radiciacao-operacoes-icone-redemath.png" alt="radiciacao"><br/>Radiciação</td>
					</tr>
				</table>
				<ul class="configMenor">
					<li>Soma</li>
					<li>Subtração</li>
					<li>Multiplicação</li>
					<li>Divisão</li>
					<li>Equação do 1°</li>
					<li>MMC</li>
                                        <li>MDC</li>
                                        <li>Radiciação</li>
				</ul>
			</div>
			<div id="configNivel">
				<h2>2. Escolha o nível de dificuldade</h2>
				<div class="configMaior">
					<button>Fácil</button>
					<button class="padrao">Médio</button>
					<button>Difícil</button>
				</div>
				<ul class="configMenor">
					<li>Fácil</li>
					<li class="padrao">Médio</li>
					<li>Difícil</li>
				</ul>
			</div>
			<div id="configQtd">
				<h2>3. Escolha a quantidade de questões</h2>
					<label for="qtsQtd"><span>Quantidade: </span></label><input type="number" form="frmComecar" id="qtsQtd" value="4" name="qtd" required="required" />
			</div>
			<div id="configTempo">
				<h2>4. Escolha o tempo</h2>
				<div class="configMaior">
					<button class="semTempo">Sem Tempo</button>
					<button class="comTempo" id="cTempo">Com Tempo</button>
				</div>
				<ul class="configMenor">
					<li class="semTempo">Sem Tempo</li>
					<li class="comTempo">Com Tempo</li>
				</ul>
			</div>
				<div class="balao">
					<input type="Number" id="h" maxlength="2" min="0" max="60" title="Horas" />
					:<input type="Number" id="min" maxlength="2" min="0" max="60" title="Minutos" />
					:<input type="Number" id="s" maxlength="2" min="0" max="60" title="Segundos" />
				</div>

			<form action="lista/" method="post" id="frmComecar">
				<input type="submit" value="Começar" class="hvr-grow-shadow" />
			</form>
		</div>
	</div>
	<div id="blq"></div>
    <iframe src="../menu.html" id="orc"></iframe>
</body>
</html>