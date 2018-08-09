<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>RedeMath</title>
        <link rel="stylesheet" type="text/style" href="_css/style.css" />
        <link rel="stylesheet" type="text/style" href="_css/resultados.css" />
        <link rel="shortcut icon" href="_imagens/LogoWebMath-icone.png" />
        <script language="javascript" src="_javascript/principal.js"></script>
    </head>
    <body>
        <div id="conteudo">
        <div id="resultado">
            <h1>Resultado:</h1><div id="eqcres">
            <?php
                include 'expressao.php';
                $ex = $_GET["eqc"];
                showEquacao1($ex);
            ?>
                </div></div>
        <iframe src="operacoes.html" onmouseover="abrirOp();" onmouseout="fecharOp();" id="orc"></iframe>
        </div>
    </body>
</html>
