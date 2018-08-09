<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>MathBook</title>
    <link rel="stylesheet" type="text/style" href="_css/style.css" />
    <link rel="stylesheet" type="text/style" href="_css/resultados.css" />
    <link rel="shortcut icon" href="_imagens/LogoWebMath-icone.png" />
    <script language="javascript" src="_javascript/principal.js"></script>
    
    <?php
                $n1 = isset($_GET["n1"])?$_GET["n1"]:"";
                $n2 = isset($_GET["n2"])?$_GET["n2"]:"";
                $inverte = false;
                if($n1<$n2){
                    $res = $n2 - $n1;
                    $inverte = true;
                }else{
                    $res = $n1 - $n2;
                }
                if($n1 == "" || $n2 == ""){
                    echo "<script>history.go(-1);</script>";
                }
                $maior;
                if((strlen($n1) > strlen($n2)) && strlen($n1) > strlen($res)){
                    $maior = strlen($n1);
                }else if((strlen($n2) > strlen($n1)) && strlen($n2) > strlen($res)){
                    $maior = strlen($n2);
                }else if(strlen($n1) == strlen($n2)){
                    $maior = strlen($n1);
                }
                else {
                    $maior = strlen($res);
                }
    ?>
    
    <style>
        table#tabres {
            width: <?php echo 19*$maior."px"; ?>;
        }
    </style>
</head>
	
<body>
    <header id="cabecalho">
	<nav id="menu">
            <img src="_imagens/Operacoes-mathbook.png" onmouseover="abrirOp();" />
	</nav>
    </header>
    <iframe src="operacoes.html" onmouseover="abrirOp();" onmouseout="fecharOp();" id="orc"></iframe>
    
    <div id="conteudo">
    <div id="resultado">
        <h1>Resultado:</h1>
        <table id="tabres">
            <tr><td rowspan="3">-</td></tr>
            <?php
                echo "<tr>";
                
                $a = str_split($n1);
                $b = str_split($n2);
                for($i=0;$i+(($inverte)?strlen($n2):strlen($n1))<$maior;$i++){
                    echo "<td></td>";
                }
                
                for($i=0;$i<(($inverte)?strlen($n2):strlen($n1));$i++){
                    echo "<td>";
                    echo ($inverte)?$b[$i]:$a[$i];
                    echo "</td>";
                }
                
                echo "</tr><tr>";
                
                
                for($i=0;$i+(($inverte)?strlen($n1):strlen($n2))<$maior;$i++){
                    echo "<td class='ln2'></td>";
                }
                
                for($i=0;$i<(($inverte)?strlen($n1):strlen($n2));$i++){
                    echo "<td class='ln2'>";
                    echo ($inverte)?$a[$i]:$b[$i];
                    echo "</td>";
                }
                echo "</tr><tr><td></td>";
                
                $c = str_split($res);
                for($i=0;$i+count($c)<$maior;$i++){
                    array_unshift($c, "0");
                }
                for($i=0;$i<count($c);$i++){
                    echo "<td>$c[$i]</td>";
                }
                
                echo "</tr></table>";
                if($inverte){
                    echo "<div id='dclc'><span class='clc'>";
                    echo $res ." (-1)<br />";
                    $res *= -1;
                    echo $res."</span></div>";
                }
            ?>
        <a href="subtracao.php" class="voltar">Voltar</a>
    </div>
    </div>
</body>

</html>