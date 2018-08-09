<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Aprenda a como calcular Equação do 2° grau - RedeMath</title>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="stylesheet" type="text/css" href="../../_css/style.css" />
        <link rel="stylesheet" type="text/css" href="../../_css/calculos.css" />
        <link rel="shortcut icon" href="../../_imagens/LogoRedeMath-icone.png" />
        <link rel="stylesheet" href="../../_css/animate.css" />
        <link rel="stylesheet" type="text/css" href="../../_css/fracao.css" />
        <script type="text/javascript" src="../../_javascript/jquery.min.js"></script>
        <script language="javascript" src="../../_javascript/principal.js"></script>
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
        <div id="cabExe" class="animated bounceInDown"><h1>Equação do 2º grau</h1></div>
        <?php include_once '../header.php'; ?>
        <div id="lateral-esq">
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- lateral esq equacao1 -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-1599113724199588"
                 data-ad-slot="1542467152"
                 data-ad-format="auto"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
    <?php include_once "../../expressao.php"; ?>
    <div id="conteudo">
        <section id="artigo">
            <article>
                <h2>O que é?</h2>
                <br />
                <p>
                    A equação do 2º grau é uma expressão matemática que possui as seguintes características:
                    <ul>
                        <li>Uma variável.</li>
                        <li>Sinal de igualdade.</li>
                        <li>Sentença fechada.</li>
                        <li>Operações.</li>
                        <li>Maior expoente 2(DOIS).</li>
                        <li>Ser igual a 0(ZERO).</li>
                    </ul>
                </p>
                <p>Onde o objetivo é encontrar o valor da incógnita(variável).</p>
                <p>
                    Exemplo: 3x²+5=0.
                </p>
                <br />
                <h2>Variável</h2>
                <br />
                <p>
                    A variável ou incógnita é 
                    representada por uma letra qualquer,
                    e está letra pode ter um coeficiente.
                    O coeficiente é um numero que está multiplicando a variável.
                </p>
                <br />
                <h2>Forma genérica</h2>
                <img src="../../_imagens/formaGeral_equacao2.png" id="imgFormaGeral" />
                <br />
                <h2>Formulas</h2>
                <p>Para efetuar o calculo de uma equação do 2° grau é necessário utilizarmos duas formulas:</p>
                <br />
                <table class="formulas"><tr class="frlTtl"><td>Formula do Delta</td></tr> <tr><td>&Delta; = b²-4*a*c</td></tr></table>
                <br />
                <table class="formulas"><tr class="frlTtl"><td>Formula de Baskara</td></tr><tr><td><table><tr><td>x=</td><td><?php echo showFracao("-b&plusmn;&radic;&Delta;","2*a");?></td></tr></table></td></tr></table>
                <br />
                <br />
                <h2>Como calcular?</h2>
                <br />
                <p>
                    Para calcular uma equação do 2° grau é necessário seguir alguns passos:
                </p>
                <br />
                <ol>
                    <li>Organizar</li>
                        <ul type="none">
                            <li>
                                <p>
                                    Para organizar a equação, devemos 
                                    deixar todos os termos de um lado da igualdade, assim igualando-a a 0(ZERO).
                                </p>
                                <p>
                                    Exemplo: x²-5x+4=3x-8 &rarr; x²-5x+4-3x+8=0
                                </p>
                            </li>
                        </ul>
                    <li>Somar os semelhantes</li>
                        <ul type="none">
                            <li>
                                <p>
                                    Após organizar a equação devemos somar todos os termos semelhantes.
                                </p>
                                <p>
                                    Exemplo: x²-5x+4-3x+8=0 &rarr; x²-8x+12=0
                                </p>
                            </li>
                        </ul>
                    <li>Calcular o Delta (&Delta;)</li>
                        <ul type="none">
                            <li>
                                <p>
                                    Após somar os semelhantes, devemos calcular o Delta, pois utilizaremos na formula de Baskara.
                                </p>
                                <p>
                                    Utilizando a formula do Delta (&Delta;=b²-4*a*c),encontrada acima, substituímos as letras A,B e C pelos coeficientes das mesmas, efetuando a expressão gerada pela substituição dos coeficientes encontra-se o valor do Delta.

                                </p>
                                <p>
                                    Caso o valor de Delta for maior que 0(ZERO) a equação apresenta duas soluções reais e diferentes. Se o valor de Delta for igual a 0(ZERO) a equação apresenta duas raízes reais e iguais. Caso o valor de Delta for menor que 0(ZERO) a equação não apresenta soluções reais.
                                </p>
                                <p>
                                    Exemplo: <b>1</b>x²<b>-8</b>x<b>+12</b>=0 &rarr; (A=1,B=-8,C=12) &rarr; &Delta;=<b>b</b>²-4*<b>a</b>*<b>c</b> &rarr; &Delta;=<b>-8</b>²-4*<b>1</b>*<b>12</b> &rarr; &Delta;=64-4*1*12 &rarr; <b>&Delta;=16</b>
                                </p>
                            </li>
                        </ul>
                        <li>Calcular raízes</li>
                            <ul type="none">
                                <li>
                                    <p>
                                        Após calcular o Delta, utilizando a formula de Baskara, substituímos Delta e os coeficientes por seus valores.
                                    </p>
                                    <p>
                                        Depois da substituição dos valores existirá duas raízes o x<sub>1</sub> e o x<sub>2</sub> uma somará e a outra subtrairá.
                                    </p>
                                    <p>
                                        Exemplo: <table><tr><td>x=</td><td><?php echo showFracao("-b&plusmn;&radic;&Delta;","2*a");?></td><td>&rarr;</td><td>x=</td><td><?php echo showFracao("-(-8)&plusmn;&radic;16","2*1");?></td><td>&rarr;</td><td>x=</td><td><?php echo showFracao("8&plusmn;4","2");?></td></tr></table>
                                        <table><tr><td>x<sub>1</sub>=</td><td><?php echo showFracao("8+4","2"); ?></td><td>&rarr;</td><td>x<sub>1</sub>=</td><td><?php echo showFracao("12","2"); ?></td><td>&rarr;</td><td>x<sub>1</sub>=6</td></tr></table>
                                        <table><tr><td>x<sub>2</sub>=</td><td><?php echo showFracao("8-4","2"); ?></td><td>&rarr;</td><td>x<sub>2</sub>=</td><td><?php echo showFracao("4","2"); ?></td><td>&rarr;</td><td>x<sub>2</sub>=2</td></tr></table>
                                    </p>
                                </li>
                            </ul>
                </ol>
            </article>
        </section>
        <div id="blq"></div>
        <iframe src="../../menu.html" id="orc"></iframe>
    </div>
    </body>
</html>
