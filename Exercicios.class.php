<?php

require_once 'Calculadora.class.php';
require_once 'Exercicio.class.php';

class Exercicios extends Calculadora {
    private $perguntas; // Todas perguntas
    private $nivel; // Nivel de dificuldade
    private $quantidade; // Quantidade de perguntas
    private $opcoes; // Opcoes
    private $countCont; // Numero de conteúdos
    private $conteudos; // Conteúdos
    
    public function __construct($opcoes,$nivel,$quantidade){
        $this->setOpcoes($opcoes);
        $this->setNivel($nivel);
        $this->setQuantidade($quantidade);
        $this->setPerguntas();
    }
    
    public function getPerguntas() {
        return $this->perguntas;
    }

    public function getNivel() {
        return $this->nivel;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }

    public function setPerguntas() {
        if(!is_null($this->getNivel())&&!is_null($this->getQuantidade())){
            for($i=0;$i<$this->getCountCont();$i++){
                for($j=0;$j<(int)($this->getQuantidade()/$this->getCountCont());$j++){
                    do {
                        $this->addPergunta(new Exercicio("-".$this->getConteudos()[$i], $this->getNivel()));
                    } while($this->uniquePergunta()>0);
                }
            }
            
            if($this->getQuantidade()%$this->getCountCont()!=0){ // Caso sobre algumas perguntas
                for($i=0;$i<$this->getQuantidade()%$this->getCountCont();$i++){
                    do {
                        $this->addPergunta(new Exercicio("-".$this->getConteudos()[rand(0,$this->getCountCont()-1)], $this->getNivel()));
                    } while($this->uniquePergunta()>0);
                }
            }
            shuffle($this->perguntas);
        }
    }
    
    private function addPergunta($pergunta){ // Adiciona uma pergunta
        $this->perguntas[] = $pergunta;
    }
    
    private function uniquePergunta() { // Torna as perguntas unicas
        $a = array();
        $d = 0; // Numero de perguntas deletadas
        for($i=0;$i<count($this->getPerguntas());$i++){
            $a[] = $this->getPerguntas()[$i]->getPergunta();
        }
        for($i=0;$i<count($this->getPerguntas());$i++){
            $countRepeat = $this->countArrayElements($a, $this->getPerguntas()[$i]->getPergunta());
            if($countRepeat>1){
                for($j=0;$j+1<$countRepeat;$j++){
                    unset($this->perguntas[array_search($this->getPerguntas()[$i]->getPergunta(), $a)]);
                    $d++;
                }
            }
        }
        $this->perguntas = $this->orgArray($this->getPerguntas());
        return $d;
    }
    
    private function countArrayElements($arr,$el) { // Conta quantas vezes um elemento repete em um array
        $j = 0;
        for($i=0;$i<count($arr);$i++){
            if($arr[$i]==$el){
                $j++;
            }
        }
        return $j;
    }

    public function setNivel($nivel) {
        $this->nivel = $nivel;
    }

    public function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }
    
    public function getOpcoes() {
        return $this->opcoes;
    }

    public function setOpcoes($opcoes) {
        $this->opcoes = $opcoes;
        $this->setCountCont();
    }

    public function getCountCont() {
        return $this->countCont;
    }

    public function setCountCont() {
        $this->countCont = substr_count($this->getOpcoes(), "-");
        $this->setConteudos();
    }

    public function getConteudos() {
        return $this->conteudos;
    }

    public function setConteudos() {
        if(!is_null($this->opcoes)){
            $this->conteudos = $this->orgArray(array_filter(explode("-",  $this->opcoes)));
        }
    }

    public function show() {
        $a = range("a", "z");
        $aq = range("a","d");
        $res = "";
        for($i=0;$i<$this->getQuantidade();$i++){
            echo "<div class='questao'>";
            echo "<div class='idQuestao'>".$a[$i]."</div>";
            echo "<h2>".$this->getPerguntas()[$i]->getEnunciado()."</h2>";
            echo "<br /><h3>".$this->formatPergunta($this->getPerguntas()[$i]->getPergunta())."</h3>";
            echo "<ul>";
            for($j=0;$j<count($aq);$j++){
                echo "<li><table><tr><td class='unSelectQuestao'>".$aq[$j]."</td><td>".($this->getPerguntas()[$i]->getOpcoes()[$j])."</td></tr></table><input name='questao".$i."' type='radio' form='formCorrige' value='".($this->getPerguntas()[$i]->getOpcoes()[$j])."'/></li>";
            }
            echo "</ul>";
            // echo "<input type='text' placeholder='Resposta' name='rtxt".$i."' form='formCorrige' style='position: absolute;' autocomplete='off' />";
            echo "</div>";
            $res .= $this->hideRadiciacao($this->getPerguntas()[$i]->getPergunta()).":";
        }
        echo "<script>document.getElementById('respostas').value='$res';</script>";
	echo "<script>document.getElementById('nv').value='".$this->getNivel()."';</script>";
    }

    private function formatPergunta($p) { // Formata a pergunta
        return str_replace("|", "", $p);
    }
}
