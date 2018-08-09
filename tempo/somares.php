<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>RedeMath</title>
    <link rel="stylesheet" type="text/style" href="_css/style.css" />
    <link rel="stylesheet" type="text/style" href="_css/resultados.css" />
    <link rel="shortcut icon" href="_imagens/LogoWebMath-icone.png" />
    <script language="javascript" src="_javascript/principal.js"></script>
    <?php
                $n1 = isset($_GET["n1"])?$_GET["n1"]:"";
                $n2 = isset($_GET["n2"])?$_GET["n2"]:"";
                $res = $n1 + $n2;
                $maior;
                
                if((strlen($n1) > strlen($n2)) && strlen($n1) > strlen($res)){
                    $maior = strlen($n1);
                }else if((strlen($n2) > strlen($n1)) && strlen($n2) > strlen($res)){
                    $maior = strlen($n2);
                }  else {
                    $maior = strlen($res);
                }
                
                
                
                if($n1 == "" || $n2 == ""){
                    echo "<script>history.go(-1);</script>";
                }
    ?>
    <style>
        table#tabres {
            width: <?php echo 19*$maior."px"?>;
        }
    </style>
</head>
	
<body>
    <header id="cabecalho">
	<nav id="menu">
            <img src="_imagens/Operacoes-mathbook.png" onmouseover="abrirOp();" />
	</nav>
    </header>
    <div id="conteudo">
        <div id="resultado">
        <h1>Resultado:</h1>
        <table id="tabres">
            <tr><td rowspan="3">+</td></tr>
            <?php
                echo "<tr>";
                
                $a = str_split($n1);
                for($i=0;$i+strlen($n1)<$maior;$i++){
                    echo "<td></td>";
                }
                    
                for($i=0;$i<strlen($n1);$i++){
                    echo "<td>$a[$i]</td>";
                }
                echo "</tr><tr>";
                
                $b = str_split($n2);
                for($i=0;$i+strlen($n2)<$maior;$i++){
                    echo "<td class='ln2'></td>";
                }
                    
                for($i=0;$i<strlen($n2);$i++){
                    
                    echo "<td class='ln2'>$b[$i]</td>";
                }
                echo "</tr><tr><td></td>";
                
                $c = str_split($res);
                for($i=0;$i<strlen($res);$i++){
                    echo "<td>$c[$i]</td>";
                }
                
                echo "</tr>";
            ?>
        </table>
        <a href="soma.php" class="voltar">Voltar</a>
    </div>
        <iframe src="operacoes.html" onmouseover="abrirOp();" onmouseout="fecharOp();" id="orc"></iframe>
    </div>
</body>

</html>