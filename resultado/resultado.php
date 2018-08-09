<div id="resultado">
    <img src="../_imagens/semsolucaoIcon.png" id="lampada" style="position:absolute;" draggable="false" />
    <!-- <input id="btn-err" type="button" value="Certo?" /> --><h1>Solução</h1>
    <img src="../_imagens/infoIcon.png" id="btnInfo" draggable="false" />
    <div id="res">
    <?php
        require_once '../Calculadora.class.php';
        if(isset($_GET["ex"])){
            $ex = $_GET["ex"];
        }else{
            header("location: ../calculadora");
        }
        $calculadora = new Calculadora($ex);
        echo $calculadora->showCalc();
    ?>
    <script>
        if(lampada){
            $("#lampada").attr("src","../_imagens/solucaoIcon.png");
        }
    </script>
        <div id="footerResultado">
            <a href="../calculos"><button>Conheça todos cálculos</button></a>
            <a href="../exercicios"><button>Treine agora</button></a>
        </div>
    </div>
</div>
