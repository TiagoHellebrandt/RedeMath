<?php

require_once "Calculadora.class.php";

class Exercicio extends Calculadora {
    private $pergunta;
    private $tipo;
    private $nivel;
    private $enunciado;
    private $opcoes;
    
    public function __construct($tipo,$nivel) {
        $this->setNivel($nivel);
        $this->setTipo($tipo);
        $this->setPergunta();
    }

    
    public function getPergunta() {
        return $this->pergunta;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getNivel() {
        return $this->nivel;
    }

    private function setPergunta() {
        switch ($this->getTipo()){
            case "-Soma":
                $this->setEnunciado("Efetue a operação abaixo.");
                $this->pergunta = $this->geraSoma($this->getNivel());
            break;
            case "-Subtração":
                $this->setEnunciado("Efetue a operação abaixo.");
                $this->pergunta = $this->geraSubtracao($this->getNivel());
            break;
            case "-Multiplicação":
                $this->setEnunciado("Efetue a operação abaixo.");
                $this->pergunta = $this->geraMultiplicacao($this->getNivel());
            break;
            case "-Divisão":
                $this->setEnunciado("Efetue a operação abaixo.");
                $this->pergunta = $this->geraDivisao($this->getNivel());
            break;
            case "-MMC":
                $this->setEnunciado("Calcule o MMC entre os números abaixo.");
                $this->pergunta = $this->geraMmc($this->getNivel());
            break;
            case "-MDC":
                $this->setEnunciado("Calcule o MDC entre os números abaixo.");
                $this->pergunta = $this->geraMdc($this->getNivel());
            break;
            case "-Equação do 1°":
                $this->setEnunciado("Efetue a equação abaixo.");
                $this->pergunta = $this->geraEquacao1($this->getNivel());
            break;
            case "-Radiciação":
                $this->setEnunciado("Efetue a radiciação abaixo.");
                $this->pergunta = $this->showRaiz2($this->geraRadicicao($this->getNivel()));
            break;
        }
        $this->setOpcoes();
    }

    private function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    private function setNivel($nivel) {
        $nivel = str_replace("Fácil", "facil", $nivel);
        $nivel = str_replace("Médio", "medio", $nivel);
        $nivel = str_replace("Difícil", "dificil", $nivel);
        $this->nivel = $nivel;
    }

    private function geraSoma($nivel){ // Gera uma soma aleatória
        $s = "";
        switch ($nivel) {
            case 'facil':
                $s = rand(1,100)." + ".rand(1,100);
                break;
            case 'medio':
                $s = rand(100,1000)." + ".rand(100,1000);
                break;
            case 'dificil':
                $s = rand(1000,100000)." + ".rand(10000,100000);
                break;
        }
        return $s;
    }
    
    private function geraSubtracao($nivel){ // Gera uma subtração aleatória
        $s = "";
        switch ($nivel) {
                case 'facil':
                        $s = rand(1,100)." - ".rand(1,100);
                        break;
                case 'medio':
                        $s = rand(100,1000)." - ".rand(100,1000);
                        break;
                case 'dificil':
                        $s = rand(1000,100000)." - ".rand(1000,100000);
                        break;
        }
        return $s;
    }
    
    private function geraMultiplicacao($nivel){ // Gera uma multiplocação aleatória
        $s = "";
        switch ($nivel) {
                case 'facil':
                        $s = rand(1,100)." × ".rand(1,100);
                        break;
                case 'medio':
                        $s = rand(100,1000)." × ".rand(100,1000);
                        break;
                case 'dificil':
                        $s = rand(1000,100000)." × ".rand(1000,100000);
                        break;
        }
        return $s;
    }
    
    private function geraDivisao($nivel){ // Gera uma divisão aleatória
        $s = "";
        $p = 0;
        $r = 0;
        switch ($nivel) {
                case 'facil':
                        $p = rand(1,100);
                        $r = rand(1,$p);
                        $s = $p*$r." &div; ".$p;
                        break;
                case 'medio':
                        $p = rand(100,1000);
                        $r = rand(1000,$p);
                        $s = $p*$r." &div; ".$p;
                        break;
                case 'dificil':
                        $p = rand(1000,10000);
                        $r = rand(10000,$p);
                        $s = $p*$r." &div; ".$p;
                        break;
        }
        return $s;
    }
    
    private function geraMmc($nivel){ // Gera um mmc aleatório
        $s = "";
        switch ($nivel) {
                case 'facil':
                        $s = rand(1,100).";".rand(1,100);
                        break;
                case 'medio':
                        $s = rand(1,100).";".rand(1,100).";".rand(1,100);
                        break;
                case 'dificil':
                        $s = rand(100,1000).";".rand(100,1000).";".rand(100,1000).";".rand(100,1000);
                        break;
        }
        return $s;
    }

    private function geraMdc($nivel){ // Gera um mmc aleatório
        $s = "";
        switch ($nivel) {
                case 'facil':
                        $s = rand(1,100).";".rand(1,100);
                        break;
                case 'medio':
                        $s = rand(1,100).";".rand(1,100).";".rand(1,100);
                        break;
                case 'dificil':
                        $s = rand(100,1000).";".rand(100,1000).";".rand(100,1000).";".rand(100,1000);
                        break;
        }
        return $s."|";
    }
    
    private function geraEquacao1($nivel){ // Gera uma equação do 1° grau aleatória
        $alfabeto = range("a", "z");
        $l = $alfabeto[rand(0,count($alfabeto)-1)];
        $max = 0;
        $min = 0;
        switch ($nivel) {
                case 'facil':
                        $max = 50;
                        $min = 15;
                        break;

                case 'medio':
                        $max = 100;
                        $min = 50;
                        break;

                case 'dificil':
                        $max = 150;
                        $min = 100;
                        break;
        }
        $base = rand($min,$max);
        $e = "+".rand(1,$base);
        do {
            $n = "+".rand(1,$base);
            $calculadora = new Calculadora($e.$n);
            if($calculadora->getResult()>$base){
                    $n .= "-".($calculadora->getResult()-$base);
            }
            $e .= $n;
        } while((new Calculadora($e))->getResult()<$base);
        $e = $this->sepElements($e, "=");
        $a = [];
        $c = count($e);
        for($i=0;$i<$c;$i++){
            if($i==$c-1){
                if(count($e)==1){
                    break;
                }
                if(!isset($a[0])){
                    $a[] = $e[$i];
                    unset($e[$i]);
                }
            }
            if(rand(0,1)==0&&isset($e[$i])){
                $a[] = $e[$i];
                unset($e[$i]);
            }
        }
        $e = $this->orgArray($e);
        $e = inverteNumArray($e);
        if(rand(0,1)==0){
                $e[] = "+".$base;
        }else{
                $a[] = "-".$base;
        }
        $p2 = $e;
        $p1 = [];
        $x = rand(1, $base);
        for($i=0;$i<count($a);$i++){
            if(is_int($a[$i]/$x)){
                $p1[$i] = ($a[$i]/$x);
            }else{
                $coeficiente = 0;
                $sobra = 0;
                $j = 0;
                if($x>$a[$i]){
                    $j = -1;
                    $coeficiente = $j;
                    $sobra = ($x+$a[$i])*-1;
                    $p2[] = $sobra;
                }else{
                    while($j*$x<$a[$i]){
                        if(($j+1)*$x>$a[$i]){
                            $coeficiente = $j;
                            $sobra = ($a[$i]-($j*$x))*-1;
                            if($sobra>0){
                                $sobra = "+$sobra";
                            }
                            $p2[] = $sobra;
                        }
                        $j++;
                    }
                }
                $p1[$i] = $coeficiente;
            }
            if($p1[$i]>0){
                $p1[$i] = "+".$p1[$i];
            }
        }
        $pt1 = "";
        for($i=0;$i<count($p1);$i++){
            $pt1 .= $p1[$i].$l;
        }
        $pt2 = "";
        for($i=0;$i<count($p2);$i++){
            if($p2[$i]>0){
                $p2[$i] *= 1;
                $p2[$i] = "+".$p2[$i];
            }
            $pt2 .= $p2[$i];
        }
        $res = $this->misturaElementos($this->sepElements($pt1."=".$pt2,"="));
        return $res;
    }
    
    private function misturaElementos($a){
        $p1 = $this->sepArray($a,"=",-1);
        $p2 = $this->sepArray($a,"=",1);
        for($i=0;$i<count($p1);$i++){
            if(rand(0,1)==0&&count($p1)>1){
                $p2[] = (str_replace($this->verVar($p1[$i]),"",$p1[$i])*(-1)).  $this->verVar($p1[$i]);
                unset($p1[$i]);
                $p1 = $this->orgArray($p1);
            }
        }
        if(count($p1)==0){
            $p1[] = (str_replace($this->verVar($p2[0]),"",$p2[0])*(-1)).  $this->verVar($p2[0]);
            unset($p2[0]);
            $p2 = $this->orgArray($p2);
        }
        for($i=0;$i<count($p2);$i++){
            if(rand(0,1)==0&&count($p2)>1){
                $p1[] = (str_replace($this->verVar($p2[$i]),"",$p2[$i])*(-1)).  $this->verVar($p2[$i]);
                unset($p2[$i]);
                $p2 = $this->orgArray($p2);
            }
        }
        if(count($p2)==0){
            $p2[] = (str_replace($this->verVar($p1[0]),"",$p1[0])*(-1)).  $this->verVar($p1[0]);
            unset($p2[0]);
            $p1 = $this->orgArray($p1);
        }
        $pt1 = "";
        for($i=0;$i<count($p1);$i++){
            if($p1[$i]>0&&substr_count($p1[$i],"+")==0){
                $p1[$i] = "+".$p1[$i];
            }
            $pt1 .= $p1[$i];
        }
        $pt2 = "";
        for($i=0;$i<count($p2);$i++){
            if($p2[$i]>0&&substr_count($p2[$i],"+")==0){
                $p2[$i] = "+".$p2[$i];
            }
            $pt2 .= $p2[$i];
        }
        return $pt1."=".$pt2;
    }

    private function inverteNumArray($a){
        for($i=0;$i<count($a);$i++){
            $a[$i] *= -1;
        }
        return $a;
    }

    private function geraRadicicao($nivel) {
        switch ($nivel) {
            case 'facil':
                $r = rand(0,10);
            break;

            case 'medio':
                $r = rand(11,50);
            break;

            case 'dificil':
                $r = rand(51,100);
            break;
            
        }
        $R = $r*$r;
        if(rand(0,1)==1) {
            $n1 = rand(1,$R);
            $n2 = $R-$n1;
            $R = $n1." + ".$n2;
        }
        return $R;
    }
    
    public function getEnunciado() {
        return $this->enunciado;
    }

    private function setEnunciado($enunciado) {
        $this->enunciado = $enunciado;
    }

    private function setOpcoes() { // Gera opções de resposta
        $pergunta = $this->hideRadiciacao($this->getPergunta());
    	$calc = new Calculadora($pergunta);
    	$this->opcoes[0] = (!$calc->getResult()===false)?$calc->getResult():"Sem solução";
    	if($calc->getResult()){
    		$base = $calc->getResult();
    	}else{
    		$base = rand(0,array_sum($this->array_dell("=",$this->sepElements($calc->getEx(),"="))));
    	}
    	while (count($this->getOpcoes())<4){
    		switch (rand(1,10)) {
    			case 1:
    				$this->opcoes[] = $base+10;
    			break;

    			case 2:
    				$this->opcoes[] = $base-10;
    			break;
    			
    			case 3:
    				if(is_int($base/10)){
    					$this->opcoes[] = $base/10;
    				}
    			break;

    			case 4:
    				$this->opcoes[] = $base*10;
    			break;

    			case 6:
    				if(is_int(($base-10)/10)) {
    					$this->opcoes[] = ($base-10)/10;
    				}
    			break;

    			case 7:
    				$this->opcoes[] = ($base-10)*10;
    			break;

    			case 8:
    				if (is_int(($base+10)/10)) {
    					$this->opcoes[] = ($base+10)/10;
    				}
    			break;

    			case 9:
    				$this->opcoes[] = ($base+10)*10;
    			break;

    			case 10:
    				if($calc->getTipo()=="eqc1"){
    					$this->opcoes[] = "Sem solução";
    				}
    			break;
    		}
    		$this->opcoes = array_unique($this->getOpcoes());
    	}
    	shuffle($this->opcoes);
    }

    public function getOpcoes() { // Retorna opções
    	return $this->opcoes;
    }

}