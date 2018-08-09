<?php

/**
 * Description of TrataCalc
 *
 * @author Tiago Hellebrandt Silva
 */
require_once '../Calculadora.class.php';
class TrataCalc extends Calculadora {
    private $e; // Calculo não tratado
    private $ae; // Calculo antes de digitar
    private $ex; // Calculo tratado
    private $cursor; // Posição do cursor
    private $result; // Resultado
    
    public function __construct($e,$ae) {
        $this->setE($e);
        $this->setAe($ae);
        $this->startVars();
        $this->tratar();
    }
    
    private function startVars() { // Iniciar variaveis
        $this->setEx($this->getE());
        $this->result = [];
    }
    
    // GETTERS & SETTERS
    
    public function getE() {
        return $this->e;
    }

    public function getAe() {
        return $this->ae;
    }

    public function getEx() {
        return $this->ex;
    }

    public function getCursor() {
        return $this->cursor;
    }

    public function setE($e) {
        $this->e = $e;
    }

    public function setAe($ae) {
        $this->ae = $ae;
    }

    public function setEx($ex) {
        $this->ex = $ex;
    }

    public function setCursor() {
        if(func_num_args()==1) {
            $this->cursor = func_get_arg(0);
        } else {
            if(strlen($this->getTxt($this->getAe()))>strlen($this->getTxt($this->getEx()))) {
                $maior = strlen($this->getTxt($this->getAe()));
            } else {
                $maior = strlen($this->getTxt($this->getEx()));
            }
            $c = 0;
            $c += abs(strlen($this->getTxt($this->getEx()))-strlen($this->getTxt($this->getAe())));
            if($c>1) {
                $this->cursor = strlen($this->getTxt($this->ex));
            } else {
                for($i=0;$i<$maior;$i++) {
                    if((isset(str_split($this->getTxt($this->getAe()))[$i])&&isset(str_split($this->getTxt($this->getEx()))[$i]))){
                        if(str_split($this->getTxt($this->getAe()))[$i] != str_split($this->getTxt($this->getEx()))[$i]) {
                            if(strlen($this->getAe()) <= strlen($this->getE())) {
                                $this->cursor = $i+1;
                                break;
                            } else {
                                $this->cursor = $i;
                                break;
                            }
                        }
                    } elseif((isset(str_split($this->getTxt($this->getAe()))[$i])||isset(str_split($this->getTxt($this->getEx()))[$i]))){
                        if(strlen($this->getAe()) <= strlen($this->getE())) {
                            $this->cursor = $i+1;
                            break;
                        } else {
                            $this->cursor = $i;
                            break;
                        }
                    }
                    
                }
            }
        }
    }
    
    private function removeTags() { // Remove tags
        //$this->setEx($this->removeAlinhar($this->getEx()));
        $this->ex = str_replace("<div>", "", $this->ex);
        $this->ex = str_replace("</div>", "", $this->ex);
        $this->ex = str_replace("<br>", "", $this->ex);
        $this->ex = str_replace("<br type=\"_moz\">", "", $this->ex);
        $this->ex = str_replace("<br type='_moz'>", "", $this->ex);
        $this->ex = str_replace("<sup></sup>", "", $this->ex);
    }
    
    private function setPotencias() {
        if((substr_count($this->ex,"^")>0) && isset(str_split($this->ex)[(strpos($this->ex,"^"))+1]) &&  (array_search(str_split($this->ex)[(strpos($this->ex,"^"))+1],$this->getSeps())===false)) {
            for($i=strpos($this->ex, "^");$i<strlen($this->ex);$i++) {
                if(array_search(str_split($this->ex)[$i],$this->getSeps())>=0&&array_search(str_split($this->ex)[$i],$this->getSeps())!==false) {
                    $this->ex = str_replace(str_split($this->ex)[$i], ("</sup>".str_split($this->ex)[$i]), $this->ex);
                    $this->ex = str_replace("^", "<sup>", $this->ex);
                    break;
                }
            }
            if(substr_count($this->ex,"^")>0) {
                $this->ex = $this->ex."</sup>";
                $this->ex = str_replace("^", "<sup>", $this->ex);
            }
        }
        $this->ex = str_replace("<sup>(</sup>","<sup>()</sup>",$this->ex);
        for($i=0;$i<count($this->getSeps());$i++) {
            $this->ex = str_replace($this->getSeps()[$i]."</sup>","</sup>".$this->getSeps()[$i],$this->ex);
        }
    }
    
    private function setFracoes() { // Define as frações
        $this->setEx(str_replace("</", "<|", $this->getEx()));
        $a = $this->sepElements($this->getEx());
        for($i=0;$i<count($a);$i++){
            if(isset($a[$i])&&isset($a[$i-1])&&isset($a[$i+1])&&($a[$i]=="/"&&$a[$i-1]!="/"&&$a[$i+1]!="/")) {
                if(isset($a[$i-2])&&($a[$i-2]=="*"||$a[$i-2]=="/")) {
                    $this->setEx(str_replace(($a[$i-1].$a[$i].$a[$i+1]), ($this->showFracao($a[$i-1], $a[$i+1])), $this->getEx()));
                } else {
                    $this->setEx(str_replace(($a[$i-1].$a[$i].$a[$i+1]), (($this->getSinal($a[$i-1])).($this->showFracao($this->hideSinal($a[$i-1]), $a[$i+1]))), $this->getEx()));
                }
            }
        }
        $this->setEx(str_replace("<|", "</", $this->getEx()));
        $a = $this->sepElements($this->getEx());;
        for($i=0;$i<count($a);$i++) {
            if(substr_count($a[$i],"<td></td>")>0) {
                $a[$i] = $this->reverseShowFracao($a[$i])[0];
            }
        }
        
        $this->setEx("");
        for($i=0;$i<count($a);$i++) {
            $this->setEx($this->getEx().$a[$i]);
        }
        $this->setEx(str_replace("<td class=\"btmBorder\"></td>", "<td class=\"btmBorder\">0</td>", $this->getEx()));
        for($i=0;$i<count($this->getSeps());$i++) {
            $this->setEx(str_replace("<table class=\"frc\"><tbody><tr><td class=\"btmBorder\">".$this->getSeps()[$i], $this->getSeps()[$i]."<table class=\"frc\"><tbody><tr><td class=\"btmBorder\">", $this->getEx()));
            $this->setEx(str_replace($this->getSeps()[$i]."</td></tr></tbody></table>", "</td></tr></tbody></table>".$this->getSeps()[$i], $this->getEx()));
        }   
    }
    
    private function getSinal($n) {
        $s = "";
        if(substr($n,0,1)=="+"||substr($n,0,1)=="-") {
            $s = substr($n,0,1);
        }
        return $s;
    }
    
    private function hideSinal($n) {
        if(substr($n,0,1)=="+"||substr($n,0,1)=="-") {
            $n = substr($n,1,strlen($n)-1);
        }
        return $n;
    }
    
    
    
    private function tratar() {
        $this->removeTags();
        $this->setPotencias();
        $this->setFracoes();
        $this->setCursor();
        /*if($this->getEx()!=""){
            $this->alinhar();
        }*/
    }
    
    public function show() {
        $this->result["ex"] = $this->getEx();
        $this->result["cursor"] = $this->getCursor();
        echo json_encode($this->result);
    }
}