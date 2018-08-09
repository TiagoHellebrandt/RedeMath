<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>Resultado - RedeMath</title>
        <link rel="stylesheet" type="text/css" href="../_css/style.css" />
        <link rel="stylesheet" type="text/css" href="../_css/resultados.css" />
        <link rel="shortcut icon" href="../_imagens/LogoRedeMath-icone.png" />
        <link rel="stylesheet" href="../_css/animate.css" />
        <link rel="stylesheet" href="../_css/fracao.css" />
        <link rel="stylesheet" href="../_css/divisao.css" />
        <script type="text/javascript" src="../_javascript/jquery.min.js"></script>
        <script language="javascript" src="../_javascript/principal.js"></script>
        <script type="text/javascript" src="../_javascript/resultados.js"></script>
        <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-68775298-1', 'auto');
  ga('send', 'pageview');

</script>
    </head>
    <body>
    <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.5";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
        <?php include_once '../header.php'; ?>
        <div id="conteudo">
        <div id="resultado">
            <a href="../calculadora"><div id="voltar"></div></a>
            <input id="btn-err" type="button" value="Certo?" /><h1>Solução</h1><div id="fblike"><div class="fb-like" id="curtiecomp" data-href="https://www.facebook.com/redemath" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div><div class="fb-like" id="curti" data-href="https://www.facebook.com/redemath" data-layout="button" data-action="like" data-show-faces="true" data-share="false"></div></div>
            <div id="res">
            <?php
                require_once '../calculadora.php';
                if(isset($_GET["ex"])){
                    $ex = $_GET["ex"];
                }else{
                    header("location: ../calculadora");
                }
                $EX = $ex;
                if(substr_count($ex, "\"")==0){
                    $ex = str_replace("–", "-", $ex);
                    $ex = str_replace("÷", "/", $ex);
                    $ex = str_replace(":", "/", $ex);
                    $ex = str_replace("×", "*", $ex);
                    $ex = str_replace("·", "*", $ex);
                    $ex = str_replace(",", ".", $ex);
                }
                $ex = elevadoa2(sepElements($ex,"="));
                $ex = str_replace(icognita($ex)."<sup>2</sup>", icognita($ex).icognita($ex), $ex);
                $ex = sees($ex);
                switch(typeEx($ex)){
                    case "eqc1":
                        if(eqc1Valida($ex)==true) {
                            showEquacao1($ex);
                        }else {
                            echo "Sem solução";
                        }
                    break;
                    case "eqc2":
                        showEquacao2($ex);
                    break;
                    case "soma":
                        somaCalc(sepElements($ex, "=")[0], sepElements($ex, "=")[1]);
                    break;
                    case "subtracao":
                        subCalc(sepElements($ex, "=")[0], sepElements($ex, "=")[1]);
                    break;
                    case "exp":
                        echo $ex."=<br /><span id='exNum'>";
                        echo expressaoCalc($ex);
                        echo "</span>";
                    break;
                    case 'expNum':
                        echo "$ex<br />";
                        expressaoNumerica($ex);
                    break;
                    case "mult":
                        echo sepElements($ex, "=")[0]."×".sepElements($ex, "=")[2]."=".sepElements($ex, "=")[0]*sepElements($ex, "=")[2]."<br /><br />";
                        multCalc(sepElements($ex, "=")[0], sepElements($ex, "=")[2]);
                    break;
                    case "div":
                    echo sepElements($ex, "=")[0]."&div;".sepElements($ex, "=")[2]."=".sepElements($ex, "=")[0]/sepElements($ex, "=")[2]."<br /><br />";
                        divCalc(sepElements($ex, "=")[0],sepElements($ex, "=")[2]);
                    break;
                    case "distbv":
                        echo $ex."<br />";
                        echo str_replace("<sup>1</sup>", "", distributiva($ex))."<br />";
                        echo str_replace("<sup>1</sup>", "", somaSemelhantes(distributiva($ex)));
                    break;
                    case 'mmc':
                        echo "mmc($ex)=".mmc($ex);
                        showMmc($ex);
                    break;
                    case 'mdc':
                        $ex = str_replace("|", "", $ex);
                        echo "mdc($ex)=".mdc($ex);
                        showMdc($ex);
                    break;
                    case 'raizQrd':
                        $ex = str_replace("√", "", $ex);
                        echo showRaiz2($ex);
                        echo "<br /><br />";
                        raizQuadrada($ex);
                    break;
                    case 'rga3smp':
                        showRgr3sp($ex);
                    break;
                    case 'juros':
                        showJuros($ex);
                    break;
                    case 'iqc1':
                        showInequacao1($ex);
                    break;
                    case 'nhm':
                        echo "<script type='text/javascript'>cancelInfoLike();</script>";
                        echo "<table id='altTbl'><tr><td><img src='../_imagens/alerta.png' /></td><td><h2>Expressão invalida!</h2></td></tr></table>";
                    break;
                }
            ?>
                <div id="footerResultado">
                    <a href="../calculos"><button>Conheça todos cálculos</button></a>
                    <a href="../exercicios"><button>Treine agora</button></a>
                </div>
            </div>
        </div>
            <div id="bloco-err">
                <h1>Ajude a melhorar</h1>
                <p>Se você encontrou algum problema no calculo clique no botão “Enviar” para que o mesmo seja corrigido.</p>
                <p style="background-color: #f3f3f3;">Caso preencher o formulário abaixo, ao termino do correção do problema você será notificado(a).</p>
                <div id="eml-inv-err">E-mail invalido!</div>
                <form method="get" action="../err" id="form-err">
                    <input type="text" name="nome" placeholder="Nome" id="nm-err" />
                    <input type="text" name="email" placeholder="E-mail" id="eml-err" />
                    <textarea name="msg" placeholder="Comentário" id="msg-err"></textarea>
                    <input type="text" name="ex" id="ex-err" readonly="true" value="<?php echo $EX;?>" />
                    <input type="button" value="Fechar" id="cnl-err" />
                    <input type="submit" value="Enviar" id="evr-err" />
                </form>
            </div>
        <div id="blq"></div>
        <iframe src="../menu.html" id="orc"></iframe>
        </div>
    </body>
</html>
