<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>RedeMath</title>
    <link rel="stylesheet" type="text/style" href="_css/style.css" />
    <link rel="stylesheet" type="text/style" href="_css/resultados.css" />
    <link rel="stylesheet" type="text/css" href="_css/divisao.css" />
    <link rel="shortcut icon" href="_imagens/LogoWebMath-icone.png" />
    <script language="javascript" src="_javascript/principal.js"></script>
    <?php
                include "funcoes.php";
                $n1 = isset($_GET["n1"])?$_GET["n1"]:"";
                $n2 = isset($_GET["n2"])?$_GET["n2"]:"";
                $rn1 = "";
                $resto = "";
                $ns = "";
                $adz = false;
                $a = str_split($n1);
                $b = str_split($n2);
                $res = $n1 / $n2;
                $r = 0;
                $c = is_int($res)?str_split($res):str_split(number_format($res,"3"));
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
            <tr>
                <td>
                <table class="tabint">
                    <tr>
                        <td>
                            <?php
                                // Verifica a divisao
                                while (conStr($a) < conStr($b)){
                                    $a[] = "0";
                                }

                                // Exibe numerador
                                echo "<tr>";
                                for($i=0;$i<count($a);$i++){
                                    echo "<td>$a[$i]</td>";
                                }
                                echo "</tr>";
                                
                                $y = 0;
                                $k = 0;
                                while($k < strlen($res)){
                                    if($y==0){
                                        for($i=0;$rn1<$n2;$i++){
                                            $rn1 .= $a[$i];
                                        }
                                    }
                                    
                                    echo "<tr>";
                                    
                                    $r .= numMult($n2, $rn1);
                                    $ns = $n2 * numMult($n2, $rn1);
                                    $vns = str_split($ns);
                                    if($y!=0){
                                        for($i=0;$i+count($vns)<count($vrn1);$i++){
                                            echo "<td></td>";
                                        }
                                    }
                                    for($i=0;$i<count($vns);$i++){
                                        echo "<td class='beb'>$vns[$i]</td>";
                                    }
                                    
                                    echo "</tr><tr>";
                                    
                                    $rn1 -= $ns;
                                    $vrn1 = str_split($rn1);
                                    $eb = 0;
                                    while(count($vrn1)<count($vns)){
                                        array_unshift($vrn1, "0");
                                        $eb++;
                                    }
                                    
                                    if(strlen($n1)>count($vrn1)+$y){
                                        $vrn1[] = substr($n1,count($vrn1),1);
                                        if(substr($n1,count($vrn1),1)==0){
                                            $r .= "0";
                                            $k--;
                                            if(substr($n1,count($vrn1)+1,1)==0 && count($vrn1)+1<=strlen($n1)){
                                                for($i=0;(substr($n1,count($vrn1)+$i,1)==0) && (count($vrn1)+$i<=strlen($n1));$i++){
                                                    $r .= "0";
                                                    $vrn1[] = "0";
                                                    $k--;
                                                }
                                            }
                                        }
                                        $adz = true;
                                    }
                                    
                                    if($y!=0){
                                        for($i=0;$i+$eb<count($vns);$i++){
                                            echo "<td></td>";
                                        }
                                    }
                                    for($i=0;$i<count($vrn1);$i++){
                                        echo "<td>$vrn1[$i]</td>";
                                    }
                                    $rn1 = conStr($vrn1);
                                    echo "</tr>";
                                    $y++;
                                    $k++;
                                }
                                
                                
                            ?>
                        </td>
                    </tr>
                </table>
                </td>
                <td>
                   <table class="tabint">
                <tr>
                    <td>
                        <?php
                            // Exibe o denominador
                            echo "<tr>";
                            for($i=0;$i<count($b);$i++){
                                if($i==0){
                                    echo "<td id='apb'>$b[$i]</td>";
                                }  else {
                                    echo "<td>$b[$i]</td>";
                                }
                            }
                            echo "</tr>";
                            
                            // Exibe resultado
                            echo "<tr>";
                            for($i=0;$i<count($c);$i++){
                                echo "<td class='rbt'>$c[$i]</td>";
                            }
                            if(count($b)>count($c)){
                                for($i=0;$i-count($c)<count($b);$i++){
                                    echo "<td class='rbt'></td>";
                                }
                            }
                            echo "</tr>";
                        ?>
                    </td>
                </tr>
            </table> 
              </td>
            </tr>
        </table>
        
        
    </div>
    </div>
</body>

</html>