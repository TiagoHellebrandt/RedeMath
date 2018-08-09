<?php

require_once 'Calculadora.class.php';

class Resultado extends Calculadora {
    private $corretas; // Questões corretas
    private $incorretas; // Questões incorretas
    private $nulas; // Questões não respondidas
    private $nota; // Porcentagem de questões corretas
    private $tempo; // Tempo
    private $nivel; // Nivel de dificuldade
    private $perguntas; // Pergunatas
    private $respostas; // Respostas
    private $gabarito; // Respostas do usuario
    private $num; // Numero de perguntas
    private $angulo; // Angulo de acordo com a nota

    function __construct() {
        if(isset(func_get_args()[0])){
            $this->setPerguntas(func_get_arg(0));
        }
        if(isset(func_get_args()[1])){
            $this->setNivel(func_get_arg(1));
        }
        if(isset(func_get_args()[2])){
            $this->setTempo(func_get_arg(2));
        }
        if(isset(func_get_args()[3])){
            $this->setGabarito(func_get_arg(3));
        }
        $this->corrigir();
    }

    function getTempo() {
        return $this->tempo;
    }

    function getNivel() {
        return $this->nivel;
    }

    function getPerguntas() {
        return $this->perguntas;
    }
    
    function getCorretas() {
        return $this->corretas;
    }

    function getIncorretas() {
        return $this->incorretas;
    }

    function getNulas() {
        return $this->nulas;
    }

    function getNota() {
        return $this->nota;
    }

    function getGabarito() {
        return $this->gabarito;
    }
    
    private function setTempo($tempo) {
        $this->tempo = $tempo;
    }

    private function setNivel($nivel) {
        $this->nivel = $nivel;
    }

    private function setPerguntas($perguntas) {
        $this->perguntas = $perguntas;
        $this->setNum();
    }
    
    private function setGabarito($gabarito) {
        $this->gabarito = $gabarito;
    }
    
    private function setNum() { // Define o numero de perguntas
        $this->num = count($this->getPerguntas())-1;
    }
    
    public function getNum() {
        return $this->num;
    }
    
    private function setRespostas() { // Calcula as respostas
        for($i=0;$i<$this->getNum();$i++){
            $calculadora = new Calculadora($this->getPerguntas()[$i]);
            $this->respostas[] = $calculadora->getResult();
        }
    }
    
    public function getRespostas() {
        return $this->respostas;
    }

    private function setNota() { // Define a nota de acordo com seus atributos
        $this->nota = floor(($this->getCorretas()*100)/$this->getNum());
    }
    
    private function setAngulo() { // Define angulo
        $this->angulo = (2*$this->getNota())/100;
    }
    
    function getAngulo() {
        return $this->angulo;
    }

        
    public function corrigir() {
        $this->corretas = 0;
        $this->incorretas = 0;
        $this->nulas = 0;
        if($this->perguntas&&$this->gabarito) {
            $this->setRespostas();
            for($i=0;$i<$this->getNum();$i++){
                if(isset($this->getGabarito()[$i])&&$this->getGabarito()[$i]==$this->getRespostas()[$i]&&$this->getGabarito()[$i]!=""){
                    $this->corretas++;
                }else if(($this->getPerguntas()[$i]=="")||(!isset($this->getGabarito()[$i]))){
                    $this->nulas++;
                }else{
                    $this->incorretas++;
                }
            }
            $this->setNota();
            $this->setAngulo();
        }
    }
    
    private function formatNivel() { // Formata o nivel
        $this->setNivel(str_replace("facil", "Fácil", $this->getNivel()));
        $this->setNivel(str_replace("medio", "Médio", $this->getNivel()));
        $this->setNivel(str_replace("dificil", "Difícil", $this->getNivel()));
    }
    
    public function showResult() { // Exibe resultado
        $this->formatNivel();
        echo "<script>
                        var canvas = document.getElementById('desenhoAcertos');
                        var ctx = canvas.getContext('2d');
                        var txt = canvas.getContext('2d');
                        ctx.beginPath();
                        var angulo = 0;
                        var nota = 0;
                        ctx.lineWidth = 5;
                        ctx.lineCap = 'round';
                        txt.font = '45px sans-serif';
                        txt.textAlign = 'center';
                        txt.verticalAlign = 'middle';
                        function loop(){
                        	if(angulo<=".$this->getAngulo()."){
                        	ctx.fillStyle = '#fff';
                        	ctx.strokeStyle = '#fff';
                        	ctx.rect(0,0,canvas.width,canvas.height);
                        	ctx.fill();
                        	ctx.stroke();
                        	ctx.beginPath();
                        	txt.fillStyle = '#90ee90';
                        	ctx.strokeStyle = '#90ee90';
                        	ctx.arc(90,90,75,-0.5*Math.PI,(angulo*Math.PI)-(0.5*Math.PI),false);
                        	ctx.stroke();
                        	txt.fillText(nota+'%',canvas.width/2,canvas.height/2+15);
                        	nota++;
                        	angulo = (2*nota)/100;
                        	requestAnimationFrame(loop);
                        	}
                        }
                        loop();
                </script>
                <table id='infoRes'>
                        <tr>
                                <td class='tdNome'>Numero de questões</td>
                                <td class='tdInfo'>".$this->getNum()."</td>
                        </tr>
                        <tr>
                                <td class='tdNome'>Questões corretas</td>
                                <td class='tdInfo'>".$this->getCorretas()."</td>
                        </tr>
                        <tr>
                                <td class='tdNome'>Questões incorretas</td>
                                <td class='tdInfo'>".$this->getIncorretas()."</td>
                        </tr>
                        <tr>
                                <td class='tdNome'>Questões não respondidas</td>
                                <td class='tdInfo'>".$this->getNulas()."</td>
                        </tr>
                        <tr>
                                <td class='tdNome'>Tempo</td>
                                <td class='tdInfo'>".$this->getTempo()."</td>
                        </tr>
                        <tr>
                                <td class='tdNome'>Nivel</td>
                                <td class='tdInfo'>".$this->getNivel()."</td>
                        </tr>
                </table>
            ";
    }
}