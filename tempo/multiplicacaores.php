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
                if(strlen($_GET["n1"]) >= strlen($_GET["n2"])){
                    $n1 = isset($_GET["n1"])?$_GET["n1"]:"";
                    $n2 = isset($_GET["n2"])?$_GET["n2"]:"";
                }else{
                    $n1 = isset($_GET["n2"])?$_GET["n2"]:"";
                    $n2 = isset($_GET["n1"])?$_GET["n1"]:"";
                }
                $res = $n1 * $n2;
                $a = str_split($n1);
                $b = str_split($n2);
                $c = str_split($res);
                $r;
                $sn1 = 0;
                $sn2 = 0;
                $smaior;
                for ($i=0;$i<strlen($n2);$i++){
                    $r[$i] = (($b[strlen($n2)-1-$i])*$n1);
                    $zeros = "";
                    for ($j=0;$j!=$i;$j++){
                        $zeros = $zeros."0";
                    }
                    if($r[$i]==0){
                        for($j=0;$j<strlen($n1);$j++){
                            $zeros = $zeros."0";
                        }
                        $r[$i] = $zeros;
                    }else{
                        $r[$i] = $r[$i].$zeros;
                    }
                }
                
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
    <iframe src="operacoes.html" onmouseover="abrirOp();" onmouseout="fecharOp();" id="orc"></iframe>
    <div id="conteudo">
        <div id="resultado">
        <h1>Resultado:</h1>
        <table id="tabres">
            <tr><td rowspan="3">x</td></tr>
            <?php
                echo "<tr>";
                
                for($i=0;$i+strlen($n1)<$maior;$i++){
                    echo "<td></td>";
                    $sn1++;
                }
                    
                for($i=0;$i<strlen($n1);$i++){
                    echo "<td>$a[$i]</td>";
                    $n1++;
                }
                echo "</tr><tr>";
                
                
                for($i=0;$i+strlen($n2)<$maior;$i++){
                    echo "<td class='ln2'></td>";
                    $sn2++;
                }
                    
                for($i=0;$i<strlen($n2);$i++){
                    echo "<td class='ln2'>$b[$i]</td>";
                    $sn2++;
                }
                echo "</tr><tr><td></td>";
                
                if($sn1>$sn2){
                    $smaior = $sn1;
                }else{
                    $smaior = $sn2;
                }
                
                for($i=0;$i<=(count($r)-1);$i++){
                        echo "<tr><td></td>";
                        $rr = str_split($r[$i]);
                        if(strlen($r[$i])<$smaior){
                            for($h=0;$h+strlen($r[$i])<$smaior;$h++){
                                echo "<td></td>";
                            }
                        }
                        
                        for($j=0;$j<strlen($r[$i]);$j++){
                            echo "<td>$rr[$j]</td>";
                        }
                        echo "</tr>";
                }
                echo "</tr><tr><td></td>";
                if(strlen($n2)!=1){
                    for($i=0;$i<strlen($res);$i++){
                        echo "<td class='brf'>$c[$i]</td>";
                    }
                }
                
                echo "</tr>";
            ?>
        </table>
    </div>
    </div>
</body>

</html>