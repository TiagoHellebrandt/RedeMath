<?php

require_once 'infostep.class.php';

class Calculadora {

    private $ex; // Calculo
    private $passo; // Passo atual
    private $passos; // Todos os passos do calculo (ARRAY)
    private $hasSolution; // Tem solução?
    private $result; // Resultado
    private $type; // Tipo
    private $splits; // elementos solitario
    private $tags; // Tags html que podem conter um calculo
    private $seps; // Separa elementos
    private $print; // Resultado

    function __construct($ex){ // Construtor
        $this->print = "";
        $passo = 0;
        if(isset($ex)) {
            $this->setEx($ex);
        }
    }

    // GETTERS & SETTERS

    public function setEx($ex){ // Altera o valor do atributo $ex
        if(isset($ex)&&$ex!=""){
            $this->ex = $this->tratarEntrada($ex);
            $this->setTipo();
            $this->hasSolution();
            $this->calc();
        }
    }
    
    public function getSeps() {
        if(is_null($this->seps)) {
            $this->setSeps(["+","-","/","*","=","<"]);
        }
        return $this->seps;
    }

    public function setSeps($seps) {
        $this->seps = $seps;
    }
    
    private function getSplits() {
        if(is_null($this->splits)) {
           $this->splits = ["*","/","="];
        }
        return $this->splits;
    }
    
    public function setTags($tags) {
        $this->tags = $tags;
    }
    
    protected function getTags() {
        if(is_null($this->tags)){
            $this->setTags(["sup","table","td","tr","table class='frc'","td class='btmBorder'","tbody","table class=\"frc\"","td class=\"btmBorder\""]);
        }
        return $this->tags;
    }
    
    public function getEx(){ 
        return $this->ex;
    }

    public function getResult(){ // Retorna o resultado
        return $this->result;
    }

    private function setResult($res) { // Define o resultado
        $this->result = $res;
    }
    
    private function setTipo() { // Define o tipo
        $this->type = $this->typeEx($this->getEx());
        var_dump($this->type);
    }

    public function getTipo() { // Retorna o tipo
        return $this->type;
    }

    // Outros metodos
    
    private function addInfoStep($info) { // Adiciona um InfoStep ao passos
        $this->passos[]=new InfoStep($this->passo+1, $info);
        $this->passo++;
    }
    
    private function showStep($step,$info) { // Exibe um passo e adiciona um InfoStep
        $this->addInfoStep($info);
        $this->print .= "<div class='step".$this->passo." steps'>".$step."</div>";
    }
    
    private function showInfoSteps(){
        $this->print .= "<div id='infosteps'>";
        for($i=0;$i<count($this->passos);$i++){
            $this->print .= $this->passos[$i]->show();
        }
        $this->print .= "</div>";
    }
    
    // Metodos para calculo

    /*protected function sepElements($ex){ // Separa os elementos
        $e = [];
        $j = 0;
        if(isset(func_get_args()[1])){
            $t = func_get_args()[1];
        } else {
            $t = "=";
        }
        if(stripos($ex, $t)>0){
            $exs = explode($t, $ex);
            $exs1 = str_split($exs[0]);
            $exs2 = str_split($exs[1]);
            $e[$j] = "";
            for($i=0;$i<count($exs1);$i++){
                if($exs1[$i]=="("){
                    while($exs1[$i]!=")"){
                        $e[$j] .= $exs1[$i];
                        $i++;
                    }
                }
                if((($exs1[$i]=="+")||($exs1[$i]=="-"))&&($i!=0)){
                    $j++;
                    $e[$j] = "";
                }
                $e[$j] .= $exs1[$i];
            }
            $j++;
            $e[$j] = "";
            $e[$j] = $t;
            $j++;
            $e[$j] = "";
            for($i=0;$i<count($exs2);$i++){
                if($exs2[$i]=="("){
                    while($exs2[$i]!=")"){
                        $e[$j] .= $exs2[$i];
                        $i++;
                    }
                }
                if((($exs2[$i]=="+")||($exs2[$i]=="-"))&&($i!=0)){
                    $j++;
                    $e[$j] = "";
                }
                $e[$j] .= $exs2[$i];
            }
        }else{
            $exs = str_split($ex);
            $e[$j] = "";
            for($i=0;$i<count($exs);$i++){
                if((($exs[$i]=="+")||($exs[$i]=="-"))&&($i!=0)){
                    $j++;
                    $e[$j] = "";
                }
                if((($exs[$i]=="*")||($exs[$i]=="/"))&&($i!=0)&&stripos($ex, "<sup>")==0){
                    $j++;
                    $e[$j] = $exs[$i];
                    $j++;
                    $i++;
                    $e[$j] = "";
                }
                $e[$j] .= $exs[$i];
            }
        }
        return $e;
    }*/
    
    /**
     * Esta função separa os elementos de um calculo transformando-a em um array
     * @param String $ex Calculo a ser separado
     * @param Array $beginSeps [opicional] Elementos de separação para o inicio
     * @param Array $endSeps [opcionale] Elementos de separação para o fim
     * @return Array Retorna um array com os elementos do calculo
     */
    protected function sepElements($ex) { // Separa os elementos
        $splits = $this->getSplits(); // Elementos a isolar
        $subSplits = ["+","-"]; // Elementos para separar
        $beginTags = ["<table"]; // Tags de inicio
        $endTags = ["table>"]; // Tags de fim
        $uniques = ["&gt;","&lt;","&ge;","&le;"];
        if(func_num_args()==3) {
            $beginTags = array_merge($beginTags, func_get_arg(1));
            $endTags = array_merge($endTags, func_get_arg(2));
        }
        $delEl = [""];
        $prts = 0;// Numero de parenteses abertos
        // Tags
        for($i=0;$i<count($beginTags);$i++) {
            $ex = str_replace($beginTags[$i], "(".$beginTags[$i], $ex);
            $ex = str_replace($endTags[$i], $endTags[$i].")", $ex);
        }
        $ex = str_replace("</", "<|", $ex);
        $e = str_split($ex);
        $count = count($e);
        $break = "@";
        for($i=0;$i<$count;$i++) {
            if($e[$i]=="(") {
                $prts++;
            } elseif($e[$i]==")") {
                $prts--;
            }
            if((array_search($e[$i], $subSplits)>=0)&&($prts==0)&&(array_search($e[$i], $subSplits)!==false)) {
                $e = $this->addElement($e, $break, $i);
                $count++;
                $i++;

            }
            if((array_search($e[$i], $splits)>=0)&&($prts==0)&&(array_search($e[$i], $splits)!==false)) {
                $e = $this->addElement($e, $break, $i);
                $e = $this->addElement($e, $break, $i+2);
                $count += 2;
                $i += 2;

            }
        }
        $ex = "";
        for($i=0;$i<count($e);$i++) {
            $ex .= $e[$i];
        }
        $ex = str_replace("<|", "</", $ex);
        $ex = str_replace("(<", $break."<", $ex);
        $ex = str_replace(">)", ">".$break, $ex);
        for($i=0;$i<count($uniques);$i++) {
            $ex = str_replace($uniques[$i], $break.$uniques[$i].$break, $ex);
        }
        $e = explode($break, $ex);
        for($i=0;$i<count($e);$i++) {
            if(array_search($e[$i], $delEl)!==false) {
                unset($e[$i]);
                $e = $this->orgArray($e);
                $i--;
            }
        }
        $e = $this->orgArray($e);
        return $e;
    }
    
    private function addElement($array,$el,$pos) {
        if($pos<=count($array)){
            $arr = [];
            // Parte 1
            for($i=0;$i<$pos;$i++) {
                $arr[] = $array[$i];
            }
            $arr[] = $el;
            // Parte 2

            for($i=$pos;$i<count($array);$i++) {
                $arr[] = $array[$i];
                
            }
            return $arr;
        }
    }
    
    private function orgElements($e,$t){ // Organiza os elementos
        $e1 = $this->sepArray($e, $t, -1);
        $e2 = $this->sepArray($e, $t, 1);
        for($i=0;$i<count($e1);$i++){
            if(stripos($e1[$i],$this->verVar($e1[$i]))==false){
                $ax = $e1[$i];
                $ax *= -1;
                if($ax>0){
                    $ax = "+".$ax;
                }
                
                $e2[] = $ax;
                for($j=0;$j+$i<count($e1);$j++){
                    if(isset($e1[$j+$i])&&isset($e1[$j+$i+1])){
                        $e1[$j+$i] = $e1[$j+$i+1];
                    }
                }
                array_pop($e1);
                $i--;
            }
        }
        
        for($i=0;$i<count($e2);$i++){
            if(isset($e2[$i])){
                if(!stripos($e2[$i],$this->verVar($e2[$i]))==false){
                    $ax = $e2[$i];
                    $ax *= -1;
                    if($ax==0){
                        $e1[] = (((substr($e2[$i],0,1)=="+")||(substr($e2[$i],0,1)!="+"&&substr($e2[$i],0,1)!="-"))?"-":"+").$this->verVar($e2[$i]);
                    }else{
                        if($ax>0){
                            $ax = "+".$ax;
                        }
                        $e1[] = $ax.$this->verVar($e2[$i]);
                    }

                    for($j=0;$j+$i<count($e2);$j++){
                        if(isset($e2[$j+$i])&&isset($e2[$j+$i+1])){
                            $e2[$j+$i] = $e2[$j+$i+1];
                        }
                    }
                    array_pop($e2);
                    $i--;
                }
            }
        }
        $resOrg = "";
        
        for($i=0;$i<count($e1);$i++){
            if(isset($e1[$i])){
                $resOrg .= $e1[$i];
            }
        }
        $resOrg .= $t;
        for($i=0;$i<=count($e2);$i++){
            if(isset($e2[$i])){
                $resOrg .= $e2[$i];
            }
        }
        return $resOrg;
    }
    
    protected function sepArray($e,$t,$p){// Separa um array em duas partes
        $e1 = [];
        $e2 = [];
        $pos = array_search($t, $e);
        if($pos!==false) {
            for($i=0;$i<$pos;$i++){
                if(isset($e[$i])){
                    $e1[$i] = $e[$i];
                }
            }
            $pos++;
            for($i=0;$i+$pos<=count($e);$i++){
                if(isset($e[$i+$pos])){
                    $e2[$i] = $e[$i+$pos];
                }
            }
            if($p<0){
                return $e1;
            }else if($p>0){
                return $e2;
            }else{
                return false;
            }
        } else {
            return false;
        }
    }
    
    private function sees($ex){ // Elimina os espaços de uma string
        if(isset($ex)){
            return str_replace(" ", "", $ex);
        }else{
            return false;
        }
    }
    
    protected function verVar($ex){ // Retorna a VARIAVEL de uma string
        if(isset($ex)){
            $v = "";
            $exs = str_split($ex);
            for($i=0;$i<count($exs);$i++){
                if(($exs[$i]>="a"&&$exs[$i]<="z")||($exs[$i]>="A"&&$exs[$i]<="Z")){
                    $v = $exs[$i];
                }else{
                    $v = false;
                }
            }
            return $v;
        }
    }
    
    private function comNum($e){ // Explicita os coeficientes em todos os elementos
        $v = "";
        $resComNum = "";
        for($i=0;$i<count($e);$i++){
            if(isset($e[$i])){
                if($this->verVar($e[$i])!=false){
                    if($this->soNumero($e[$i])==false&&$e[$i]!="="&&strlen($e[$i])<=3){
                        $ax = substr($e[$i], 0,1);
                        if($ax=="+"){
                            $v = "+1".$this->verVar($e[$i]);
                        }elseif($ax=="-"){
                            $v = "-1".$this->verVar($e[$i]);
                        }else{
                            $v = "+1".$this->verVar($e[$i]);
                        }
                        if(substr_count($e[$i], $this->verVar($e[$i]).$this->verVar($e[$i]))>0){
                            $e[$i] = $v.$this->verVar($e[$i]);
                        }else{
                            $e[$i] = $v;
                        }
                    }
                }
                $resComNum .= $e[$i];
            }
        }
        
        return $resComNum;
    }
    
    private function soNumero($str) {
        return preg_replace("/[^0-9]/", "", $str);
    }

    private function letras($str) {
        $str = strtolower($str);
        return preg_replace("/[^a-z]/", "", $str);
    }
    
    private function somaElements($e,$t){ // Soma os elementos semelhantes da equação ou inequação
        $e1 = $this->sepArray($e, $t, -1);
        $e2 = $this->sepArray($e, $t, 1);
        $v1 = 0;
        $v2 = 0;
        $resSoma = "";
        
        for($i=0;$i<count($e1);$i++){
            $v1 += $e1[$i];
        }
        $v1 .= $this->verVar($e1[0]);
        for($i=0;$i<=count($e2);$i++){
            if(isset($e2[$i])){
                $v2 += $e2[$i];
            }
        }
        $resSoma = $v1.$t.$v2;
        return $resSoma;
    }
    
    private function showEquacao1($ex){ // Exibe o cauculo da equação do 1° grau
        $this->print .= "<div id='eqcres'>";
        $ex = $this->sees($ex);
        $ex = $this->comNum($this->sepElements($ex));
        $this->print .= "$ex<br />";
        if($this->soLetra($this->toString($this->sepArray($this->sepElements($ex),"=",1)))==""){
            $ex = $this->toString($this->sepArray($this->sepElements($ex),"=",-1))."=".$this->expressaoCalc($this->toString($this->sepArray($this->sepElements($ex),"=",1)));
        }
        if($this->soLetra($this->toString($this->sepArray($this->sepElements($ex),"=",-1)))==""){
            $ex = $this->expressaoCalc($this->toString($this->sepArray($this->sepElements($ex),"=",-1)))."=".$this->toString($this->sepArray($this->sepElements($ex),"=",1));
        }
        $ex = str_replace("*", "", $ex);
        $antes = $ex;
        if($ex!=$antes){
            $this->print .= "$ex<br />";
        }
        $ae = $this->sepElements($ex);
        for($i=0;$i<count($ae);$i++){
            if(substr_count($ae[$i],"(")>0){
                $ae[$i] = str_replace("<sup>1</sup>", "", $this->distributiva($ae[$i]));
            }
        }
        $ex = "";
        for($i=0;$i<count($ae);$i++){
            $ex .= $ae[$i];
        }
        if($ex!=$antes){ // Quando tem distributiva
            $this->showStep("$ex<br />", "Resolva a distributiva.");
        }
        $antes = $ex;
        $ex = $this->orgElements($this->sepElements($ex),"=");
        
        if($antes!=$ex){
            $this->showStep("$ex<br />", "Agrupe os termos com a variável de um lado da igualdade, e os que não têm do outro. Lembre-se que ao passar um termo para o outro lado, seu sinal se altera, de positivo para negativo e virce-versa.");
        }
        $antes = $ex;
        $ex = $this->somaElements($this->sepElements($ex),"=");
        $e = $this->sepElements($ex);
        if($e[0]<0){
            $this->showStep("$ex (-1)<br />", "Some todos os termos semelhantes, e multiplique a equação por -1, assim invertendo todos os sinais.");
            $ex = $this->inverteEqc($e);
        }
        if($antes!=$ex){
            $this->showStep("$ex<br />","Some todos os termos semelhantes.");
        }
        $es = $this->trocaCoeficiente($this->sepElements($ex),false,"=");
        $this->showStep("$es<br />", "Passe o coeficiente de ".$this->icognita($ex)." para o outro lado dividindo.");
        $ex = $this->trocaCoeficiente($this->sepElements($ex),true,"=");
        $this->showStep("$ex", "Parabéns! Você resolveu a equação.");
        $this->print .= "</div>";
    }

    private function inverteEqc($e){ // Multiplica a equação por (-1)
        if(substr($e[0],0,-1)<0){
            $e1 = (substr($e[0],0,-1)*(-1)).$this->verVar($e[0]);
            $e2 = (($e[2])*(-1));
            $res = $e1."=".$e2;
            return $res;
        }
    }

    private function trocaCoeficiente($e,$p,$t){ // Passa o coeficiente de um lado dividindo
        $d = substr($e[0],0,-1);
        $e[0] = $this->verVar($e[0]);
        $res = "";
        if(!$p){
            for($i=1;$i<=count($e);$i++){
                if(isset($e[$i])&&$e[$i]!=$t){
                    $e[0] = "<table class='tableCenter'><tr><td>".$this->verVar($e[0])."</td>";
                    $e[1] = "<td>".$e[1]."</td>";
                    $e[$i] = "<td>".$this->showFracao($e[$i],$d)."</td></tr></table>";//$e[$i]."&div;$d"
                }
            }
        }else{
            for($i=1;$i<=count($e);$i++){
                if(isset($e[$i])&&$e[$i]!=$t){
                    $e[$i] = $e[$i]/$d;
                }
            }
        }
        for($i=0;$i<=count($e);$i++){
            if(isset($e[$i])){
                $res .= $e[$i];
            }
        }
        return $res;
    }
    
    private function deleteNull($e){ // Apaga valores igualis a 0 em um array
        for($i=0;$i<count($e);$i++){
            if(isset($e[$i])){
                if($e[$i]+0==0){
                    for($j=0;$j+$i+1<count($e);$j++){
                        if(isset($e[$j+$i+1])){
                            $e[$j+$i] = $e[$j+$i+1];
                        }
                    }
                    array_pop($e);
                }
            }
        }
    }
    
    private function typeEx($ex){ // Retorna o tipo de calculo
        $n = count($this->sepElements($ex));
        if(substr_count($ex, "'")>0&&substr_count($ex,"[rga3smp]")>0){
            return "rga3smp";
        }elseif((substr_count($ex, "'")>0&&(substr_count($ex,"jsp")>0)||substr_count($ex,"jcp")>0)||($this->isJson($ex)&&isset(json_decode($ex)->tipoJuros))){
            return "juros";
        }elseif($this->isDesigual($this->getDesigual($ex))){
            return "iqc1";
        }elseif(array_search("=",$this->sepElements($ex))!==false&&$this->eqc2Valida($ex)==false&&substr_count($ex,"<table class='frc'")==0){
            return "eqc1";
        }elseif(array_search("=",$this->sepElements($ex))!==false&&$this->eqc2Valida($ex)==true&&substr_count($ex,"<table class='frc'")==0){
            return "eqc2";
        }elseif(stripos($ex,"-")==false&&stripos($ex,"*")==false&&stripos($ex,"/")==false&&$n==2&&substr_count($ex, "(")==0&&$this->soLetra($ex)==""&&substr_count($ex,"√")==0) {
            return "soma";
        }elseif (stripos($ex,"+")==false&&stripos($ex,"*")==false&&stripos($ex,"/")==false&&$n==2&&substr_count($ex, "(")==0&&$this->soLetra($ex)==""&&substr_count($ex,"√")==0) {
            return "subtracao";
        }elseif (substr_count($ex,"-")==0&&substr_count($ex,"+")==0&&substr_count($ex,"/")==0&&$n==3&&substr_count($ex, "(")==0&&$this->soLetra($ex)==""&&substr_count($ex,"√")==0) {
            return "mult";
        }elseif($this->isFracao($ex)&&count($this->sepElements($ex))==1){
            return "div";
        }elseif (substr_count($ex,"<table class='frc'")>0) {
            return "frcOrc";
        }elseif(substr_count($ex, "(")>0&&substr_count($ex,"<sup>")==0&&substr_count($ex, "=")==0&&$this->soLetra($ex)!=""&&substr_count($ex,"√")==0){
            return "distbv";
        }elseif(substr_count($ex, ";")>0&&substr_count($ex,"√")==0){
            return "mmcmdc";
        }elseif(count($this->sepElements($ex))==1&&substr_count($ex,"sup")>0&&is_double($this->expressaoCalc(substr($ex,strpos($ex, "<sup>")+5,strpos($ex,"</sup>")-(strpos($ex, "<sup>")+5))))){
            return "rad";
        }elseif((substr_count($ex, "[")==0&&substr_count($ex, "{")==0)){
            return "exp";
        }elseif($this->soLetra($ex)==""&&(substr_count($ex, "[")>0||substr_count($ex, "{")>0)){
            return "expNum";
        }else{
            return "nhm";
        }
    }
    
    private function somaCalc($n1,$n2){ // Calcula a soma entre dois numeros
        $res = $n1 + $n2;
        if(substr($n1, 0,1)=="+"){
            $n1 = substr($n1,(strlen($n1)-1)*(-1));
        }
        if(substr($n2, 0,1)=="+"){
            $n2 = substr($n2,(strlen($n2)-1)*(-1));
        }
        $maior;
           
        if((strlen($n1) > strlen($n2)) && strlen($n1) > strlen($res)){
            $maior = strlen($n1);
        }else if((strlen($n2) > strlen($n1)) && strlen($n2) > strlen($res)){
            $maior = strlen($n2);
        }else{
            $maior = strlen($res);
        }
        $this->print .= "<table id='tabres' style='width:".(19*$maior+19)."px;'><tr><td rowspan='3'>+</td></tr>";
        $this->print .= "<tr>";
                
        $a = str_split($n1);
        for($i=0;$i+strlen($n1)<$maior;$i++){
            $this->print .= "<td></td>";
        }

        for($i=0;$i<strlen($n1);$i++){
            $this->print .= "<td>$a[$i]</td>";
        }
        $this->print .= "</tr><tr>";

        $b = str_split($n2);
        for($i=0;$i+strlen($n2)<$maior;$i++){
            $this->print .= "<td class='ln2'></td>";
        }

        for($i=0;$i<strlen($n2);$i++){

            $this->print .= "<td class='ln2'>$b[$i]</td>";
        }
        $this->print .= "</tr><tr><td></td>";

        $c = str_split($res);
        for($i=0;$i<strlen($res);$i++){
            $this->print .= "<td>$c[$i]</td>";
        }

        $this->print .= "</tr></table>";
    }

    private function subCalc($n1,$n2){ // Calcula a subtração entre dois numeros
        if(substr($n1, 0,1)=="-"){
            $n1 = substr($n1,(strlen($n1)-1)*(-1));
        }
        if(substr($n2, 0,1)=="-"){
            $n2 = substr($n2,(strlen($n2)-1)*(-1));
        }
        $inverte = false;
        if($n1<$n2){
            $res = $n2 - $n1;
            $inverte = true;
        }else{
            $res = $n1 - $n2;
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
        $this->print .= "<table id='tabres' style='width:".(19*$maior+19)."px;'><tr><td rowspan='3'>-</td></tr>";
        $this->print .= "<tr>";
                
        $a = str_split($n1);
        $b = str_split($n2);
        for($i=0;$i+(($inverte)?strlen($n2):strlen($n1))<$maior;$i++){
            $this->print .= "<td></td>";
        }

        for($i=0;$i<(($inverte)?strlen($n2):strlen($n1));$i++){
            $this->print .= "<td>";
            $this->print .= ($inverte)?$b[$i]:$a[$i];
            $this->print .= "</td>";
        }

        $this->print .= "</tr><tr>";


        for($i=0;$i+(($inverte)?strlen($n1):strlen($n2))<$maior;$i++){
            $this->print .= "<td class='ln2'></td>";
        }

        for($i=0;$i<(($inverte)?strlen($n1):strlen($n2));$i++){
            $this->print .= "<td class='ln2'>";
            $this->print .= ($inverte)?$a[$i]:$b[$i];
            $this->print .= "</td>";
        }
        $this->print .= "</tr><tr><td></td>";

        $c = str_split($res);
        for($i=0;count($c)<$maior;$i++){
            array_unshift($c, "0");
        }
        for($i=0;$i<count($c);$i++){
            $this->print .= "<td>$c[$i]</td>";
        }

        $this->print .= "</tr></table>";
        if($inverte){
            $this->print .= "<div id='dclc'><span class='clc'>";
            $this->print .= $res ." (-1)<br />";
            $res *= -1;
            $this->print .= $res."</span></div>";
        }
    }
    
    private function expressaoCalc($e){ // Calcula uma expressão matematica simples
        $e = $this->reverseFracao($e);
        /*
        if(substr_count($e,"√")>0){
            $e = str_replace("√", "|", $e);
            $ae = str_split($e);// Array $e
            $prtRdc = false;// Parte da radiciação
            $rzQrd = "";// Raiz quadrada
            for($i=0;$i<count($ae);$i++){
                if($ae[$i]=="|"){
                    $prtRdc = true;
                    continue;
                }
                if($prtRdc){
                    if($ae[$i]=="("&&$ae[$i-1]=="|"){
                        $rzQrd = substr($e,$i,stripos($e,")"));
                        for($j=2;substr_count($rzQrd,"(")!=substr_count($rzQrd,")");$j++){
                            $rzQrd = substr($e,stripos($e, "("),$this->stripos_count($e, ")",$j)-stripos($e, "(")+1);
                        }
                        $i += strlen($rzQrd)-1;
                        $e = str_replace("|".$rzQrd, sqrt($this->expressaoCalc($rzQrd)), $e);
                        $rzQrd = "";
                        $prtRdc = false;
                    }else{
                        if($ae[$i]=="+"||$ae[$i]=="-"||$ae[$i]=="*"||$ae[$i]=="/"||$ae[$i]=="("||$ae[$i]==")"){
                            $prtRdc = false;
                            $e = str_replace("|".$rzQrd, sqrt($this->expressaoCalc($rzQrd)), $e);
                            $rzQrd = "";
                        }else{
                            $rzQrd .= $ae[$i];
                        }
                    }
                }
            }
        }*/
        // Potencia
        if(substr_count($e, "<sup>")>0) {
            $pex = $this->sepElements($e);
            $e = "";
            for($i=0;$i<count($pex);$i++) {
                if(substr_count($pex[$i], "<sup>")>0) {
                    $expoente = $pex[$i];
                    $expoente = str_replace(substr($expoente, 0, strpos($expoente, ">")+1),"",$expoente);
                    $expoente = str_replace("</sup>","",$expoente);
                    $pex[$i] = "+pow(".(str_replace("<sup>".$expoente."</sup>","",$pex[$i]).",".$this->expressaoCalc($expoente)).")";
                }
                $e .= $pex[$i];
            }
        }
        $s = "";
        $comando = "\$s = $e;";
        eval($comando);
        return $s;
    }
    
    protected function showPotencia($b,$e) { // Retorna uma potencia em html
        // ...
    }
    
    private function reverseFracao($ex) { // Transforma 
        $e = $this->sepElements($ex);
        $ex = "";
        for($i=0;$i<count($e);$i++) {
            if($this->isFracao($e[$i])) {
                $e[$i] = $this->reverseShowFracao($e[$i])[0]."/".$this->reverseShowFracao($e[$i])[1];
            }
            $ex .= $e[$i];
        }
        return $ex;
    }

    private function expressaoNumerica($ex){ // Calcula uma expressão numerica
        try{
            while(substr_count($ex, "(")>0&&substr_count($ex, ")")>0){
                $parenteses = substr($ex,stripos($ex, "("),stripos($ex, ")")-stripos($ex, "(")+1);
                for($i=2;substr_count($parenteses,"(")!=substr_count($parenteses,")");$i++){
                    $parenteses = substr($ex,stripos($ex, "("),$this->stripos_count($ex, ")",$i)-stripos($ex, "(")+1);
                }
                if(substr_count($parenteses,"[")==0&&substr_count($parenteses,"{")==0){
                    $ex = str_replace($parenteses, $this->expressaoCalc($parenteses), $ex);
                }else{
                    throw new Exception("Expressão invalida!");
                }
                $this->print .= "$ex<br />";
            }

            while(substr_count($ex, "[")>0&&substr_count($ex, "]")>0){
                $conchetes = substr($ex,stripos($ex, "["),stripos($ex, "]")-stripos($ex, "[")+1);
                if(substr_count($conchetes,"{")==0){
                    $calculo = $conchetes;
                    $calculo = str_replace("[", "", $calculo);
                    $calculo = str_replace("]", "", $calculo);
                    $ex = str_replace($conchetes, $this->expressaoCalc($calculo), $ex);
                }else{
                    throw new Exception("Expressão invalida!");
                }
                $this->print .= "$ex<br />";
            }

            if(substr_count($ex, "{")>0){
                while(substr_count($ex, "{")>0&&substr_count($ex, "}")>0){
                    $chaves = substr($ex,stripos($ex, "{"),stripos($ex, "}")-stripos($ex, "{")+1);
                    $calculo = $chaves;
                    $calculo = str_replace("{", "", $calculo);
                    $calculo = str_replace("}", "", $calculo);
                    $ex = str_replace($chaves, $this->expressaoCalc($calculo), $ex);
                    if(substr_count($ex, "{")>0&&substr_count($ex, "}")>0){
                        $this->print .= "$ex<br />";
                    }
                }
            }
            $ex = str_replace("--", "+", $ex);
            $ex = str_replace("-+", "-", $ex);
            $ex = str_replace("+-", "-", $ex);
            $ex = str_replace("++", "+", $ex);
            $ex = $this->expressaoCalc($ex);
            $this->print .= $ex;
        }catch(Exception $e){
            $this->print .= $e->getMessage();
        }
    }
    
    private function multCalc($n1,$n2){
        $vezes10 = 0;
        $an1=$n1;
        $an2=$n2;
        while(substr_count($n1, ".")||substr_count($n2, ".")){
            $n1 *= 10;
            $n2 *= 10;
            $vezes10++;
        }

        $res = $an1 * $an2;
        $a = str_split($an1);
        $b = str_split($an2);
        $c = str_split($res);
        for($i=0;$i<count($a);$i++) {
            if($a[$i]=="."&&isset($a[$i-1])) {
                $a[$i-1] .= ".";
                unset($a[$i]);
                break;
            }
        }
        $a=$this->orgArray($a);
        for($i=0;$i<count($b);$i++) {
            if($b[$i]=="."&&isset($b[$i-1])) {
                $b[$i-1] .= ".";
                unset($b[$i]);
                break;
            }
        }
        $b=$this->orgArray($b);
        for($i=0;$i<count($c);$i++) {
            if($c[$i]=="."&&isset($c[$i-1])) {
                $c[$i-1] .= ".";
                unset($c[$i]);
                break;
            }
        }
        $c=$this->orgArray($c);
        $n2 = str_replace(".", "", $an2);
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
                for($j=0;$j<count($a);$j++){
                    $zeros = $zeros."0";
                }
                $r[$i] = $zeros;
            }else{
                $r[$i] = $r[$i].$zeros;
            }
        }
        $r = $this->orgArray($r);

        $maior;

        if((count($a) > count($b)) && count($a) > count($c)){
            $maior = count($a);
        }else if((count($b) > count($a)) && count($b) > count($c)){
            $maior = count($b);
        }else{
            $maior = count($c);
        }
        
        $this->print .= "<table id='tabres' style='width:".(19*$maior+19)."px;'><tr><td rowspan='3'>×</td></tr>";
        $this->print .= "<tr>";
                
        for($i=0;$i+count($a)<$maior;$i++){
            $this->print .= "<td></td>";
            $sn1++;
        }

        for($i=0;$i<count($a);$i++){
            $this->print .= "<td>$a[$i]</td>";
            $n1++;
        }
        $this->print .= "</tr><tr>";


        for($i=0;$i+count($b)<$maior;$i++){
            $this->print .= "<td class='ln2'></td>";
            $sn2++;
        }

        for($i=0;$i<count($b);$i++){
            $this->print .= "<td class='ln2'>$b[$i]</td>";
            $sn2++;
        }
        $this->print .= "</tr><tr><td></td>";

        if($sn1>$sn2){
            $smaior = $sn1;
        }else{
            $smaior = $sn2;
        }

        for($i=0;$i<=(count($r)-1);$i++){
                $this->print .= "<tr><td></td>";
                $rr = str_split($r[$i]);
                if(strlen($r[$i])<$smaior){
                    for($h=0;$h+strlen($r[$i])<$smaior;$h++){
                        $this->print .= "<td></td>";
                    }
                }

                for($j=0;$j<count($rr);$j++){
                    $this->print .= "<td>".$rr[$j]."</td>";
                }
                $this->print .= "</tr>";
        }
        $this->print .= "</tr><tr><td></td>";
        if(strlen($n2)!=1){
            for($i=0;$i<count($c);$i++){
                $this->print .= "<td class='brf'>$c[$i]</td>";
            }
        }

        $this->print .= "</tr></table>";
    }
    
    private function simplificarShowFracao($ex){
        $f = $this->reverseShowFracao($ex);
        $n1 = $f[0];
        $n2 = $f[1];
        $mdc = $this->mdc($n1.";".$n2);
        $this->print .= "<table class='tableCenter'><tr><td>".$this->showFracao($n1, $n2)."</td><td>=</td><td>".$this->showFracao($n1/$mdc, $n2/$mdc)."</td></tr></table>";
    }
    
    private function simplificarFracao($ex){
        $f = $this->reverseShowFracao($ex);
        $n1 = $f[0];
        $n2 = $f[1];
        $mdc = $this->mdc($n1.";".$n2);
        return $this->showFracao($n1/$mdc, $n2/$mdc);
    }
            
    private function divCalc($n1,$n2){
        $n1 = $this->expressaoCalc($n1);
        $n2 = $this->expressaoCalc($n2);
        $res = $n1/$n2;// Resultado da divisão
        while(is_double($n1)||is_double($n2)){
            $n1 = (($n1*10)."")*1;
            $n2 = (($n2*10)."")*1;
        }
        $this->simplificarShowFracao($this->showFracao($n1, $n2));
        $this->print .= "<table id='tabres'><tr><td>";
        $this->print .= "<table id='tabnumenador'><tr>";
        $J = 0;// Valor inicial de j
        $addPonto = false;
        while($n1<$n2){
            $n1 .= "0";
            if(!$addPonto) {
                $J = 2;
                $addPonto = true;
            } else {
                $J++; 
            }
        }
        $an1 = str_split($n1);// Array do n1
        //imprime dividendo
        for($i=0;$i<count($an1);$i++){
            $this->print .= "<td>".$an1[$i]."</td>";
        }
        $this->print .= "</tr>";
        $numsub = "";// Numero a ser subtraido
        $rq = "";// Resto do quociente
        for($i=0;$i<count($an1);$i++){
            $numsub .= $an1[$i];
            if($numsub>=$n2){
                for($j=0;$j<count($an1);$j++){
                    if($j>strlen($numsub)-1){
                        $rq .= $an1[$j];
                    }
                }
                break;
            }
        }
        $arq = str_split($rq);// Array $rq
        $r = str_split($res);
        $spaceNone = 0;
        $l = 0;
        for($j=$J;$j<strlen($res);$j++){
            // Imprime ($n2*$r[$j])
            $r[$j] = 9;
            while ($n2*$r[$j]>$numsub) {
                $r[$j]--;
            }
            $mn2r = $n2*$r[$j];// Multiplicação de $n2 e $r[$j]
            for($i=0;strlen($numsub)-strlen($mn2r)-$i>0;$i++){
                $spaceNone++;
            }
            while(strlen($numsub)>strlen($mn2r)){
                $mn2r = "0$mn2r";
                $spaceNone--;
            }
            $amn2r = str_split($mn2r);// Array de $mn2r
            $this->print .= "<tr>";
            for($i=0;$i<$spaceNone;$i++){
                $this->print .= "<td></td>";
            }
            for($i=0;$i<count($amn2r);$i++){
                $this->print .= "<td class='beb'>".$amn2r[$i]."</td>";
            }
            $this->print .= "</tr>";
            // Imprime ($numsub-$mn2r)
            $subr = $numsub-$mn2r;// Resposta da subtração
            while(strlen($numsub)>strlen($subr)){
                $subr = "0$subr";
            }
            if(isset($arq[$l])){
                $subr .= $arq[$l];
                if($subr<$n2&&$subr!=0){
                    $j++;
                }
                $l++;
                if($subr==0){
                    $j++;
                    while(isset($arq[$l])){
                        $subr .= $arq[$l];
                        $j++;
                        $l++;
                    }
                }
            }
            if($subr<$n2&&$subr!=0){
                if(isset($arq[$l])){
                    $subr .= $arq[$l];
                    $l++;
                }else{
                    $subr .= "0";
                }
                if(isset($r[$j+1])){
                    if($r[$j+1]=="."||$r[$j+1]=="0"){
                        $j++;
                    }
                }
            }
            // var_dump($arq);
            $asubr = str_split($subr);// Array $subr
            $this->print .= "<tr>";
            for($i=0;$i<$spaceNone;$i++){
                $this->print .= "<td></td>";
            }
            for($i=0;$i<count($asubr);$i++){
                $this->print .= "<td>".$asubr[$i]."</td>";
            }
            $this->print .= "</tr>";
            if($subr!=0){
                for($i=0;$i<count($asubr);$i++){
                    if($asubr[$i]==0){
                        $asubr[$i] = "";
                        $spaceNone++;
                    }else{
                        break;
                    }
                }
                $subr = "";
                for($i=0;$i<count($asubr);$i++){
                    $subr .= $asubr[$i];
                }
            }
            $numsub = $subr;
        }
        $this->print .= "</table>";
        $this->print .= "</td><td><table id='tabdenominador'><tr>";
        $an2 = str_split($n2);// Array de $n2
        if(strlen($n2)<strlen($res)){
            for($i=0;$i<count($an2);$i++){
                if($i==0){
                    $this->print .= "<td class='apb'>".$an2[$i]."</td>";
                }else{
                    $this->print .= "<td>".$an2[$i]."</td>";
                }
            }
        }else{
            for($i=0;$i<count($an2);$i++){
                if($i==0){
                    $this->print .= "<td class='apb' class='beb'>".$an2[$i]."</td>";
                }else{
                    $this->print .= "<td class='beb'>".$an2[$i]."</td>";
                }
            }
        }
        $this->print .= "</tr><tr>";
        if(strlen($n2)<=strlen($res)){
            for($i=0;$i<count($r);$i++){
                $this->print .= "<td class='rbt'>".$r[$i]."</td>";
            }
        }else{
            for($i=0;$i<count($r);$i++){
                $this->print .= "<td>".$r[$i]."</td>";
            }
        }
        $this->print .= "</tr></table></td></tr></table>";
        $this->print .= "<script>var ml = '-'+($('#tabnumenador').width()-".(strlen($n1)*19+20).")+'px';$('#tabdenominador').css('margin-left',ml)</script>";
    }
    
    private function eqc1res($ex){
        $ex = $this->sees($ex);
        $ex = $this->comNum($this->sepElements($ex));
        $ex = $this->orgElements($this->sepElements($ex),"=");
        $ex = $this->somaElements($this->sepElements($ex),"=");
        $e = $this->sepElements($ex);
        if($e[0]<0){
            $ex = $this->inverteEqc($e);
        }
        $ex = $this->trocaCoeficiente($this->sepElements($ex),true,"=");
        $a = str_split($ex);
        $ex = "";
        for($i=0;$i<count($a);$i++){
            if($i<2){
                continue;
            }
            $ex .= $a[$i];
        }
        return $ex;
    }
    private function eqc1Valida($ex){ // Verifica se a equação tem solução
        $ex = $this->sees($ex);
        $ex = $this->comNum($this->sepElements($ex));
        $ae = $this->sepElements($ex);
        for($i=0;$i<count($ae);$i++){
            if(substr_count($ae[$i],"(")>0){
                $ae[$i] = str_replace("<sup>1</sup>", "", $this->distributiva($ae[$i]));
            }
        }
        $ex = "";
        for($i=0;$i<count($ae);$i++){
            $ex .= $ae[$i];
        }
        $ex = $this->orgElements($this->sepElements($ex),"=");
        $ex = $this->somaElements($this->sepElements($ex),"=");
        $as = str_split($ex);
        $a = $as[0];
        if($a=="0"){
            return false;
        }else{
            return true;
        }
    }
    
    /*private function distributiva($ex){ // Realiza o calculo da distributiva (chuveirinho)
        if(substr_count($ex, "(")==substr_count($ex, ")")){
            $qdp = substr_count($ex, "(");
            $pex = [];
            for($i=0;$i<$qdp;$i++){
                $pex[$i] = substr($ex,strpos($ex, "(")+1,strpos($ex, ")")-1);
                $pex[$i] = str_replace("(", "", $pex[$i]);
                $pex[$i] = str_replace(")", "", $pex[$i]);
                $ex = substr($ex, 0,strpos($ex,"(")).substr($ex,strpos($ex,")")+1);
            }
            $ex = str_replace("*", "", $ex);
            if($ex!=""){
                $pex[] = $ex;
            }
            
            $pe = [];
            for($i=0;$i<count($pex);$i++){
                $pe[$i] = $this->sepElements($pex[$i], "=");
            }
            $ps = [];// Segundos parenteses
            $p = 0;// Parenteses
            if(count($pe)>2){
                for($a=0;$a+2<count($pe);$a++){
                    $ps[$a] = $pe[$a+2];
                    unset($pe[$a+2]);
                }
                $p = count($ps)+1;
            }else{
                $p = 1;
            }
            $pr = $pe[0];// Parentese resposta
            $PE = $pe[1];
            unset($pe);
            $pf = [];// Parentese final
            for($c=0;$c<$p;$c++){
                if($c>0){
                    $PE = $ps[$c-1];
                }
                $l = 0;
                while($l<count($pr)){
                    $j = 0;
                    while($j<count($PE)){
                        $pf[] = $this->multElements($pr[$l], $PE[$j]);
                        $j++;
                    }
                    $l++;
                }
                $pr = $pf;
                $pf = [];
            }
            $v = "";
            for($i=0;$i<count($pr);$i++){
                $v .= $pr[$i];
            }
            return $v;
        }
    }*/
    
    private function showDistributiva($ex) {
        $ex = str_replace("*(","(",$ex);
        $a = $this->subElements($ex);
        $e = $a;
        for ($i = 0; $i < count($e); $i++) {
            $e[$i] = $this->sepElements($e[$i]);
        }
        $r = [];
        for ($i = 0; $i < count($e[0]); $i++) {
            for ($j = 0; $j < count($e[1]); $j++) {
                $r[] = $this->tratar($this->multElements($e[0][$i], $e[1][$j]));
            }
        }
        $res = "";
        for ($i = 0; $i < count($r); $i++) {
            $res .= $r[$i];
        }
        //$res = $this->somaSemelhantes($res);
        if(count($e)>2) {
            $ex = "($res)";
            for ($i = 2; $i < count($e); $i++) {
                $ex .= "(";
                for ($j = 0; $j < count($e[$i]); $j++) {
                    $ex .= $e[$i][$j];
                }
                $ex .= ")";
            }
            $this->print .= $ex."<br>";
            return $this->showDistributiva($ex);
        } else {
            return $res;
        }
    }
    
    private function distributiva($ex) {
        $ex = str_replace("*(","(",$ex);
        $a = $this->subElements($ex);
        if(count($a)==1){
            $a[] = "1";
        }
        $e = $a;
        for ($i = 0; $i < count($e); $i++) {
            $e[$i] = $this->sepElements($e[$i]);
        }
        $r = [];
        for ($i = 0; $i < count($e[0]); $i++) {
            for ($j = 0; $j < count($e[1]); $j++) {
                $r[] = $this->tratar($this->multElements($e[0][$i], $e[1][$j]));
            }
        }
        $res = "";
        for ($i = 0; $i < count($r); $i++) {
            $res .= $r[$i];
        }
        //$res = $this->somaSemelhantes($res);
        if(count($e)>2) {
            $ex = "($res)";
            for ($i = 2; $i < count($e); $i++) {
                $ex .= "(";
                for ($j = 0; $j < count($e[$i]); $j++) {
                    $ex .= $e[$i][$j];
                }
                $ex .= ")";
            }
            return $this->distributiva($ex);
        } else {
            return $res;
        }
    }
    
    private function reverseSupAll($m) { // Transforma potencia em multiplicação em um monomio
        if(substr_count($m,"<sup>")>0) {
            $m = $this->subElements($m);
            if(isset($m["all"])){
                $all = $m["all"];
            } else {
                $all = $m;
            }
            $r = "";
            for ($i = 0; $i < count($all); $i++) {
                if(substr_count($all[$i],"<sup>")==1) {
                    $r = $this->multElements($r,$this->reverseSup($all[$i]));
                } else {
                    if($r=="") {
                        $r = "1";
                    }
                    $r = $this->multElements($r, $all[$i]);
                }
            }
            return $r;
        } else {
            return $m;
        }
    }
    
    private function multElements($n1,$n2){ // Multiplica dois monomios
        //$this->print .= "<br>[$n1,$n2]";
        if(substr_count($n1,"(")>=2) {
            $n1 = $this->tratar($this->distributiva($n1));
        }
        if(substr_count($n2,"(")>=2) {
            $n2 = $this->tratar($this->distributiva($n2));
        }
        if(substr_count($n1,"(")==1) {
            $n1 = $this->tratar($this->distributiva($n1."(1)"));
        }
        if(substr_count($n2,"(")==1) {
            $n2 = $this->tratar($this->distributiva($n2."(1)"));
        }
        
        if(count($this->sepElements($n1))>1||count($this->sepElements($n2))>1) {
            return "($n1)($n2)";
        }
        $n1 = $this->reverseSupAll($n1);
        $n2 = $this->reverseSupAll($n2);
        $n1 = str_replace("+", "", $n1);
        $n2 = str_replace("+", "", $n2);
        $n1 = str_replace("<sup>1</sup>", "", $n1);
        $n2 = str_replace("<sup>1</sup>", "", $n2);
        
        if(substr_count($n1, "<sup>")>0){
            $n1 = $this->convertExpoente($n1);
        }
        if(substr_count($n2, "<sup>")>0){
            $n2 = $this->convertExpoente($n2);
        }
        $sn1 = $this->soNumero($n1);
        if($sn1==""){
            $sn1 = 1;
        }
        $sn2 = $this->soNumero($n2);
        if($sn2==""){
            $sn2 = 1;
        }
        $c = $sn1*$sn2;// Coeficiente numerico
        if($c>0){
            $c = "+".$c;
        }
        if((substr_count($n1,"-")>0&&substr_count($n2,"-")==0)xor(substr_count($n2,"-")>0&&substr_count($n1,"-")==0)){
            $n1 = str_replace("-", "", $n1);
            $n2 = str_replace("-", "", $n2);
            $c = str_replace("+", "", $c);
            $c = "-".$c;
        }
        $plt = $this->parteLiteral($n1).$this->parteLiteral($n2);// Parte literal total
        if($plt!=""){
            $plts = str_split($plt);
            $pl = "";// Parte literal final
            $epl = [];// expoente da parte literal
            for($i=0;$i<count($plts);$i++){
                if(substr_count($pl,$plts[$i])==0){
                    $epl[$i] = substr_count($plt, $plts[$i]);
                    $pl .= $plts[$i];
                }
            }
            $pls = str_split($pl);// Parte literal final array
            $plr = "";// Resultado parte literal
            $ax = -1;
            for($i=0;$i<=max(array_keys($epl));$i++){
                if(!isset($epl[$i])&&($ax<0)){
                    $ax = $i;
                }
                if(($ax>=0)&&isset($epl[$i])){
                    $epl[$ax] = $epl[$i];
                    unset($epl[$i]);
                    $ax = -1;
                }
            }
            for($i=0;$i<count($epl);$i++){
                if(isset($epl[$i])){
                    $plr .= $pls[$i]."<sup>".$epl[$i]."</sup>";
                }
            }
            //$plr = str_replace("<sup>1</sup>", "", $plr);
            $v = $c.$plr;// Resultado da multiplicação de monomios
        }else{
            $v = $c;
        }
        return $v;
    }
    
    private function parteLiteral($ex){ // Retorna a parte literal de um elemento
        $exs = str_split($ex);
        $v = "";
        for($i=0;$i<count($exs);$i++){
            if($this->soLetra($exs[$i])!=""){
                $v .= $exs[$i];
            }
        }
        return $v;
    }
    private function convertExpoente($m){ // Transforma o expoente de uma icognita em icognata repitidas
        $m = $this->showExpoente($m);
        $pn = $this->parteNumerica($m);// Parte numerica
        $pl = str_replace($pn, "", $m);// Parte literal
        $pla = str_split($pl);// Parte literal array
        $ea = [];// Expoentes array
        $c = 0;// Contador
        for($i=0;$i<count($pla);$i++){
            if(!isset($ea[$c])){
                $ea[$c] = "";
            }
            if(is_numeric($pla[$i])){
                $ea[$c] .= $pla[$i];
            }
            if(!is_numeric($pla[$i])&&$ea[$c]!=""){
                $c++;
            }
        }
        $ea = $this->orgArray($ea);
        for($i=0;$i<count($ea);$i++){
            if($ea[$i]==""){
                unset($ea[$i]);
                $ea = $this->orgArray($ea);
            }
        }
        for($i=0;$i<10;$i++){
            $pl = str_replace($i, "", $pl);
        }
        $pl = str_replace("<sup></sup>", "", $pl);
        $pla = str_split($pl);// Parte literal array
        if(count($pla)==count($ea)){
            for($i=0;$i<count($pla);$i++){
                $pl = str_replace($pla[$i], $this->repeteTxt($pla[$i], $ea[$i]), $pl);
            }
        }
        return $pn.$pl;
    }
    
    private function repeteTxt($txt,$v){ // Repete um texto um numero de vezes
        $t = "";
        for($i=0;$i<$v;$i++){
            $t .= $txt;
        }
        return $t;
    }
    
    private function isSimilar($m1,$m2){
        if(!is_numeric($m1)&&!is_numeric($m2)){
            $m1 = $this->convertExpoente($this->showExpoente($m1));
            $m2 = $this->convertExpoente($this->showExpoente($m2));
            $pl1 = str_replace($this->parteNumerica($m1), "", $m1);// Parte literal 1
            $pl2 = str_replace($this->parteNumerica($m2), "", $m2);// Parte literal 2
            
            $pla1 = [];// Array da parte literal 1
            for($i=0;$i<=strlen($pl1);$i++){
                $pla1[] = $this->repeteTxt(substr($pl1, 0, 1), substr_count($pl1, substr($pl1, 0, 1)));
                $pl1 = str_replace(substr($pl1, 0, 1), "" , $pl1);
            }
            $pla2 = [];// Array da parte literal 2
            for($i=0;$i<=strlen($pl2);$i++){
                $pla2[] = $this->repeteTxt(substr($pl2, 0, 1), substr_count($pl2, substr($pl2, 0, 1)));
                $pl2 = str_replace(substr($pl2, 0, 1), "" , $pl2);
            }
            $a = 0;// Icognitas aprovadas IGUAIS
            if(count($pla1)==count($pla2)){
                for($i=0;$i<count($pla1);$i++){
                    if(in_array($pla1[$i], $pla2)){
                        $a++;
                    }
                }
                if($a==count($pla1)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
            
        }elseif((!is_numeric($m1)&&is_numeric($m2))xor(is_numeric($m1)&&!is_numeric($m2))){
            return false;
        }else{
            return true;
        }
    }
    
    private function somaSemelhantes($p){ // Soma todos os monomios semelhantes de um polinomio
        $pa = $this->sepElements($p, "=");// Polinomio array
        $pao = [];// Polinomio array organizado
        for($i=0;$i<count($pa);$i++){
            $pao[$i] = "";
            for($j=0;$j<count($pa);$j++){
                if($this->isSimilar($pa[$i], $pa[$j])){
                    $pao[$i] .= $pa[$j];
                }
            }
        }
        $pao = $this->delCopyArray($pao);
        for($i=0;$i<count($pao);$i++){
            $ms = $this->sepElements($pao[$i], "=");
            if(count($ms)>1){
                $pl = str_replace($this->parteNumerica($ms[0]), "", $ms[0]);// Parte literal
                $s = 0;
                for($j=0;$j<count($ms);$j++){
                    $s += $this->parteNumerica($ms[$j]);
                }
                if($s>0){
                    $s = "+$s";
                }
                if($s!=0){
                   $pao[$i] = $s.$pl; 
                }else{
                    unset($pao[$i]);
                    $pao = $this->orgArray($pao);
                }
            }
        }
        $r = "";
        for($i=0;$i<count($pao);$i++){
            $r .= $pao[$i];
        }
        return $r;
    }
    
    protected function orgArray($a){ // coloca todos os indices em ordem
        $p = [];// Polinomio
        if(count($a)>0){
            for($i=0;$i<=max(array_keys($a));$i++){
                if(isset($a[$i])){
                    $p[] = $a[$i];
                }
            }
        }
        return $p;
    }
    
    private function showExpoente($m){ // Explicita os expoentes em monomio
        $pn = $this->parteNumerica($m);// Parte numerica
        $pl = str_replace($pn, "", $m);// Parte literal
        $pl = str_replace("<sup>", "", $pl);
        $pl = str_replace("</sup>", ";", $pl);
        $pla = explode(";", $pl);// Parte literal array
        for($i=0;$i<count($pla);$i++){
            if(strlen($pla[$i])>2||$this->soNumero($pla[$i])==""){
                $pm = str_split($pla[$i]);// Parte do monomio
                for($j=0;$j<count($pm);$j++){
                    if($this->isLetra($pm[$j])){
                        if(isset($pm[$j+1])){
                            if($this->isLetra($pm[$j+1])){
                                $pm[$j] .= "1;";
                            }
                        }else{
                            $pm[$j] .= "1;";
                        }
                    }
                }
                $pla[$i] = "";
                for($j=0;$j<count($pm);$j++){
                    $pla[$i] .= $pm[$j];
                }
                $pm = [];
            }
        }
        $pl = "";
        for($i=0;$i<count($pla);$i++){
            $pl .= $pla[$i].";";
        }
        $pla = explode(";", $pl);
        for($i=0;$i<=count($pla);$i++){
            if(isset($pla[$i])){
                if($pla[$i]==""){
                    unset($pla[$i]);
                }
            }
            $pla = $this->orgArray($pla);
        }
        for($i=0;$i<count($pla);$i++){
            $pla[$i] = str_replace($this->soNumero($pla[$i]), "<sup>".$this->soNumero($pla[$i])."</sup>", $pla[$i]);
        }
        $pl = "";
        for($i=0;$i<count($pla);$i++){
            $pl .= $pla[$i];
        }
        return $pn.$pl;
    }
    
    private function isLetra($c){ // Verifica se o caractere informado e uma letra
        if(($c>="a"&&$c<="z")||($c>="A"&&$c<="Z")){
            return true;
        }else{
            return false;
        }
    }
    
    private function parteNumerica($m){ // Retorna a parte numerica de um monomio
        $ma = str_split($m);// Monomio array
        $pn = "";// Parte numerica
        for($i=0;$i<count($ma);$i++){
            if(!$this->isLetra($ma[$i])){
                $pn .= $ma[$i];
            }else{
                break;
            }
        }
        return $pn;
    }
    
    private function delCopyArray($a){ // Deleta elementos copiados de um array
        $v = [];
        for($i=0;$i<count($a);$i++){
            if(!in_array($a[$i], $v)){
                $v[] = $a[$i];
            }
        }
        return $v;
    }
    
    private function soLetra($ex){ // Somente letras
        $aex = str_split($ex);
        $r = "";
        for($i=0;$i<count($aex);$i++){
            if($this->isLetra($aex[$i])){
                $r .= $aex[$i];
            }
        }
        return $r;
    }

    private function calcEquacao1($ex){ // Calcula a equação do 1°grau
        $ex = $this->sees($ex);
        $l = $this->soLetra($ex);
        $l = $l{0};
        $ex = $this->comNum($this->sepElements($ex));
        if($this->soLetra($this->toString($this->sepArray($this->sepElements($ex),"=",1)))==""){
            $ex = $this->toString($this->sepArray($this->sepElements($ex),"=",-1))."=".$this->expressaoCalc($this->toString($this->sepArray($this->sepElements($ex),"=",1)));
        }
        if($this->soLetra($this->toString($this->sepArray($this->sepElements($ex),"=",-1)))==""){
            $ex = $this->expressaoCalc($this->toString($this->sepArray($this->sepElements($ex),"=",-1)))."=".$this->toString($this->sepArray($this->sepElements($ex),"=",1));
        }
        $ex = str_replace("*", "", $ex);
        $ex = $this->orgElements($this->sepElements($ex),"=");
        $ex = $this->somaElements($this->sepElements($ex),"=");
        $e = $this->sepElements($ex);
        if($e[0]<0){
            $ex = $this->inverteEqc($e);
        }
        $s = $ex;
        $ex = $this->trocaCoeficiente($this->sepElements($ex),true,"=");
        $ex = str_replace($l."=","",$ex);
        $ex += 0;
        if(is_int($ex)){
            // var_dump($ex);
            return $ex;
        }else{
            $s = $this->trocaCoeficiente($this->sepElements($s,"="),false,"=");
            $s = str_replace("<td>".$l."</td><td>=</td>","",$s);
            // var_dump($s);
            return $s;
        }
    }

    private function orgEquacao2($e){// Organiza uma equação do 2° grau
        $e1 = $this->sepArray($e, "=", -1);
        $e2 = $this->sepArray($e, "=", 1);
        $ex = "";
        if(count($e2)==1&&$e2[0]=="0"){
            for($i=0;$i<count($e);$i++){
                $ex .= $e[$i];
            }
            return $ex;
        }else{
            for($i=0;$i<count($e2);$i++){
                $e1[] = str_replace("<sup>1</sup>","",$this->multElements($e2[$i],"-1"));
            }
            for($i=0;$i<count($e1);$i++){
                $ex .= $e1[$i];
            }
            return $ex."=0";
        }
    }

    private function eqc2Valida($ex){// Verifica se é uma equação do segundo grau
        $ex = $this->sees($ex);
        $ex = str_replace("*", "", $ex);
        $ex = $this->comNum($this->sepElements($ex));
        $ae = $this->sepElements($ex);
        for($i=0;$i<count($ae);$i++){
            if(substr_count($ae[$i],"(")>0){
                $ae[$i] = str_replace("<sup>1</sup>", "", $this->distributiva($ae[$i]));
            }
        }
        $ex = "";
        for($i=0;$i<count($ae);$i++){
            $ex .= $ae[$i];
        }
        //Icognita
        $l = $this->letras($ex);
        $l = str_replace("sup" ,"" ,$l);
        $l = $l{0};
        if(substr_count($ex,$l.$l)>0||substr_count($ex,$l."<sup>2</sup>")>0){
            return true;
        }else{
            return false;
        }
    }

    private function icognita($ex){// Retorna a icognita de uma equação
        $l = $ex;
        $l = str_replace("<sup>" ,"" ,$l);
        $l = str_replace("</sup>" ,"" ,$l);
        $l = $this->letras($ex);
        $l = substr($l,0,1);
        return $l;
    }

    private function showEquacao2($ex){// Exibe o calculo de uma equação do 2° grau
        $this->print .= "<div id='eqcres'>";
        $ex = $this->sees($ex);
        $ex = str_replace("*", "", $ex);
        $ex = $this->comNum($this->sepElements($ex));
        $antes = $ex;
        $this->print .= "$ex<br />";
        $ae = $this->sepElements($ex);
        for($i=0;$i<count($ae);$i++){
            if(substr_count($ae[$i],"(")>0){
                $ae[$i] = str_replace("<sup>1</sup>", "", $this->distributiva($ae[$i]));
            }
        }
        $ex = "";
        for($i=0;$i<count($ae);$i++){
            $ex .= $ae[$i];
        }
        if($ex!=$antes){
            $this->print .= "$ex<br />";
        }
        $antes = $ex;
        $ex = $this->orgEquacao2($this->sepElements($ex));
        if($ex!=$antes){
            $this->print .= "$ex<br />";
        }
        $antes = $ex;
        $soma = str_replace("=0","",$ex);
        $ex = $this->somaSemelhantes($soma)."=0";
        if(!$this->eqc2Valida($ex)){
            $this->showEquacao1($ex);
        }else{
            if($ex!=$antes){
                $this->print .= "$ex<br />";
            }
            // Coeficientes
            $c = $this->eqc2Coeficientes($ex);
            $A = str_replace("(", "", $c["a"]);
            $A = str_replace(")", "", $A)*1;
            $B = str_replace("(", "", $c["b"]);
            $B = str_replace(")", "", $B)*1;
            $C = str_replace("(", "", $c["c"]);
            $C = str_replace(")", "", $C)*1;
            // Icognita
            $l = $this->icognita($ex);
            // Calculando o delta
            $delta = $B*$B-4*$A*$C;
            $this->print .= "&Delta; = ".$c["b"]."<sup>2</sup>-4*".$c["a"]."*".$c["c"]."<br />";
            $this->print .= "&Delta; = $delta<br />";
            if($delta<0){
                $this->print .= "$l &notin; &real;";
            }else{
                $this->print .= "<table class='ctr'><tr><td>$l = </td><td>";
                $this->print .= $this->showFracao("-($B)&plusmn;".$this->showRaiz2($delta),"2*$A");
                $this->print .= "</td></tr></table>";
                if($delta>0){
                    $numerador1 = ($B*(-1))+sqrt($delta);
                    $numerador2 = ($B*(-1))-sqrt($delta);
                    $denominador = 2*$A;
                    // X1
                    $this->print .= "<table class='ctr'><tr><td>$l<sub>1</sub> = </td><td>";
                    $this->print .= $this->showFracao(($B*(-1))."+".$this->showRaiz2($delta),"2*$A");
                    $this->print .= "</td></tr></table>";
                    $this->print .= "<table class='ctr'><tr><td>$l<sub>1</sub> = </td><td>";
                    $this->print .= $this->showFracao(($B*(-1))."+".$this->formatNum(sqrt($delta)),2*$A);
                    $this->print .= "</td><td> = </td><td>";
                    $this->print .= $this->showFracao(($B*(-1))+$this->formatNum(sqrt($delta)),2*$A);
                    $this->print .= "</td><td> = ".$this->formatNum($numerador1/$denominador)."</td></tr></table>";
                    // X2
                    $this->print .= "<table class='ctr'><tr><td>$l<sub>2</sub> = </td><td>";
                    $this->print .= $this->showFracao(($B*(-1))."-".$this->showRaiz2($delta),"2*$A");
                    $this->print .= "</td></tr></table>";
                    $this->print .= "<table class='ctr'><tr><td>$l<sub>2</sub> = </td><td>";
                    $this->print .= $this->showFracao(($B*(-1))."-".$this->formatNum(sqrt($delta)),2*$A);
                    $this->print .= "</td><td> = </td><td>";
                    $this->print .= $this->showFracao(($B*(-1))-$this->formatNum(sqrt($delta)),2*$A);
                    $this->print .= "</td><td> = ".$this->formatNum($numerador2/$denominador)."</td></tr></table>";
                }else{
                    $this->print .= "<table class='ctr'><tr><td>$l = </td><td>";
                    $this->print .= $this->showFracao(($B*(-1))."&plusmn;$delta","2*$A");
                    $this->print .= "</td><td> = </td><td>";
                    $this->print .= $this->showFracao(($B*(-1)),2*$A);
                    $this->print .= "</td><td> = </td><td>";
                    $this->print .= $this->formatNum(($B*(-1))/(2*$A));
                    $this->print .= "</td></tr></table>";
                }
            }
        }
    }

    protected function showFracao($n,$d){
        return "<table class='frc'><tbody><tr><td class='btmBorder'>$n</td></tr><tr><td>$d</td></tr></tbody></table>";
    }

    private function formatNum($n){
        if(is_int($n)||substr_count($n,".")==0){
            return $n;
        }else{
            return number_format($n,2,".",",");
        }
    }

    private function eqc2Coeficientes($ex){// Retorna um array com os coeficientes de uma equação do 2° grau
        $ex = str_replace("=0","",$ex);
        $e = $this->sepElements($ex);
        //Icognita
        $l = $this->letras($ex);
        $l = str_replace("sup" ,"" ,$l);
        $l = $l{0};
        $a = [];
        for($i=0;$i<count($e);$i++){
            if(substr_count($e[$i],$l."<sup>2</sup>")>0||substr_count($e[$i],$l.$l)>0){
                $e[$i] = str_replace($l."<sup>2</sup>", "", $e[$i]);
                $e[$i] = str_replace($l.$l, "", $e[$i]);
                $e[$i] = str_replace("+", "", $e[$i]);
                $a["a"] = ($e[$i]<0)?"(".$e[$i].")":$e[$i];
            }elseif(substr_count($e[$i],$l)>0){
                $e[$i] = str_replace($l, "", $e[$i]);
                $e[$i] = str_replace("+", "", $e[$i]);
                $a["b"] = ($e[$i]<0)?"(".$e[$i].")":$e[$i];
            }else{
                $e[$i] = str_replace("+", "", $e[$i]);
                $a["c"] = ($e[$i]<0)?"(".$e[$i].")":$e[$i];
            }
        }
        if(!isset($a["a"])){
            $a["a"] = "0";
        }
        if(!isset($a["b"])){
            $a["b"] = "0";
        }
        if(!isset($a["c"])){
            $a["c"] = "0";
        }
        return $a;
    }

    private function elevadoa2($e){
        for($i=0;$i<count($e);$i++){
            if(substr_count($e[$i], "²")>0){
                if(substr_count($e[$i], "(")>0){
                    $e[$i] = str_replace("²", "", $e[$i]);
                    $e[$i] = $e[$i].$e[$i];
                }else{
                    $e[$i] = str_replace($this->icognita($e[$i])."²", $this->icognita($e[$i]).$this->icognita($e[$i]), $e[$i]);
                }
            }
        }
        $ex = "";
        for($i=0;$i<count($e);$i++){
            $ex .= $e[$i];
        }
        return $ex;
    }

    private function toString($a){// Transforma um array em string
        $str = "";
        for($i=0;$i<count($a);$i++){
            $str .= $a[$i];
        }
        return $str;
    }

    private function showMmc($ex){// Mostra o calculo do mmc
        $n = explode(";", $ex);
        if($this->soLetra($ex)=="") {
            $this->print .= "<table id='tabres' style='width:".(19*(strlen($ex)-substr_count($ex,";"))+19)."px;'>";
            $d = 2;// divisivel
            $q = 0;// Quantidade de divisao
            $nt = $n;// Numeros para teste
            $r = [];
            while (!$this->igualArray($n,"1")){
                for($i=0;$i<count($nt);$i++){
                    if(is_int($nt[$i]/$d)){
                        $nt[$i] /= $d;
                        $q++;
                    }
                }
                if($q==0){
                    $d++;
                    continue;
                }
                $r[] = $d;
                $q = 0;
                $this->print .= "<tr>";
                for($i=0;$i<count($n);$i++){
                    $this->print .= "<td>".$n[$i]."</td>";
                    if($i+1<count($n)){
                        $this->print .= "<td>,</td>";
                    }
                }
                $this->print .= "<td class='apb'>$d</td></tr>";
                $n = $nt;
            }
            $this->print .= "<tr>";
            for($i=0;$i<count($n);$i++){
                $this->print .= "<td>".$n[$i]."</td>";
                if($i+1<count($n)){
                    $this->print .= "<td>,</td>";
                }
            }
            $res = $r[0];
            for($i=1;$i<count($r);$i++){
                $res *= $r[$i];
            }
            $this->print .= "<td class='apb rbt'>$res</td></tr></table>";
        }
    }

    private function mmc($ex){// Calcula o mmc
        $n = explode(";", $ex);
        if($this->soLetra($ex)=="") {
            $d = 2;// divisivel
            $q = 0;// Quantidade de divisao
            $nt = $n;// Numeros para teste
            $r = [];
            while (!$this->igualArray($n,"1")){
                for($i=0;$i<count($nt);$i++){
                    if(is_int($nt[$i]/$d)){
                        $nt[$i] /= $d;
                        $q++;
                    }
                }
                if($q==0){
                    $d++;
                    continue;
                }
                $r[] = $d;
                $q = 0;
                $n = $nt;
            }
            $res = $r[0];
            for($i=1;$i<count($r);$i++){
                $res *= $r[$i];
            }
            $this->setResult($res);
        } else {
            $res = "1";
            for ($i = 0; $i < count($n); $i++) {
                $res = $this->multElements($res, $n[$i]);
            }
        }
        return $res;
    }

    private function showMdc($ex){// Mostra o calculo do mdc
        $n = explode(";", $ex);
        $this->print .= "<table id='tabres' style='width:".(19*(strlen($ex)-substr_count($ex,";"))+19)."px;'>";
        $d = 2;// divisivel
        $q = 0;// Quantidade de divisao
        $nt = $n;// Numeros para teste
        $r = [];
        $fta = false;// Fator atual BOOLEAN
        while (!$this->igualArray($n,"1")){
            for($i=0;$i<count($nt);$i++){
                if(is_int($nt[$i]/$d)){
                    $nt[$i] /= $d;
                    $q++;
                }
            }
            if($q==count($nt)){
                $r[] = $d;
                $fta = true;
            }
            if($q==0){
                $d++;
                continue;
            }
            $q = 0;
            $this->print .= "<tr>";
            for($i=0;$i<count($n);$i++){
                $this->print .= "<td>".$n[$i]."</td>";
                if($i+1<count($n)){
                    $this->print .= "<td>,</td>";
                }
            }
            if($fta){
                $this->print .= "<td class='apb numDtq'>$d</td></tr>";
            }else{
                $this->print .= "<td class='apb'>$d</td></tr>";
            }
            $fta = false;
            $n = $nt;
        }
        $this->print .= "<tr>";
        for($i=0;$i<count($n);$i++){
            $this->print .= "<td>".$n[$i]."</td>";
            if($i+1<count($n)){
                $this->print .= "<td>,</td>";
            }
        }
        if(isset($r[0])){
            $res = $r[0];
            for($i=1;$i<count($r);$i++){
                $res *= $r[$i];
            }
        }else{
            $res = 1;
        }
        $this->print .= "<td class='apb rbt'>$res</td></tr></table>";
    }

    private function mdc($ex){// Calcula o mdc
        $n = explode(";", $ex);
        if($this->soLetra($ex)==""){
            $d = 2;// divisivel
            $q = 0;// Quantidade de divisao
            $nt = $n;// Numeros para teste
            $r = [];
            $fta = false;// Fator atual BOOLEAN
            while (!$this->igualArray($n,"1")){
                for($i=0;$i<count($nt);$i++){
                    if(is_int($nt[$i]/$d)){
                        $nt[$i] /= $d;
                        $q++;
                    }
                }
                if($q==count($nt)){
                    $r[] = $d;
                    $fta = true;
                }
                if($q==0){
                    $d++;
                    continue;
                }
                $q = 0;
                $fta = false;
                $n = $nt;
            }
            if(isset($r[0])){
                $res = $r[0];
                for($i=1;$i<count($r);$i++){
                    $res *= $r[$i];
                }
            }else{
                $res = 1;
            }
            $this->setResult($res);
        }else {
            $res = 1;
        }
        return $res;
    }

    private function igualArray($ary,$str){// Verifica se todos os elementos do array é igual a str
        for($i=0;$i<count($ary);$i++){
            if($ary[$i]!=$str){
                return false;
            }
        }
        return true;
    }

    private function radiciacao($ex){// Mostra o calculo de uma radiciação
        if(substr_count($ex,"[")==0&&substr_count($ex,"]")==0&&substr_count($ex,"{")==0&&substr_count($ex,"}")==0){
            $frc = substr(substr($ex,strpos($ex, "<sup>")+5,strpos($ex,"</sup>")-(strpos($ex, "<sup>")+5)),1,-1);
            $indice = explode("/", $frc)[1];
            $n = pow(str_replace(substr($ex,strpos($ex,"<sup>"),(strpos($ex,"</sup>")+6)-strpos($ex,"<sup>")), "", $ex),explode("/", $frc)[0]);
            $num = $n;
            $this->print .= "<table id='tabres' class='tableCenter'>";
            $d = 2;// divisivel
            $q = 0;// Quantidade de divisao
            $r = [];
            $nt = $n;
            while ($n>1){
                if(is_int($nt/$d)){
                    $nt /= $d;
                    $q++;
                }
                if($q==0){
                    $d++;
                    continue;
                }
                $r[] = $d;
                $q = 0;
                $this->print .= "<tr>";
                $this->print .= "<td>".$n."</td>";
                $this->print .= "<td class='apb'>$d</td></tr>";
                $n = $nt;
            }
            $this->print .= "<tr><td>$n</td><td class='apb'> </td></tr></table><br />";
            $externo = [];
            $interno = [];
            $arr = array_count_values($r);
            foreach ($arr as $key => $value) {
                $ax = 0;
                while ($indice<=$value-$ax) {
                    $externo[] = $key;
                    $ax += $indice;
                }
                if($value-$ax>0) {
                    $interno[] = $key."<sup>".($value-$ax)."</sup>";
                }
            }
            $this->print .= "<sup>$indice</sup><span class='radical'>&radic;</span><span class='radicando'>";
            for($i=0;$i<count($externo);$i++){
                $this->print .= $externo[$i]."<sup><del>$indice</del></sup>".($i+1==count($externo)?"":"*");
            }
            if(isset($externo[0])&&isset($interno[0])){
                $this->print .= "*";
            }
            for($i=0;$i<count($interno);$i++){
                $this->print .= $interno[$i].($i+1==count($interno)?"":"*");
            }
            $this->print .= "</span>";
            if(count($externo)>0){
                $this->print .= " = ";
            }
            for($i=0;$i<count($externo);$i++){
                $this->print .= $externo[$i].($i+1==count($externo)?"":"*");
            }
            if(isset($externo[0])&&isset($interno[0])){
                $this->print .= "*";
                $this->print .= "<sup>$indice</sup><span class='radical'>&radic;</span><span class='radicando'>";
                for($i=0;$i<count($interno);$i++){
                    $this->print .= $interno[$i].($i+1==count($interno)?"":"*");
                }
                $this->print .= "</span>";
            }
            $this->print .= " = ".pow($num,1/$indice);
        }
    }

    private function stripos_count($str, $txt, $c){ // Encontra a ocorrencia desejada dentro de uma string
        $pos = 0;
        $texto = substr($str,0,stripos($str,$txt)+1);
        $del = $texto;
        if(substr_count($str,$txt)>=$c){
            for($i=0;$i<$c;$i++){
                $pos += strlen($texto);
                $texto = substr(str_replace($del, "", $str),0,stripos(str_replace($del, "", $str),$txt)+1);
                $del .= $texto;
            }
        }else{
            return -1;
        }
        return $pos-1;
    }

    private function showRgr3sp($ex){ // Mostra o calculo da Regra de 3 simples
        $ex = str_replace("[rga3smp]", "", $ex);
        $ppc = "";// Diratamente proporcional ou invesamente propocional
        if(substr_count($ex, "|dp\"")==1){
            $ppc = "dp";
            $ex = str_replace("dp\"", "", $ex);
        }elseif(substr_count($ex, "|ip\"")==1){
            $ppc = "ip";
            $ex = str_replace("ip\"", "", $ex);
        }
        $gdz1 = substr($ex,stripos($ex,"\""),stripos($ex,":")+1);// Grandeza 1
        $ex = str_replace($gdz1, "", $ex);
        $gdz1 = str_replace("\"", "", $gdz1);
        $gdz1 = str_replace(":", "", $gdz1);
        $col1 = substr($ex, 0, stripos($ex, ";"));// Primeira coluna (grandeza1)
        $ex = str_replace($col1.";","",$ex);
        $col1 = explode(",", $col1);
        $gdz2 = substr($ex, 0, stripos($ex, ":"));// Grandeza 2
        $ex = str_replace($gdz2.":", "", $ex);
        $col2 = substr($ex, 0, stripos($ex,"|"));// SEgunda coluna (grandeza 2)
        $ex = str_replace($col2, "", $ex);
        $col2 = explode(",",$col2);
        $ex = str_replace("|", "", $ex);
        $seta1 = "";// SRC para a primeira seta
        $seta2 = "";// SRC para a segunda seta
        if($col1[0]!="x"&&$col1[1]!="x"){
            if($col1[0]>$col1[1]){
                $seta1 = "seta-para-cima.png";
            }elseif($col1[0]<$col1[1]){
                $seta1 = "seta-para-baixo.png";
            }
        }else{
            if($col2[0]>$col2[1]){
                if($ppc=="dp"){
                    $seta1 = "seta-para-cima.png";
                }
                if($ppc=="ip"){
                    $seta1 = "seta-para-baixo.png";
                }
            }elseif($col2[0]<$col2[1]){
                if($ppc=="dp"){
                    $seta1 = "seta-para-baixo.png";
                }
                if($ppc=="ip"){
                    $seta1 = "seta-para-cima.png";
                }
            }
        }
        if($col2[0]!="x"&&$col2[1]!="x"){
            if($col2[0]>$col2[1]){
                $seta2 = "seta-para-cima.png";
            }elseif($col2[0]<$col2[1]){
                $seta2 = "seta-para-baixo.png";
            }
        }else{
            if($col1[0]>$col1[1]){
                if($ppc=="dp"){
                    $seta2 = "seta-para-cima.png";
                }
                if($ppc=="ip"){
                    $seta2 = "seta-para-baixo.png";
                }
            }elseif($col1[0]<$col1[1]){
                if($ppc=="dp"){
                    $seta2 = "seta-para-baixo.png";
                }
                if($ppc=="ip"){
                    $seta2 = "seta-para-cima.png";
                }
            }
        }
        $this->print .= "<table class='tabela'><tr><td rowspan='3' class='strg3'>";
        $this->print .= ($seta1!="")?"<img src='../_imagens/$seta1' draggable='false'/>":"";
        $this->print .= "</td><th>$gdz1</th><th>$gdz2</th><td rowspan='3' class='strg3'>";
        $this->print .= ($seta2!="")?"<img src='../_imagens/$seta2'  draggable='false'/>":"";
        $this->print .= "</td></tr><tr><td class='tdtbl'>".$col1[0]."</td><td class='tdtbl'>".$col2[0]."</td></tr><tr><td class='tdtbl'>".$col1[1]."</td><td class='tdtbl'>".$col2[1]."</td></tr></table>";

        switch ($ppc) {
            case 'dp':
                $this->print .= "(Diretamente proporcional)";
            break;
            
            case 'ip':
                $this->print .= "(Inversamente proporcional)";
            break;
        }
        $this->print .= "<br /><br />";

        $this->print .= "<table class='tableCenter'><tr><td>";
        $this->print .= $this->showFracao($col1[0],$col1[1])."</td>";
        $this->print .= "<td> = </td><td>";
        $this->print .= ($ppc=="ip")?$this->showFracao($col2[1],$col2[0]):$this->showFracao($col2[0],$col2[1]);
        $this->print .= "</td></tr></table><br />";
        $ax = "";
        if($ppc=="ip"){
            $ax = $col2[0];
            $col2[0] = $col2[1];
            $col2[1] = $ax;
        }
        $ax = "";
        $parte1 = "";// Primeira parte da equação lado esquerdo
        $parte2 = "";// Segunda parte da equação lado direito
        if($col1[0]=="x"||$col2[1]=="x"){
            if($col1[0]=="x"){
                $parte1 = $col2[1].$col1[0];
            }else{
                $parte1 = $col1[0].$col2[1];
            }
        }else{
            $parte1 = $col1[0]."*".$col2[1];
        }
        if($col1[1]=="x"||$col2[0]=="x"){
            if($col1[1]=="x"){
                $parte2 = $col2[0].$col1[1];
            }else{
                $parte2 = $col1[1].$col2[0];
            }
        }else{
            $parte2 = $col1[1]."*".$col2[0];
        }
        $this->showEquacao1($parte1."=".$parte2);
        $x = $this->calcEquacao1($parte1."=".$parte2);
        if(substr_count($x ,"&div;")){
            $x = "<strong>".$this->expressaoCalc(str_replace("&div;", "/", $x))."</strong>";
        }else{
            $x = "<strong>".$x."</strong>";
        }
        if($col1[0]=="x"){
            $col1[0] = $x;
        }elseif($col1[1]=="x"){
            $col1[1] = $x;
        }elseif($col2[0]=="x"){
            $col2[0] = $x;
        }elseif($col2[1]=="x"){
            $col2[1] = $x;
        }
        if($ppc=="ip"){
            $ax = $col2[0];
            $col2[0] = $col2[1];
            $col2[1] = $ax;
        }
        $ax = "";
        $this->print .= "<br />";
        $this->print .= "<table class='tabela'><tr><th>$gdz1</th><th>$gdz2</th></tr><tr><td class='tdtbl'>".$col1[0]."</td><td class='tdtbl'>".$col2[0]."</td></tr><tr><td class='tdtbl'>".$col1[1]."</td><td class='tdtbl'>".$col2[1]."</td></tr></table>";
    }

    private function showJuros($ex){ // Mostra o calculo do Juros
        // Coleta de dados
        $tipoJuros = "";
        $capital = "";
        $montante = "";
        $juros = "";
        $taxa = "";
        $tipoTaxa = "";
        $tipoTempo = "";
        $tempo = "";
        if(!$this->isJson($ex)) {
            $tipoJuros = substr($ex,stripos($ex, "["),5);
            $ex = str_replace($tipoJuros, "", $ex);
            $ex = str_replace("\"", "", $ex);
            $ex = str_replace("'", "", $ex);
            $ex = str_replace("|", "", $ex);
            $campos = explode(";",$ex);
            $capital = $campos[0];
            $montante = $campos[1];
            $juros = $campos[2];
            $taxa = $campos[3];
            $tipoTaxa = $this->soLetra($taxa);
            $taxa = str_replace($tipoTaxa, "", $taxa);
            $tempo = $campos[4];
            $tipoTempo = $this->soLetra($tempo);
            $tempo = str_replace($tipoTempo, "", $tempo);
        } else {
            $r = json_decode($ex);
            $tipoJuros = $r->tipoJuros;
            $capital = $r->capital;
            $montante = $r->montante;
            $juros = $r->juros;
            $taxa = $r->taxa;
            $tipoTaxa = $r->tipoTaxa;
            $tipoTempo = $r->tipoTempo;
            $tempo = $r->tempo;
        }
        $showTipoTaxa = "";
        switch ($tipoTaxa) {
            case 'ad':
                $showTipoTaxa = "Ao dia";
            break;
            
            case 'asn':
                $showTipoTaxa = "A semana";
            break;

            case 'am':
                $showTipoTaxa = "Ao mês";
            break;

            case 'ab':
                $showTipoTaxa = "Ao bimestre";
            break;

            case 'at':
                $showTipoTaxa = "Ao trimestre";
            break;

            case 'asm':
                $showTipoTaxa = "Ao semestre";
            break;

            case 'aa':
                $showTipoTaxa = "Ao ano";
            break;
        }
        $showTipoTempo = "";
        if($tempo>1){
            switch ($tipoTempo) {
                case 'ad':
                    $showTipoTempo = "Dias";
                break;
                
                case 'asn':
                    $showTipoTempo = "Semanas";
                break;

                case 'am':
                    $showTipoTempo = "Meses";
                break;

                case 'ab':
                    $showTipoTempo = "Bimestres";
                break;

                case 'at':
                    $showTipoTempo = "Trimestres";
                break;

                case 'asm':
                    $showTipoTempo = "Semestres";
                break;

                case 'aa':
                    $showTipoTempo = "Anos";
                break;
            }
        }else{
            switch ($tipoTempo) {
                case 'ad':
                    $showTipoTempo = "Dia";
                break;
                
                case 'asn':
                    $showTipoTempo = "Semana";
                break;

                case 'am':
                    $showTipoTempo = "Mês";
                break;

                case 'ab':
                    $showTipoTempo = "Bimestre";
                break;

                case 'at':
                    $showTipoTempo = "Trimestre";
                break;

                case 'asm':
                    $showTipoTempo = "Semestre";
                break;

                case 'aa':
                    $showTipoTempo = "Ano";
                break;
            }
        }
        // Exibindo a primeira tabela
        $this->print .= "<table class='mostraDados tableCenter'><thead><tr><th colspan='2'>Detalhes</th></tr></thead><tbody><tr><td>Capital (C)</td><td>";
        $this->print .= ($capital!="?")?"R$".number_format($capital,2,",","."):$capital;
        $this->print .= "</td></tr>";
        $this->print .= "<tr><td>Montante (M)</td><td>";
        $this->print .= ($montante!="?")?"R$".number_format($montante,2,",","."):$montante;
        $this->print .= "</td></tr>";
        $this->print .= "<tr><td>Juros (J)</td><td>";
        $this->print .= ($juros!="?")?"R$".number_format($juros,2,",","."):$juros;
        $this->print .= "</td></tr>";
        $this->print .= "<tr><td>Taxa (I)</td><td>$taxa% $showTipoTaxa</td></tr>";
        $this->print .= "<tr><td>Tempo (T)</td><td>$tempo $showTipoTempo</td></tr>";
        $this->print .= "<tr><td>Tipo</td><td>";
        $this->print .= (substr_count($tipoJuros,"jsp")>0)?"Juros simples":"Juros compostos";
        $this->print .= "</td></tr></tbody></table><br />";

        $tempo = $this->showConvertTempo($tempo,$taxa,$tipoTempo,$tipoTaxa);
        if($tempo!="?"&&$taxa!="?"){
            $tipoTempo = $tipoTaxa;
        }
        if(($capital!="?"&&$montante!="?")||($capital!="?"&&$juros!="?")||($montante!="?"&&$juros!="?")){
            $this->print .= "<h3>Calculando</h3><br />";
            $this->print .= "M=C+J<br />";
            $this->showEquacao1(($montante!="?"?$montante:"M")."=".($capital!="?"?$capital:"C")."+".($juros!="?"?$juros:"J"));
            if($montante=="?"){
                $montante = $this->calcEquacao1(($montante!="?"?$montante:"M")."=".($capital!="?"?$capital:"C")."+".($juros!="?"?$juros:"J"));
                $montante = $this->expressaoCalc(str_replace("&div;", "/", $montante));
            }elseif($capital=="?"){
                $capital = $this->calcEquacao1(($montante!="?"?$montante:"M")."=".($capital!="?"?$capital:"C")."+".($juros!="?"?$juros:"J"));
                $capital = $this->expressaoCalc(str_replace("&div;", "/", $capital));
            }else{
                $juros = $this->calcEquacao1(($montante!="?"?$montante:"M")."=".($capital!="?"?$capital:"C")."+".($juros!="?"?$juros:"J"));
                $juros = $this->expressaoCalc(str_replace("&div;", "/", $juros));
            }
        }
        if(substr_count($tipoJuros,"jsp")>0){
            if($capital!="?"&&$taxa!="?"&&$tempo!="?"){
                $this->print .= "<h3>Calculando Juros</h3><br />";
                $this->print .= "<table class='tableCenter'><tr><td>J=</td><td>".$this->showFracao("C*I*T","100")."</td></tr></table>";
                $this->print .= "<table class='tableCenter'><tr><td>J=</td><td>".$this->showFracao("$capital*$taxa*$tempo","100")."</td></tr></table>";
                $this->print .= "<table class='tableCenter'><tr><td>J=</td><td>".$this->showFracao($capital*$taxa*$tempo,"100")."</td></tr></table>";
                $this->print .= "J=".($capital*$taxa*$tempo)/100;
                $juros = ($capital*$taxa*$tempo)/100;
            }elseif($juros!="?"&&$taxa!="?"&&$tempo!="?"){
                $this->print .= "<h3>Calculando capital</h3><br />";
                $this->print .= "<table class='tableCenter'><tr><td>C=</td><td>".$this->showFracao("100J","I*T")."</td></tr></table>";
                $this->print .= "<table class='tableCenter'><tr><td>C=</td><td>".$this->showFracao("100*$juros","$taxa*$tempo")."</td></tr></table>";
                $this->print .= "<table class='tableCenter'><tr><td>C=</td><td>".$this->showFracao(100*$juros,$taxa*$tempo)."</td></tr></table>";
                $this->print .= "C=".(100*$juros)/($taxa*$tempo);
                $capital = is_int((100*$juros)/($taxa*$tempo))?(100*$juros)/($taxa*$tempo):number_format((100*$juros)/($taxa*$tempo),2,".","");
            }elseif($juros!="?"&&$capital!="?"&&$taxa!="?"){
                $this->print .= "<h3>Calculando o tempo</h3><br />";
                $this->print .= "<table class='tableCenter'><tr><td>T=</td><td>".$this->showFracao("100J","I*C")."</td></tr></table>";
                $this->print .= "<table class='tableCenter'><tr><td>T=</td><td>".$this->showFracao("100*$juros","$taxa*$capital")."</td></tr></table>";
                $this->print .= "<table class='tableCenter'><tr><td>T=</td><td>".$this->showFracao(100*$juros,$taxa*$capital)."</td></tr></table>";
                $this->print .= "T=".(100*$juros)/($taxa*$capital)."<br />";
                $tempo = (100*$juros)/($taxa*$capital);
                if(!is_int($tempo)){
                    $tempo = number_format($tempo,2);
                }
                $tempo = $this->showConvertNovoTempo($tempo,$taxa,$tipoTempo,$tipoTaxa);
                if(!is_int($tempo)){
                    $tempo = number_format($tempo,2);
                }
                $tempo *= 1;
            }elseif($capital!="?"&&$juros!="?"&&$tempo!="?"){
                $this->print .= "<h3>Calculando a taxa</h3><br />";
                $this->print .= "<table class='tableCenter'><tr><td>I=</td><td>".$this->showFracao("100J","T*C")."</td></tr></table>";
                $this->print .= "<table class='tableCenter'><tr><td>I=</td><td>".$this->showFracao("100*$juros","$tempo*$capital")."</td></tr></table>";
                $this->print .= "<table class='tableCenter'><tr><td>I=</td><td>".$this->showFracao(100*$juros,$tempo*$capital)."</td></tr></table>";
                $this->print .= "I=".(100*$juros)/($tempo*$capital)."<br />";
                $taxa = (100*$juros)/($tempo*$capital);
            }elseif($montante!="?"&&$taxa!="?"&&$tempo!="?"){
                $this->print .= "<br /><h3>Calculando o capital</h3><br />";
                $this->print .= "M=C(1+I*T)";
                $this->print .= "<table class='tableCenter'><tr><td>".$this->showFracao("M","(1+I*T)")."</td><td>=C</td></tr></table>";
                $this->print .= "<table class='tableCenter'><tr><td>".$this->showFracao($montante,"(1+".($taxa/100)."*$tempo)")."</td><td>=C</td></tr></table>";
                $this->print .= "<table class='tableCenter'><tr><td>".$this->showFracao($montante,(1+($taxa/100)*$tempo))."</td><td>=C</td></tr></table>";
                $this->print .= $montante/(1+($taxa/100)*$tempo)."=C";
                $capital = $montante/(1+($taxa/100)*$tempo);
                $this->print .= "<br /><h3>Calculando os juros</h3><br />";
                $this->print .= "M=C+J";
                $this->showEquacao1("$montante=$capital+J");
                $juros = $this->expressaoCalc(str_replace("&div;", "/", $this->calcEquacao1("$montante=$capital+J")));
            }
        }else{
            if($capital!="?"&&$taxa!="?"&&$tempo!="?"){
                $this->print .= "<h3>Calculando Montante</h3><br />";
                $this->print .= "M=C(1+I)<sup>T</sup><br />";
                $this->print .= "M=$capital(1+".($taxa/100).")<sup>$tempo</sup><br />";
                $this->print .= "M=$capital(".(1+($taxa/100)).")<sup>$tempo</sup><br />";
                $this->print .= "M=$capital*".pow(1+($taxa/100),$tempo)."<br />";
                $this->print .= "M=".($capital*pow(1+($taxa/100),$tempo))."<br />";
                $montante = ($capital*pow(1+($taxa/100),$tempo));
                $this->print .= "<h3>Calculando Juros</h3><br />";
                $this->print .= "M=C+J<br />";
                $this->print .= "J=M-C<br />";
                $this->print .= "J=$montante-$capital<br />";
                $this->print .= "J=".($montante-$capital)."<br />";
                $juros = $montante-$capital;
            }elseif($juros!="?"&&$taxa!="?"&&$tempo!="?"){
                $this->print .= "<h3>Calculando Montante</h3><br />";
                $this->print .= "M=C(1+I)<sup>T</sup><br />";
                $this->print .= "M=$capital(1+".($taxa/100).")<sup>$tempo</sup><br />";
                $this->print .= "M=$capital(".(1+($taxa/100)).")<sup>$tempo</sup><br />";
                $this->print .= "M=$capital*".pow(1+($taxa/100),$tempo)."<br />";
                $this->print .= "M=".($capital*pow(1+($taxa/100),$tempo))."<br />";
                $montante = ($capital*pow(1+($taxa/100),$tempo));
                $this->print .= "<h3>Calculando Capital</h3><br />";
                $this->print .= "M=C+J<br />";
                $this->print .= "C=M-J<br />";
                $this->print .= "C=$montante-$juros<br />";
                $this->print .= "C=".($montante-$juros)."<br />";
                $capital = $montante-$juros;
            }elseif($juros!="?"&&$capital!="?"&&$taxa!="?"&&$montante!="?"){
                $this->print .= "<h3>Calculando o tempo</h3><br />";
                $this->print .= "M=C(1+I)<sup>T</sup><br />";
                $this->print .= "$montante=$capital(1+".($taxa/100).")<sup>T</sup><br />";
                $this->print .= "<table class='tableCenter'><tr><td>".$this->showFracao($montante,$capital)."</td><td>=(1+".($taxa/100).")<sup>T</sup></td></tr></table>";
                $this->print .= ($montante/$capital)."=(1+".($taxa/100).")<sup>T</sup><br />";
                $this->print .= ($montante/$capital)."=".(1+($taxa/100))."<sup>T</sup><br />";
                $this->print .= "log<sub>".(1+($taxa/100))."</sub><sup>".($montante/$capital)."</sup>=T<br />";
                $this->print .= "log<sub>".(1+($taxa/100))."</sub><sup>".($montante/$capital)."</sup>=".log(($montante/$capital),(1+($taxa/100)))."<br />";
                $tempo = log(($montante/$capital),(1+($taxa/100)));
                if(!is_int($tempo)){
                    $tempo = number_format($tempo,2);
                }
                $tempo *= 1;
                $tempo = $this->showConvertNovoTempo($tempo,$taxa,$tipoTempo,$tipoTaxa);
                if(!is_int($tempo)){
                    $tempo = number_format($tempo,2);
                }
                $tempo *= 1;
            }elseif($capital!="?"&&$juros!="?"&&$tempo!="?"){
                $this->print .= "<h3>Calculando a taxa</h3><br />";
                $this->print .= "M=C(1+I)<sup>T</sup><br />";
                $this->print .= $montante."=".$capital."(1+I)<sup>$tempo</sup><br />";
                $this->print .= "<table class='tableCenter'><tr><td>".$this->showFracao($montante,$capital)."</td><td>=(1+I)<sup>$tempo</sup></td></tr></table>";
                $this->print .= ($montante/$capital)."=(1+I)<sup>$tempo</sup><br />";
                $this->print .= "<sup>$tempo</sup>".$this->showRaiz2($montante/$capital)."=1+I<br />";
                $this->print .= pow(($montante/$capital), (1/$tempo))."=1+I<br />";
                $this->print .= pow(($montante/$capital), (1/$tempo))."-1=I<br />";
                $this->print .= (pow(($montante/$capital), (1/$tempo))-1)."=I<br />";
                if(!is_int(pow(($montante/$capital), (1/$tempo))-1)){
                    $this->print .= number_format(pow(($montante/$capital), (1/$tempo))-1,2)."=I<br />";
                    $taxa = number_format(pow(($montante/$capital), (1/$tempo))-1,2);
                }else{
                    $taxa = (pow(($montante/$capital), (1/$tempo))-1);
                }
                $taxa *= 100;
            }elseif($montante!="?"&&$taxa!="?"&&$tempo!="?"){
                $this->print .= "<br /><h3>Calculando o capital</h3><br />";
                $this->print .= "M=C(1+I)<sup>T</sup><br />";
                $this->print .= "$montante=C(1+".($taxa/100).")<sup>$tempo</sup><br />";
                $this->print .= "$montante=C(".(1+($taxa/100)).")<sup>$tempo</sup><br />";
                $this->print .= "$montante=C*".pow(1+($taxa/100),$tempo)."<br />";
                $this->print .= "<table class='tableCenter'><tr><td>".$this->showFracao($montante,pow(1+($taxa/100),$tempo))."</td><td>=C</td></tr></table>";
                $this->print .= $montante/pow(1+($taxa/100),$tempo)."=C<br />";
                $capital = $montante/pow(1+($taxa/100),$tempo);
                $this->print .= "<br /><h3>Calculando os juros</h3><br />";
                $this->print .= "M=C+J";
                $this->showEquacao1("$montante=$capital+J");
                $juros = $this->expressaoCalc(str_replace("&div;", "/", $this->calcEquacao1("$montante=$capital+J")));
            }
        }
        if($montante=="?"){
            $this->print .= "<h3>Calculando o montante</h3><br />";
            $this->print .= "M=C+J<br />";
            $this->print .= "M=$capital+$juros<br />";
            $this->print .= "M=".($capital+$juros)."<br />";
            $montante = ($capital+$juros);
        }
        $showTipoTempo = "";
        if($tempo>1){
            switch ($tipoTempo) {
                case 'ad':
                    $showTipoTempo = "Dias";
                break;
                
                case 'asn':
                    $showTipoTempo = "Semanas";
                break;

                case 'am':
                    $showTipoTempo = "Meses";
                break;

                case 'ab':
                    $showTipoTempo = "Bimestres";
                break;

                case 'at':
                    $showTipoTempo = "Trimestres";
                break;

                case 'asm':
                    $showTipoTempo = "Semestres";
                break;

                case 'aa':
                    $showTipoTempo = "Anos";
                break;
            }
        }else{
            switch ($tipoTempo) {
                case 'ad':
                    $showTipoTempo = "Dia";
                break;
                
                case 'asn':
                    $showTipoTempo = "Semana";
                break;

                case 'am':
                    $showTipoTempo = "Mês";
                break;

                case 'ab':
                    $showTipoTempo = "Bimestre";
                break;

                case 'at':
                    $showTipoTempo = "Trimestre";
                break;

                case 'asm':
                    $showTipoTempo = "Semestre";
                break;

                case 'aa':
                    $showTipoTempo = "Ano";
                break;
            }
        }
        // Exibindo a segunda tabela
        $this->print .= "<br /><br />";
        $this->print .= "<table class='mostraDados tableCenter'><thead><tr><th colspan='2'>Detalhes</th></tr></thead><tbody><tr><td>Capital (C)</td><td>";
        $this->print .= ($capital!="?")?"R$".number_format($capital,2,",","."):$capital;
        $this->print .= "</td></tr>";
        $this->print .= "<tr><td>Montante (M)</td><td>";
        $this->print .= ($montante!="?")?"R$".number_format($montante,2,",","."):$montante;
        $this->print .= "</td></tr>";
        $this->print .= "<tr><td>Juros (J)</td><td>";
        $this->print .= ($juros!="?")?"R$".number_format($juros,2,",","."):$juros;
        $this->print .= "</td></tr>";
        $this->print .= "<tr><td>Taxa (I)</td><td>$taxa% $showTipoTaxa</td></tr>";
        $this->print .= "<tr><td>Tempo (T)</td><td>$tempo $showTipoTempo</td></tr>";
        $this->print .= "<tr><td>Tipo</td><td>";
        $this->print .= (substr_count($tipoJuros,"jsp")>0)?"Juros simples":"Juros compostos";
        $this->print .= "</td></tr></tbody></table><br />";
        if(stripos($tempo, ".")==0&&stripos($tempo, ",")==0){
            $C = $capital;
            $J = 0;
            $M = $C+$J;
            if(substr_count($tipoJuros,"jsp")>0){
                $this->print .= "<table class='tableCenter oqouve'><thead>";
                $this->print .= "<tr class='oqouvetitle1'><th colspan='4'>O que aconteceu durante $tempo $showTipoTempo</th></tr>";
                $this->print .= "<tr class='oqouvetitle2'><th>Período</th><th>Capital</th><th>Juros</th><th>Montante</th></tr></thead><tbody>";
                for($i=0;$i<=$tempo;$i++){
                    $this->print .= "<tr><td>$i</td><td>R$".number_format($C,2,",",".")."</td><td>R$".number_format($J,2,",",".")."</td><td>R$".number_format($M,2,",",".")."</td></tr>";
                    $C = $M;
                    $J = $capital*($taxa/100);
                    $M = $C+$J;
                }
                $this->print .= "</tbody></table>";
            }else{
                $this->print .= "<table class='tableCenter oqouve'><thead>";
                $this->print .= "<tr class='oqouvetitle1'><th colspan='4'>O que aconteceu durante $tempo $showTipoTempo</th></tr>";
                $this->print .= "<tr class='oqouvetitle2'><th>Período</th><th>Capital</th><th>Juros</th><th>Montante</th></tr></thead><tbody>";
                for($i=0;$i<=$tempo;$i++){
                    $this->print .= "<tr><td>$i</td><td>R$".number_format($C,2,",",".")."</td><td>R$".number_format($J,2,",",".")."</td><td>R$".number_format($M,2,",",".")."</td></tr>";
                    $C = $M;
                    $J = $C*($taxa/100);
                    $M = $C+$J;
                }
                $this->print .= "</tbody></table>";
            }
        }
    }

    private function showConvertTempo($tempo,$taxa,$tipoTempo,$tipoTaxa){ // Converte o tempo dos juros
        // Convertendo tempo
        $novoTempo = "";
        if($tempo!="?"&&$taxa!="?"){
            if($tipoTaxa!=$tipoTempo){
                $this->print .= "<h3>Convesão</h3><br />";
                if($tipoTaxa=="ad"){
                    if($tipoTempo=="asn"){
                        $this->showRgr3sp("\"Dias:7,x;Semanas:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*7;
                    }elseif($tipoTempo=="am"){
                        $this->showRgr3sp("\"Dias:30,x;Meses:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*30;
                    }elseif($tipoTempo=="ab"){
                        $this->showRgr3sp("\"Dias:60,x;Bimestres:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*60;
                    }elseif($tipoTempo=="at"){
                        $this->showRgr3sp("\"Dias:90,x;Trimestres:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*90;
                    }elseif($tipoTempo=="asm"){
                        $this->showRgr3sp("\"Dias:180,x;Semestres:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*180;
                    }elseif($tipoTempo=="aa"){
                        $this->showRgr3sp("\"Dias:365,x;Anos:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*365;
                    }
                }elseif($tipoTaxa=="asn"){
                    if($tipoTempo=="ad"){
                        $this->showRgr3sp("\"Semanas:1,x;Dias:7,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/7;
                    }elseif($tipoTempo=="am"){
                        $this->showRgr3sp("\"Semanas:4,x;Meses:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = 4*$tempo;
                    }elseif($tipoTempo=="ab"){
                        $this->showRgr3sp("\"Semanas:8,x;Bimestres:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*8;
                    }elseif($tipoTempo=="at"){
                        $this->showRgr3sp("\"Semanas:12,x;Trimestres:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*12;
                    }elseif($tipoTempo=="asm"){
                        $this->showRgr3sp("\"Semanas:24,x;Semestres:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*24;
                    }elseif($tipoTempo=="aa"){
                        $this->showRgr3sp("\"Semanas:48,x;Anos:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*48;
                    }
                }elseif($tipoTaxa=="am"){
                    if($tipoTempo=="ad"){
                        $this->showRgr3sp("\"Meses:1,x;Dias:30,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/30;
                    }elseif($tipoTempo=="asn"){
                        $this->showRgr3sp("\"Meses:1,x;Semanas:4,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/4;
                    }elseif($tipoTempo=="ab"){
                        $this->showRgr3sp("\"Meses:2,x;Bimestres:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*2;
                    }elseif($tipoTempo=="at"){
                        $this->showRgr3sp("\"Meses:3,x;Trimestres:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*3;
                    }elseif($tipoTempo=="asm"){
                        $this->showRgr3sp("\"Meses:6,x;Semestres:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*6;
                    }elseif($tipoTempo=="aa"){
                        $this->showRgr3sp("\"Meses:12,x;Anos:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*12;
                    }
                }elseif($tipoTaxa=="ab"){
                    if($tipoTempo=="ad"){
                        $this->showRgr3sp("\"Bimestres:1,x;Dias:60,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/60;
                    }elseif($tipoTempo=="asn"){
                        $this->showRgr3sp("\"Bimestres:1,x;Semanas:8,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/8;
                    }elseif($tipoTempo=="am"){
                        $this->showRgr3sp("\"Bimestres:1,x;Meses:2,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/2;
                    }elseif($tipoTempo=="at"){
                        $this->showRgr3sp("\"Bimestres:1.5,x;Trimestres:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*1.5;
                    }elseif($tipoTempo=="asm"){
                        $this->showRgr3sp("\"Bimestres:3,x;Semestres:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*3;
                    }elseif($tipoTempo=="aa"){
                        $this->showRgr3sp("\"Bimestres:6,x;Anos:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*6;
                    }
                }elseif($tipoTaxa=="at"){
                    if($tipoTempo=="ad"){
                        $this->showRgr3sp("\"Trimestres:1,x;Dias:90,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/90;
                    }elseif($tipoTempo=="asn"){
                        $this->showRgr3sp("\"Trimestres:1,x;Semanas:12,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/12;
                    }elseif($tipoTempo=="am"){
                        $this->showRgr3sp("\"Trimestres:1,x;Meses:3,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/3;
                    }elseif($tipoTempo=="ab"){
                        $this->showRgr3sp("\"Trimestres:1,x;Bimestres:1.5,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/1.5;
                    }elseif($tipoTempo=="asm"){
                        $this->showRgr3sp("\"Trimestres:2,x;Semestres:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*2;
                    }elseif($tipoTempo=="aa"){
                        $this->showRgr3sp("\"Trimestres:4,x;Anos:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*4;
                    }
                }elseif($tipoTaxa=="asm"){
                    if($tipoTempo=="ad"){
                        $this->showRgr3sp("\"Semestres:1,x;Dias:180,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/180;
                    }elseif($tipoTempo=="asn"){
                        $this->showRgr3sp("\"Semestres:1,x;Semanas:24,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/24;
                    }elseif($tipoTempo=="am"){
                        $this->showRgr3sp("\"Semestres:1,x;Meses:6,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/6;
                    }elseif($tipoTempo=="ab"){
                        $this->showRgr3sp("\"Semestres:1,x;Bimestres:3,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/3;
                    }elseif($tipoTempo=="at"){
                        $this->showRgr3sp("\"Semestres:1,x;Trimestres:2,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/2;
                    }elseif($tipoTempo=="aa"){
                        $this->showRgr3sp("\"Semestres:2,x;Anos:1,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo*2;
                    }
                }elseif($tipoTaxa=="aa"){
                    if($tipoTempo=="ad"){
                        $this->showRgr3sp("\"Anos:1,x;Dias:365,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/365;
                    }elseif($tipoTempo=="asn"){
                        $this->showRgr3sp("\"Anos:1,x;Semanas:48,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/48;
                    }elseif($tipoTempo=="am"){
                        $this->showRgr3sp("\"Anos:1,x;Meses:12,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/12;
                    }elseif($tipoTempo=="ab"){
                        $this->showRgr3sp("\"Anos:1,x;Bimestres:6,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/6;
                    }elseif($tipoTempo=="at"){
                        $this->showRgr3sp("\"Anos:1,x;Trimestres:4,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/4;
                    }elseif($tipoTempo=="asm"){
                        $this->showRgr3sp("\"Anos:1,x;Semestres:2,$tempo|dp[rga3smp]\"");
                        $novoTempo = $tempo/2;
                    }
                }
                $tipoTempo = $tipoTaxa;
                $tempo = $novoTempo;

                $showTipoTempo = "";
            if($tempo>1){
                switch ($tipoTempo) {
                        case 'ad':
                            $showTipoTempo = "Dias";
                        break;
                        
                        case 'asn':
                            $showTipoTempo = "Semanas";
                        break;

                        case 'am':
                            $showTipoTempo = "Meses";
                        break;

                        case 'ab':
                            $showTipoTempo = "Bimestres";
                        break;

                        case 'at':
                            $showTipoTempo = "Trimestres";
                        break;

                        case 'asm':
                            $showTipoTempo = "Semestres";
                        break;

                        case 'aa':
                            $showTipoTempo = "Anos";
                        break;
                    }
                }else{
                    switch ($tipoTempo) {
                        case 'ad':
                            $showTipoTempo = "Dia";
                        break;
                        
                        case 'asn':
                            $showTipoTempo = "Semana";
                        break;

                        case 'am':
                            $showTipoTempo = "Mês";
                        break;

                        case 'ab':
                            $showTipoTempo = "Bimestre";
                        break;

                        case 'at':
                            $showTipoTempo = "Trimestre";
                        break;

                        case 'asm':
                            $showTipoTempo = "Semestre";
                        break;

                        case 'aa':
                            $showTipoTempo = "Ano";
                        break;
                    }
                }
            }
        }
        return $tempo;
    }

    private function showConvertNovoTempo($tempo,$taxa,$tipoTempo,$tipoTaxa){ // Converte o tempo dos juros para o tempo desejado
        // Convertendo tempo
        $novoTempo = "";
        if($tempo!="?"&&$taxa!="?"){
            if($tipoTaxa!=$tipoTempo){
                $this->print .= "<h3>Convesão</h3><br />";
                if($tipoTaxa=="ad"){
                    if($tipoTempo=="asn"){
                        $this->showRgr3sp("\"Dias:7,$tempo;Semanas:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/7;
                    }elseif($tipoTempo=="am"){
                        $this->showRgr3sp("\"Dias:30,$tempo;Meses:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/30;
                    }elseif($tipoTempo=="ab"){
                        $this->showRgr3sp("\"Dias:60,$tempo;Bimestres:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/60;
                    }elseif($tipoTempo=="at"){
                        $this->showRgr3sp("\"Dias:90,$tempo;Trimestres:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/90;
                    }elseif($tipoTempo=="asm"){
                        $this->showRgr3sp("\"Dias:180,$tempo;Semestres:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/180;
                    }elseif($tipoTempo=="aa"){
                        $this->showRgr3sp("\"Dias:365,$tempo;Anos:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/365;
                    }
                }elseif($tipoTaxa=="asn"){
                    if($tipoTempo=="ad"){
                        $this->showRgr3sp("\"Semanas:1,$tempo;Dias:7,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*7;
                    }elseif($tipoTempo=="am"){
                        $this->showRgr3sp("\"Semanas:4,$tempo;Meses:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/4;
                    }elseif($tipoTempo=="ab"){
                        $this->showRgr3sp("\"Semanas:8,$tempo;Bimestres:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/8;
                    }elseif($tipoTempo=="at"){
                        $this->showRgr3sp("\"Semanas:12,$tempo;Trimestres:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/12;
                    }elseif($tipoTempo=="asm"){
                        $this->showRgr3sp("\"Semanas:24,$tempo;Semestres:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/24;
                    }elseif($tipoTempo=="aa"){
                        $this->showRgr3sp("\"Semanas:48,$tempo;Anos:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/48;
                    }
                }elseif($tipoTaxa=="am"){
                    if($tipoTempo=="ad"){
                        $this->showRgr3sp("\"Meses:1,$tempo;Dias:30,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*30;
                    }elseif($tipoTempo=="asn"){
                        $this->showRgr3sp("\"Meses:1,$tempo;Semanas:4,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*4;
                    }elseif($tipoTempo=="ab"){
                        $this->showRgr3sp("\"Meses:2,$tempo;Bimestres:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/2;
                    }elseif($tipoTempo=="at"){
                        $this->showRgr3sp("\"Meses:3,$tempo;Trimestres:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/3;
                    }elseif($tipoTempo=="asm"){
                        $this->showRgr3sp("\"Meses:6,$tempo;Semestres:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/6;
                    }elseif($tipoTempo=="aa"){
                        $this->showRgr3sp("\"Meses:12,$tempo;Anos:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/12;
                    }
                }elseif($tipoTaxa=="ab"){
                    if($tipoTempo=="ad"){
                        $this->showRgr3sp("\"Bimestres:1,$tempo;Dias:60,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*60;
                    }elseif($tipoTempo=="asn"){
                        $this->showRgr3sp("\"Bimestres:1,$tempo;Semanas:8,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*8;
                    }elseif($tipoTempo=="am"){
                        $this->showRgr3sp("\"Bimestres:1,$tempo;Meses:2,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*2;
                    }elseif($tipoTempo=="at"){
                        $this->showRgr3sp("\"Bimestres:1.5,$tempo;Trimestres:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/1.5;
                    }elseif($tipoTempo=="asm"){
                        $this->showRgr3sp("\"Bimestres:3,$tempo;Semestres:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/3;
                    }elseif($tipoTempo=="aa"){
                        $this->showRgr3sp("\"Bimestres:6,$tempo;Anos:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/6;
                    }
                }elseif($tipoTaxa=="at"){
                    if($tipoTempo=="ad"){
                        $this->showRgr3sp("\"Trimestres:1,$tempo;Dias:90,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*90;
                    }elseif($tipoTempo=="asn"){
                        $this->showRgr3sp("\"Trimestres:1,$tempo;Semanas:12,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*12;
                    }elseif($tipoTempo=="am"){
                        $this->showRgr3sp("\"Trimestres:1,$tempo;Meses:3,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*3;
                    }elseif($tipoTempo=="ab"){
                        $this->showRgr3sp("\"Trimestres:1,$tempo;Bimestres:1.5,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*1.5;
                    }elseif($tipoTempo=="asm"){
                        $this->showRgr3sp("\"Trimestres:2,$tempo;Semestres:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/2;
                    }elseif($tipoTempo=="aa"){
                        $this->showRgr3sp("\"Trimestres:4,$tempo;Anos:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/4;
                    }
                }elseif($tipoTaxa=="asm"){
                    if($tipoTempo=="ad"){
                        $this->showRgr3sp("\"Semestres:1,$tempo;Dias:180,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*180;
                    }elseif($tipoTempo=="asn"){
                        $this->showRgr3sp("\"Semestres:1,$tempo;Semanas:24,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*24;
                    }elseif($tipoTempo=="am"){
                        $this->showRgr3sp("\"Semestres:1,$tempo;Meses:6,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*6;
                    }elseif($tipoTempo=="ab"){
                        $this->showRgr3sp("\"Semestres:1,$tempo;Bimestres:3,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*3;
                    }elseif($tipoTempo=="at"){
                        $this->showRgr3sp("\"Semestres:1,$tempo;Trimestres:2,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*2;
                    }elseif($tipoTempo=="aa"){
                        $this->showRgr3sp("\"Semestres:2,$tempo;Anos:1,x|dp[rga3smp]\"");
                        $novoTempo = $tempo/2;
                    }
                }elseif($tipoTaxa=="aa"){
                    if($tipoTempo=="ad"){
                        $this->showRgr3sp("\"Anos:1,$tempo;Dias:365,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*365;
                    }elseif($tipoTempo=="asn"){
                        $this->showRgr3sp("\"Anos:1,$tempo;Semanas:48,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*48;
                    }elseif($tipoTempo=="am"){
                        $this->showRgr3sp("\"Anos:1,$tempo;Meses:12,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*12;
                    }elseif($tipoTempo=="ab"){
                        $this->showRgr3sp("\"Anos:1,$tempo;Bimestres:6,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*6;
                    }elseif($tipoTempo=="at"){
                        $this->showRgr3sp("\"Anos:1,$tempo;Trimestres:4,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*4;
                    }elseif($tipoTempo=="asm"){
                        $this->showRgr3sp("\"Anos:1,$tempo;Semestres:2,x|dp[rga3smp]\"");
                        $novoTempo = $tempo*2;
                    }
                }
                $tipoTempo = $tipoTaxa;
                $tempo = $novoTempo;

                $showTipoTempo = "";
            if($tempo>1){
                switch ($tipoTempo) {
                        case 'ad':
                            $showTipoTempo = "Dias";
                        break;
                        
                        case 'asn':
                            $showTipoTempo = "Semanas";
                        break;

                        case 'am':
                            $showTipoTempo = "Meses";
                        break;

                        case 'ab':
                            $showTipoTempo = "Bimestres";
                        break;

                        case 'at':
                            $showTipoTempo = "Trimestres";
                        break;

                        case 'asm':
                            $showTipoTempo = "Semestres";
                        break;

                        case 'aa':
                            $showTipoTempo = "Anos";
                        break;
                    }
                }else{
                    switch ($tipoTempo) {
                        case 'ad':
                            $showTipoTempo = "Dia";
                        break;
                        
                        case 'asn':
                            $showTipoTempo = "Semana";
                        break;

                        case 'am':
                            $showTipoTempo = "Mês";
                        break;

                        case 'ab':
                            $showTipoTempo = "Bimestre";
                        break;

                        case 'at':
                            $showTipoTempo = "Trimestre";
                        break;

                        case 'asm':
                            $showTipoTempo = "Semestre";
                        break;

                        case 'aa':
                            $showTipoTempo = "Ano";
                        break;
                    }
                }
            }
        }
        return $tempo;
    }

    protected function showRaiz2($radicando){ // Exibe Raiz quadrada
        return $radicando."<sup>".$this->showFracao(1, 2)."</sup>";
    }

    protected function hideRadiciacao($e) { // Oculta o modo de exibição da radiciação
        $sub1 = "<table class='tableRaiz'><tr><td class='radical'>&radic;</td><td class='radicando'>";
        $sub2 = "</td></tr></table>";
        if(substr_count($e, $sub1)>0&&substr_count($e, $sub2)>0){
            $e = str_replace($sub1, "", $e);
            $e = str_replace($sub2, "", $e);
            $e = "√".$e;
        }
        return $e;
    }

    private function getDesigual($ex){ // Retorna o simbulo de desigualdade
        if(substr_count($ex, "&le;")==1){
            return "&le;";
        }elseif(substr_count($ex, "&lt;")==1){
            return "&lt;";
        }elseif(substr_count($ex, "&ge;")==1){
            return "&ge;";
        }elseif(substr_count($ex, "&gt;")==1){
            return "&gt;";
        }else{
            return false;
        }
    }

    private function isDesigual($str){ // Verifica se o simbulo é de desigualdade
        if($str == "&lt;"){
            return true;
        }elseif($str == "&le;"){
            return true;
        }elseif($str == "&gt;"){
            return true;
        }elseif($str == "&ge;"){
            return true;
        }else{
            return false;
        }
    }

    private function showInequacao1($ex){ // Mostra o calculo de uma inequação do 1° grau
        $this->print .= "<div id='eqcres'>";
        $ex = $this->sees($ex);
        $sbDsg = $this->getDesigual($ex); // Simbulo de desigualdade
        $ex = $this->comNum($this->sepElements($ex,$sbDsg));
        $this->print .= "$ex<br />";
        if($this->soLetra($this->toString($this->sepArray($this->sepElements($ex),$sbDsg,1)))==""){
            $ex = $this->toString($this->sepArray($this->sepElements($ex),$sbDsg,-1)).$sbDsg.$this->expressaoCalc($this->toString($this->sepArray($this->sepElements($ex),$sbDsg,1)));
        }
        if($this->soLetra($this->toString($this->sepArray($this->sepElements($ex),$sbDsg,-1)))==""){
            $ex = $this->expressaoCalc($this->toString($this->sepArray($this->sepElements($ex),$sbDsg,-1))).$sbDsg.$this->toString($this->sepArray($this->sepElements($ex),$sbDsg,1));
        }
        $ex = str_replace("*", "", $ex);
        $antes = $ex;
        if($ex!=$antes){
            $this->print .= "$ex<br />";
        }
        $ae = $this->sepElements($ex,$sbDsg);
        for($i=0;$i<count($ae);$i++){
            if(substr_count($ae[$i],"(")>0){
                $ae[$i] = str_replace("<sup>1</sup>", "", $this->distributiva($ae[$i]));
            }
        }
        $ex = "";
        for($i=0;$i<count($ae);$i++){
            $ex .= $ae[$i];
        }
        if($ex!=$antes){
            $this->print .= "$ex<br />";
        }
        $antes = $ex;
        $ex = $this->orgElements($this->sepElements($ex),$sbDsg);
        if($antes!=$ex){
            $this->print .= "$ex<br />";
        }
        $antes = $ex;
        $ex = $this->somaElements($this->sepElements($ex,$sbDsg),$sbDsg);
        $e = $this->sepElements($ex,$sbDsg);
        if($e[0]<0){
            $this->print .= "$ex (-1)<br />";
            $ex = $this->inverteIqc($e,$sbDsg);
            $sbDsg = $this->inverteDesigual($sbDsg);
        }
        if($antes!=$ex){
            $this->print .= "$ex<br />";
        }
        $es = $this->trocaCoeficiente($this->sepElements($ex,$sbDsg),false,$sbDsg);
        $this->print .= "$es<br />";
        $ex = $this->trocaCoeficiente($this->sepElements($ex,$sbDsg),true,$sbDsg);
        $this->print .= "$ex";
        $this->print .= "</div>";
    }

    /*private function grupDesigual($e){ // Agrupa o simbulo de desigualdade em um Array split
        for($i=0;$i<count($e);$i++){
            if(isset($e[$i])){
                if($this->isDesigual($e[$i])){
                    if($e[$i+1]=="="){
                        $e[$i] .= $e[$i+1];
                        unset($e[$i+1]);
                    }else{
                        break;
                    }
                }
            }
        }
        return $this->orgArray($e);
    }*/

    private function inverteIqc($e,$t){ // Multiplica a inequação por (-1)
        $t = $this->inverteDesigual($t);
        if(substr($e[0],0,-1)<0){
            $e1 = (substr($e[0],0,-1)*(-1)).$this->verVar($e[0]);
            $e2 = (($e[2])*(-1));
            $res = $e1.$t.$e2;
            return $res;
        }
    }

    private function inverteDesigual($t){ // Multiplica o sinal de desigualdade por (-1)
        switch ($t) {
            case '&lt;':
                $t = "&gt;";
            break;

            case '&gt;':
                $t = "&lt;";
            break;

            case '&ge;':
                $t = "&le;";
            break;

            case "&le;":
                $t = "&ge;";
            break;
            
            default:
                $t = false;
            break;
        }
        return $t;
    }

    private function tratarEntrada($ex){ // Transfoma a ENT para que a RedeMath entenda
        if(!$this->isJson($ex)) {
            $ex = str_replace("–", "-", $ex);
            $ex = str_replace("÷", "/", $ex);
            $ex = str_replace(":", "/", $ex);
            $ex = str_replace("×", "*", $ex);
            $ex = str_replace("·", "*", $ex);
            $ex = str_replace(",", ".", $ex);
            $ex = str_replace("&div;", "/", $ex);
            //$ex = $this->removeAlinhar($ex);
            $ex = str_replace("<div><br></div>", "", $ex);
            $ex = str_replace("<br>", "", $ex);
            $ex = $this->elevadoa2($this->sepElements($ex));
            $ex = str_replace($this->icognita($ex)."<sup>2</sup>", $this->icognita($ex).$this->icognita($ex), $ex);
            $ex = $this->sees($ex);
            $ex = str_replace("<tableclass=","<table class=",$ex);
            $ex = str_replace("<tdclass=","<td class=",$ex);
            $ex = str_replace("\"", "'", $ex);
        }
        return $ex; 
    }

    public function showCalc() { // Efetua e exibe o calculo
        $ex = $this->ex;
        $this->print .= "<script>var lampada = false;</script>";
        switch($this->getTipo()){
            case "eqc1":
                if($this->eqc1Valida($ex)) {
                    $this->showEquacao1($ex);
                    $this->print .= "<script>lampada = true;</script>";
                }else {
                    $this->print .= "Sem solução";
                }
            break;
            case "eqc2":
                $this->showEquacao2($ex);
                $this->print .= "<script>lampada = true;</script>";
            break;
            case "soma":
                $this->somaCalc($this->sepElements($ex)[0], $this->sepElements($ex)[1]);
                $this->print .= "<script>lampada = true;</script>";
            break;
            case "subtracao":
                $this->subCalc($this->sepElements($ex)[0], $this->sepElements($ex)[1]);
                $this->print .= "<script>lampada = true;</script>";
            break;
            case "exp":
                $this->print .= $ex."=<br /><span id='exNum'>";
                $this->print .= $this->expressaoCalc($ex);
                $this->print .= "</span>";
                $this->print .= "<script>lampada = true;</script>";
            break;
            case 'expNum':
                $this->print .= "$ex<br />";
                $this->expressaoNumerica($ex);
                $this->print .= "<script>lampada = true;</script>";
            break;
            case "mult":
                $this->print .= $this->sepElements($ex)[0]."×".$this->sepElements($ex)[2]."=".$this->sepElements($ex)[0]*$this->sepElements($ex)[2]."<br /><br />";
                $this->multCalc($this->sepElements($ex)[0], $this->sepElements($ex)[2]);
                $this->print .= "<script>lampada = true;</script>";
            break;
            case "div":
            $this->print .= $this->reverseShowFracao($ex)[0]."&div;".$this->reverseShowFracao($ex)[1]."=".$this->expressaoCalc($ex)."<br /><br />";
                $this->divCalc($this->reverseShowFracao($ex)[0],$this->reverseShowFracao($ex)[1]);
                $this->print .= "<script>lampada = true;</script>";
            break;
            case "distbv":
                $this->print .= $ex."<br />";
                $this->print .= $this->tratar($this->showDistributiva($ex))."<br />";
                //$this->print .= $this->tratar($this->somaSemelhantes($this->distributiva2($ex)));
                $this->print .= "<script>lampada = true;</script>";
            break;
            case 'mmcmdc':
                $this->print .= "mmc($ex)=".$this->mmc($ex);
                $this->showMmc($ex);
                $this->print .= "<br /><br />";
                $this->print .= "mdc($ex)=".$this->mdc($ex);
                $this->showMdc($ex);
                $this->print .= "<script>lampada = true;</script>";
            break;
            case 'rad':
                $ex = str_replace("√", "", $ex);
                $this->print .= "<br /><br />";
                $this->radiciacao($ex);
                $this->print .= "<script>lampada = true;</script>";
            break;
            case 'rga3smp':
                $this->showRgr3sp($ex);
                $this->print .= "<script>lampada = true;</script>";
            break;
            case 'juros':
                $this->showJuros($ex);
                $this->print .= "<script>lampada = true;</script>";
            break;
            case 'iqc1':
                $this->showInequacao1($ex);
                $this->print .= "<script>lampada = true;</script>";
            break;
            case 'frcOrc':
                $this->showFrcOrc($ex);
                $this->print .= "<script>lampada = true;</script>";
            break;
            case 'nhm':
                $this->print .= "<table id='altTbl'><tr><td><img src='../_imagens/alerta.png' /></td><td><h2>Não foi possivel realizar o calculo!</h2></td></tr></table>";
            break;
        }
        if(count($this->passos)>0){
            $this->showInfoSteps();
        }
        return $this->print;
    }

    private function hasSolution(){ // Verifica se o calculo tem solução
        $ex = $this->ex;
        $this->hasSolution = false;
        switch($this->getTipo()){
            case "eqc1":
                if($this->eqc1Valida($ex)) {
                    $this->hasSolution = true;
                }
            break;
            case "eqc2":
                $this->hasSolution = true;
            break;
            case "soma":
                $this->hasSolution = true;
            break;
            case "subtracao":
                $this->hasSolution = true;
            break;
            case "exp":
                $this->hasSolution = true;
            break;
            case 'expNum':
                $this->hasSolution = true;
            break;
            case "mult":
                $this->hasSolution = true;
            break;
            case "div":
                $this->hasSolution = true;
            break;
            case "distbv":
                $this->hasSolution = true;
            break;
            case 'mmcmdc':
                $this->hasSolution = true;
            break;
            case 'rad':
                $this->hasSolution = true;
            break;
            case 'rga3smp':
                $this->hasSolution = true;
            break;
            case 'juros':
                $this->hasSolution = true;
            break;
            case 'iqc1':
                $this->hasSolution = true;
            break;
            case 'nhm':
                $this->hasSolution = false;
            break;
        }
    }

    public function getHasSolution(){ // Retorna true caso tenha solução
        return $this->hasSolution;
    }

    public function calc() { // Efetua o calculo
        if($this->getResult() || !$this->getHasSolution()){
            return false;
        }
        $ex = $this->ex;
        switch($this->getTipo()){
            case "eqc1":
                if($this->eqc1Valida($ex)) {
                    $this->setResult($this->calcEquacao1($ex));
                }else {
                    $this->setResult(false);
                }
            break;
            case "eqc2":
                $this->calcEquacao2($ex);
            break;
            case "soma":
                $this->setResult($this->expressaoCalc($ex));
            break;
            case "subtracao":
                $this->setResult($this->expressaoCalc($ex));
            break;
            case "exp":
                $this->setResult($this->expressaoCalc($ex));
            break;
            case 'expNum':
                $this->calcExpressaoNumerica($ex);
            break;
            case "mult":
                $this->setResult($this->expressaoCalc($ex));
            break;
            case "div":
                $this->setResult($this->expressaoCalc($ex));
            break;
            case "distbv":
                $this->setResult($this->distributiva($ex));
            break;
            case 'mmcmdc':
                $this->setResult([$this->mmc($ex),$this->mdc($ex)]);
            break;
            case 'rad':
                $ex = str_replace("√", "", $ex);
                $this->setResult(sqrt($this->expressaoCalc($ex)));
            break;
            case 'rga3smp':
                $this->calcRgr3sp($ex);
            break;
            case 'juros':
                // $this->calcJuros($ex);
            break;
            case 'iqc1':
                $this->calcInequacao1($ex);
            break;
            case 'frcOrc':
                $this->expressaoCalc($ex);
            break;
            case 'nhm':
                $this->setResult(false);
            break;
        }
    }

    private function calcEquacao2($ex){// Calcula uma equação do 2° grau
        $ex = $this->sees($ex);
        $ex = str_replace("*", "", $ex);
        $ex = $this->comNum($this->sepElements($ex));
        $ae = $this->sepElements($ex);
        for($i=0;$i<count($ae);$i++){
            if(substr_count($ae[$i],"(")>0){
                $ae[$i] = str_replace("<sup>1</sup>", "", $this->distributiva($ae[$i]));
            }
        }
        $ex = "";
        for($i=0;$i<count($ae);$i++){
            $ex .= $ae[$i];
        }
        $ex = $this->orgEquacao2($this->sepElements($ex));
        $soma = str_replace("=0","",$ex);
        $ex = $this->somaSemelhantes($soma)."=0";
        if(!$this->eqc2Valida($ex)){
            $this->setResult($this->calcEquacao1($ex));
        }else{

            // Coeficientes
            $c = $this->eqc2Coeficientes($ex);
            $A = str_replace("(", "", $c["a"]);
            $A = str_replace(")", "", $A)*1;
            $B = str_replace("(", "", $c["b"]);
            $B = str_replace(")", "", $B)*1;
            $C = str_replace("(", "", $c["c"]);
            $C = str_replace(")", "", $C)*1;
            // Icognita
            $l = $this->icognita($ex);
            // Calculando o delta
            $delta = $B*$B-4*$A*$C;
            if($delta<0){
                $this->setResult(false);
            }else{
                if($delta>0){
                    $numerador1 = ($B*(-1))+sqrt($delta);
                    $numerador2 = ($B*(-1))-sqrt($delta);
                    $denominador = 2*$A;
                    $raizes = array();
                    // X1
                    $raizes[0] = ($this->formatNum($numerador1/$denominador));
                    // X2
                    $raizes[1] = ($this->formatNum($numerador2/$denominador));
                }else{
                    $this->setResult($this->formatNum(($B*(-1))/(2*$A)));
                }
            }
        }
    }

    private function calcExpressaoNumerica($ex){ // Calcula uma expressão numerica
        while(substr_count($ex, "(")>0&&substr_count($ex, ")")>0){
            $parenteses = substr($ex,stripos($ex, "("),stripos($ex, ")")-stripos($ex, "(")+1);
            for($i=2;substr_count($parenteses,"(")!=substr_count($parenteses,")");$i++){
                $parenteses = substr($ex,stripos($ex, "("),$this->stripos_count($ex, ")",$i)-stripos($ex, "(")+1);
            }
            if(substr_count($parenteses,"[")==0&&substr_count($parenteses,"{")==0){
                $ex = str_replace($parenteses, $this->expressaoCalc($parenteses), $ex);
            }
        }

        while(substr_count($ex, "[")>0&&substr_count($ex, "]")>0){
            $conchetes = substr($ex,stripos($ex, "["),stripos($ex, "]")-stripos($ex, "[")+1);
            if(substr_count($conchetes,"{")==0){
                $calculo = $conchetes;
                $calculo = str_replace("[", "", $calculo);
                $calculo = str_replace("]", "", $calculo);
                $ex = str_replace($conchetes, $this->expressaoCalc($calculo), $ex);
            }
        }

        if(substr_count($ex, "{")>0){
            while(substr_count($ex, "{")>0&&substr_count($ex, "}")>0){
                $chaves = substr($ex,stripos($ex, "{"),stripos($ex, "}")-stripos($ex, "{")+1);
                $calculo = $chaves;
                $calculo = str_replace("{", "", $calculo);
                $calculo = str_replace("}", "", $calculo);
                $ex = str_replace($chaves, $this->expressaoCalc($calculo), $ex);
            }
        }
        $ex = $this->tratar($ex);
        $ex = $this->expressaoCalc($ex);
        $this->setResult($ex);
    }

    private function calcRgr3sp($ex){ // Calcula a Regra de 3 simples
        $ex = str_replace("[rga3smp]", "", $ex);
        $ppc = "";// Diratamente proporcional ou invesamente propocional
        if(substr_count($ex, "|dp\"")==1){
            $ppc = "dp";
            $ex = str_replace("dp\"", "", $ex);
        }elseif(substr_count($ex, "|ip\"")==1){
            $ppc = "ip";
            $ex = str_replace("ip\"", "", $ex);
        }
        $gdz1 = substr($ex,stripos($ex,"\""),stripos($ex,":")+1);// Grandeza 1
        $ex = str_replace($gdz1, "", $ex);
        $gdz1 = str_replace("\"", "", $gdz1);
        $gdz1 = str_replace(":", "", $gdz1);
        $col1 = substr($ex, 0, stripos($ex, ";"));// Primeira coluna (grandeza1)
        $ex = str_replace($col1.";","",$ex);
        $col1 = explode(",", $col1);
        $gdz2 = substr($ex, 0, stripos($ex, ":"));// Grandeza 2
        $ex = str_replace($gdz2.":", "", $ex);
        $col2 = substr($ex, 0, stripos($ex,"|"));// SEgunda coluna (grandeza 2)
        $ex = str_replace($col2, "", $ex);
        $col2 = explode(",",$col2);
        $ex = str_replace("|", "", $ex);


        $ax = "";
        if($ppc=="ip"){
            $ax = $col2[0];
            $col2[0] = $col2[1];
            $col2[1] = $ax;
        }
        $ax = "";
        $parte1 = "";// Primeira parte da equação lado esquerdo
        $parte2 = "";// Segunda parte da equação lado direito
        if($col1[0]=="x"||$col2[1]=="x"){
            if($col1[0]=="x"){
                $parte1 = $col2[1].$col1[0];
            }else{
                $parte1 = $col1[0].$col2[1];
            }
        }else{
            $parte1 = $col1[0]."*".$col2[1];
        }
        if($col1[1]=="x"||$col2[0]=="x"){
            if($col1[1]=="x"){
                $parte2 = $col2[0].$col1[1];
            }else{
                $parte2 = $col1[1].$col2[0];
            }
        }else{
            $parte2 = $col1[1]."*".$col2[0];
        }
        $x = $this->calcEquacao1($parte1."=".$parte2);
        if(substr_count($x ,"&div;")){
            $x = $this->expressaoCalc(str_replace("&div;", "/", $x));
        }
        if(substr_count($x, "<table")>0){
            $x = $this->reverseShowFracao($x);
            $x = $x[0]/$x[1];
        }
        $this->setResult($x);
    }

    protected function reverseShowFracao($ex){
        $ex = str_replace("\"","'",$ex);
        $ex = str_replace("<tbody>","",$ex);
        $ex = str_replace("</tbody>","",$ex);
        $ex = str_replace("<table class='tableCenter'><tr><td>", "", $ex);
        $ex = str_replace("<table class='frc'><tr><td class='btmBorder'>", "", $ex);
        $ex = str_replace("</td></tr></table>", "", $ex);

        return explode("</td></tr><tr><td>", $ex);
    }

    private function calcInequacao1($ex){ // Mostra o calculo de uma inequação do 1° grau
        $ex = $this->sees($ex);
        $sbDsg = $this->getDesigual($ex); // Simbulo de desigualdade
        $ex = $this->comNum($this->sepElements($ex,$sbDsg));
        if($this->soLetra($this->toString($this->sepArray($this->sepElements($ex),$sbDsg,1)))==""){
            $ex = $this->toString($this->sepArray($this->sepElements($ex),$sbDsg,-1)).$sbDsg.$this->expressaoCalc($this->toString($this->sepArray($this->sepElements($ex),$sbDsg,1)));
        }
        if($this->soLetra($this->toString($this->sepArray($this->sepElements($ex),$sbDsg,-1)))==""){
            $ex = $this->expressaoCalc($this->toString($this->sepArray($this->sepElements($ex),$sbDsg,-1))).$sbDsg.$this->toString($this->sepArray($this->sepElements($ex),$sbDsg,1));
        }
        $ex = str_replace("*", "", $ex);
        $ae = $this->sepElements($ex,$sbDsg);
        for($i=0;$i<count($ae);$i++){
            if(substr_count($ae[$i],"(")>0){
                $ae[$i] = str_replace("<sup>1</sup>", "", $this->distributiva($ae[$i]));
            }
        }
        $ex = "";
        for($i=0;$i<count($ae);$i++){
            $ex .= $ae[$i];
        }
        $ex = $this->orgElements($this->sepElements($ex,$sbDsg),$sbDsg);
        $ex = $this->somaElements($this->sepElements($ex,$sbDsg),$sbDsg);
        $e = $this->sepElements($ex,$sbDsg);
        if($e[0]<0){
            $ex = $this->inverteIqc($e,$sbDsg);
            $sbDsg = $this->inverteDesigual($sbDsg);
        }
        $es = $this->trocaCoeficiente($this->sepElements($ex,$sbDsg),false,$sbDsg);
        $ex = $this->trocaCoeficiente($this->sepElements($ex,$sbDsg),true,$sbDsg);
        $this->setResult($ex);
    }

    protected function array_dell($el,$array) { // Deleta um um elemento de um array
        if (is_int($el)) {
            unset($array[$el]);
            $array = $this->orgArray($array);
        } elseif(is_string($el)) {
            if (array_search($el,$array)>=0) {
                unset($array[array_search($el, $array)]);
                $array = $this->orgArray($array);
            } else {
                throw new Exception("Não foi possivel encontrar o elemento a se deletado!");
            }
        } else {
            throw new Exception("Elemento invalido!");
        }
        return $array;
    }
    
    private function alinhar($ex) {
        $e = $this->sepElements($ex);
        $ex = "";
        for($i=0;$i<count($e);$i++) {
            if($e[$i]!=""){
                $e[$i] = "<td>$e[$i]</td>";
                $ex = $ex.$e[$i];
            }
        }
        return "<table><tr>".$ex."</tr></table>";
    }
    
    /*protected function removeAlinhar($ex) {
        if(substr($ex,0,18)=="<table><tbody><tr>"&&substr($ex,-21)=="</tr></tbody></table>") {
            $ex = substr($this->getEx(),18,-21);
        }
        $e = $this->sepElements($ex);
        $ex = "";
        for($i=0;$i<count($e);$i++) {
            if((substr_count($e[$i],"table class='frc'")==0) && (substr_count($e[$i],"table class=\"frc\"")==0)) {
                $e[$i] = str_replace("<td>","",$e[$i]);
                $e[$i] = str_replace("</td>","",$e[$i]);
            }
            $ex = $ex.$e[$i];
        }
        return $ex;
    }*/
    
    private function isFracao($e) { // Verifica se é uma fração
        if(substr_count($e,"<table class=\"frc\"")>0||substr_count($e,"<table class='frc'")>0) {
            return true;
        } else {
            return false;
        }
    }
    
    private function allFracao($ex) { // Transforma todos numeros em fração
        $e = $this->sepElements($ex);
        $ex = "";
        for ($i = 0; $i < count($e); $i++) {
            if ((!$this->isFracao($e[$i]))&&array_search($e[$i],$this->getSplits())===false&&$e[$i]!="+"&&$e[$i]!="-") {
                $e[$i] = $this->showFracao($e[$i], "1");
            }
            $ex .= $e[$i];
        }
        $ex = str_replace("\"","'",$ex);
        for($i=0;$i<count($this->getSeps());$i++) {
            $ex = str_replace("<table class='frc'><tr><td class='btmBorder'>".$this->getSeps()[$i], $this->getSeps()[$i]."<table class='frc'><tr><td class='btmBorder'>", $ex);
            $ex = str_replace($this->getSeps()[$i]."</td></tr></table>", "</td></tr></table>".$this->getSeps()[$i], $ex);
        } 
        return $ex;
    }
    
    private function isDnmIgl($e) { // Verifica em um vetor de frações se os denominadores são iguais 
        for ($i = 0; $i < count($e); $i++) {
            if(count($e[$i])==2) {
                if($i>0&&isset($e[$i][1])&&isset($e[$i-1][1])) {
                    if($e[$i][1]!=$e[$i-1][1]) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
    
    
    /**
     * Esta função soma duas ou mais frações e exibe o calculo
     * 
     * @param String $ex Calculo
     * 
     */
    private function showSomaFracao($ex) {
        $e = $this->sepElements($ex);
        $sn = ""; // Soma numerador
        $ae = $ex;
        for ($i = 0; $i < count($e); $i++) {
            $e[$i] = $this->reverseShowFracao($e[$i]);
        }
        if($this->isDnmIgl($e)) {
            $mmc = "";
            for ($i = 0; $i < count($e); $i++) {
                if(count($e[$i])==2) {
                    $mmc .= $e[$i][1].";";
                }
            }
            $mmc = substr($mmc, 0,-1);
            $mmc = $this->mmc($mmc);
            for ($i = 0; $i < count($e); $i++) {
                if(count($e[$i])==2) {
                    $e[$i][0] = $this->tratar($this->multElements($e[$i][0], $this->fatoracao($mmc,$e[$i][1])));
                    $e[$i][1] = $mmc;
                }
            }
        }
        for ($i=0; $i < count($e); $i++) {
            if(substr_count($e[$i][0],"(")>0){
                $e[$i][0] = $this->distributiva($e[$i][0]);
            }
        }
        for ($i = 0; $i < count($e); $i++) {
            $sn .= $e[$i][0];
        }
        $sn = $this->tratar($sn);
        for ($i = 0; $i < count($e); $i++) {
            if(count($e[$i])==2) {
                $e[$i] = $this->showFracao($e[$i][0], $e[$i][1]);
            } elseif (count($e[$i])==1) {
                $e[$i] = $e[$i][0];
            }
        }
        $e2 = [];
        $e2[] = $this->tratar($this->showFracao($sn, $mmc));
        if($this->soLetra($this->getTxt($e2[count($e2)-1]))==""){
            $e2[] = "=";
            if($this->simplificarFracao($this->showFracao($sn, $mmc))!=$this->showFracao($sn, $mmc)) {
                $e2[] = $this->simplificarFracao($this->showFracao($sn, $mmc));
                $e2[] = "=";
            }
            $e2[] = $sn/$mmc;
        } elseif(count($this->sepElements($this->reverseShowFracao($e2[count($e2)-1])[0]))==1&&count($this->sepElements($this->reverseShowFracao($e2[count($e2)-1])[1]))==1) {
            $e2[] = "=";
            $e2[] = $this->showFatoracao($sn,$mmc);
            $e2[] = "=";
            $e2[] = $this->fatoracao($sn,$mmc);
        }
        $ex = "";
        for ($i = 0; $i < count($e); $i++) {
            $ex .= $e[$i];
        }
        //$ex = $this->alinhar($ex);
        $ex = str_replace("<table>","<table class='tableCenter'>",$ex);
        $this->print .= $ex;
        $ex = "";
        for ($i = 0; $i < count($e2); $i++) {
            $ex .= $e2[$i];
        }
        $ex = $this->alinhar($ex);
        $ex = str_replace("<table>","<table class='tableCenter'>",$ex);
        $this->print .= $ex;
    }
    
    /**
     * Esta função multiplica todas as frações do calculo
     * @param String $ex Calculo
     * @return String Resultado de uma ou mais multiplicações de frações
     */
    private function multFracao($ex) {
        if(substr_count($ex,"*")>0){
            $e = $this->sepElements($ex);
            for ($i = 0; $i < count($e); $i++) {
                if($e[$i]=="*") {
                    if(isset($e[$i-1])&&isset($e[$i+1])) {
                        $e[$i-1] = $this->showFracao($this->multElements($this->reverseShowFracao($e[$i-1])[0],$this->reverseShowFracao($e[$i+1])[0]),$this->multElements($this->reverseShowFracao($e[$i-1])[1],$this->reverseShowFracao($e[$i+1])[1]));
                        unset($e[$i]);
                        unset($e[$i+1]);
                        $e = $this->orgArray($e);
                    }
                }
            }
            $r = "";
            for ($i = 0; $i < count($e); $i++) {
               $r .= $e[$i]; 
            }
            return $r;
        } else {
            return $ex;
        }
    }
    
    /**
     * Esta função divide todas as frações do calculo
     * @param String $ex Calculo
     * @return String Resultado de uma ou mais divisões de frações
     */
    private function divFracao($ex) {
        if(substr_count($ex,"/")>0){
            $e = $this->sepElements($ex);
            for ($i = 0; $i < count($e); $i++) {
                if($e[$i]=="/") {
                    if(isset($e[$i-1])&&isset($e[$i+1])) {
                        $e[$i-1] = $this->showFracao($this->multElements($this->reverseShowFracao($e[$i-1])[0],$this->reverseShowFracao($e[$i+1])[1]),$this->multElements($this->reverseShowFracao($e[$i-1])[1],$this->reverseShowFracao($e[$i+1])[0]));
                        unset($e[$i]);
                        unset($e[$i+1]);
                        $e = $this->orgArray($e);
                    }
                }
            }
            $r = "";
            for ($i = 0; $i < count($e); $i++) {
               $r .= $e[$i]; 
            }
            return $r;
        } else {
            return $ex;
        }
    }
    
    /**
     * Exibe o calculo de operações com frações
     * @param type $ex
     */
    private function showFrcOrc($ex) {
        $ex = $this->allFracao($ex);
        $antes = $ex;
        $this->print .= str_replace("<table>","<table class='tableCenter'>",$this->alinhar($ex));
        $ex = $this->tratarFracoes($ex);
        if($antes!=$ex) {
            $this->print .= str_replace("<table>","<table class='tableCenter'>",$this->alinhar($ex));
        }
        if(array_search("/",$this->sepElements($ex))!==false){
            $ex = $this->divFracao($ex);
            $ex = $this->tratarFracoes($ex);
            $this->print .= str_replace("<table>","<table class='tableCenter'>",$this->alinhar($ex));
        }
        if(array_search("*",$this->sepElements($ex))!==false){
            $ex = $this->multFracao($ex);
            $ex = $this->tratarFracoes($ex);
            $this->print .= str_replace("<table>","<table class='tableCenter'>",$this->alinhar($ex));
        }
        if(array_search("+",$this->sepElements($ex))!==false||array_search("-",$this->sepElements($ex))!==false) {
            $this->showSomaFracao($ex);
        }
        // Continua... Fatoração.
        /*
        if($this->soLetra($this->reverseShowFracao($ex)[0])==""&&$this->soLetra($this->reverseShowFracao($ex)[1])=="") {
            $this->print .= $this->fatoracao($ex);
        }
        */
    }
    
    /**
     *  Efetua operações nos numeradores e denominadores
     *    
    **/
    private function tratarFracoes($ex) {
        $e = $this->sepElements($ex);
        for ($i = 0; $i < count($e); $i++) {
            if($this->isFracao($e[$i])) {
                if(substr_count($this->reverseShowFracao($e[$i])[0], "(")>0) {
                    $e[$i] = $this->showFracao($this->distributiva($this->reverseShowFracao($e[$i])[0]),$this->reverseShowFracao($e[$i])[1]);
                }
                if($this->soLetra($this->reverseShowFracao($e[$i])[0])=="") {
                    $e[$i] = $this->showFracao($this->expressaoCalc($this->reverseShowFracao($e[$i])[0]),$this->reverseShowFracao($e[$i])[1]);
                }
                if(substr_count($this->reverseShowFracao($e[$i])[1],"(")>0) {
                    $e[$i] = $this->showFracao($this->reverseShowFracao($e[$i])[0],$this->distributiva($this->reverseShowFracao($e[$i])[1]));
                }
                if($this->soLetra($this->reverseShowFracao($e[$i])[1])=="") {
                    $e[$i] = $this->showFracao($this->reverseShowFracao($e[$i])[0],$this->expressaoCalc($this->reverseShowFracao($e[$i])[1]));
                }
            }
        }
        $r = $this->toString($e);
        return $r;
    }
    
    protected function getTxt($e) { // Obtem calculo em forma de texto
        for($i=0;$i<count($this->getTags());$i++) {
            $e = str_replace(("<".$this->getTags()[$i].">"), "", $e);
            $e = str_replace(("</".$this->getTags()[$i].">"), "", $e);
        }
        return $e;
    }
    
    private function tratar($ex) {
        $ex = str_replace("<sup>1</sup>", "", $ex);
        $ex = str_replace("--", "+", $ex);
        $ex = str_replace("-+", "-", $ex);
        $ex = str_replace("+-", "-", $ex);
        $ex = str_replace("++", "+", $ex);
        return $ex;
    }
    
    private function fatoracao($n1,$n2) { // Fatoração
        if(!is_numeric($n1)||!is_numeric($n2)) {
            $m1 = $this->subElements($n1);
            $m2 = $this->subElements($n2);
            if(isset($m1["all"])){
                $n1 = $m1["all"];
            } else {
                $n1 = $m1;
            }
            if(isset($m2["all"])){
                $n2 = $m2["all"];
            } else {
                $n2 = $m2;
            }
            for ($i=0; $i < count($n1); $i++) { 
                $n1[$i] = $this->distributiva("(".$n1[$i].")(1)");
            }
            for ($i=0; $i < count($n2); $i++) { 
                $n2[$i] = $this->distributiva("(".$n2[$i].")(1)");
            }
            for ($i = 0; $i < count($n1); $i++) {
                if((array_search($n1[$i],$n2)>=0)&&(array_search($n1[$i],$n2)!==false)&&(isset($n1[$i]))&&(isset($n2[array_search($n1[$i],$n2)]))) {
                    unset($n2[array_search($n1[$i],$n2)]);
                    $n2 = $this->orgArray($n2);
                    unset($n1[$i]);
                    $n1 = $this->orgArray($n1);
                    $i--;
                }
            }
            $n = "";
            for ($i = 0; $i < count($n1); $i++) {
                $n .= "(".$n1[$i].")";
            }
            $n1 = $n;
            if(isset($m2["sinal"])) {
                $n = 1;
                $l = "";
                for ($i = 0; $i < count($n2); $i++) {
                    if(is_numeric($n2[$i])) {
                        $n *= $n2[$i];
                    } else {
                        $l .= $n2[$i];
                    }
                }
                
                $n2 = $m2["sinal"].$n.$l;
                
            }
            if($n2==1||$n2==[]) {
                return $n1;
            } else {
                $this->print .= "ola";
                return $this->showFracao($n1,$n2);
            }
        } else {
            return $n1/$n2;
        }
    }
    
    private function showFatoracao($n1,$n2) { // Mostra o calculo da fatoração
        $m1 = $this->subElements($n1);
        $m2 = $this->subElements($n2);
        $n1 = $m1["all"];
        $n2 = $m2["all"];
        for ($i = 0; $i < count($n1); $i++) {
            if((array_search($n1[$i],$n2)>=0)&&(array_search($n1[$i],$n2)!==false)&&(isset($n1[$i]))&&(isset($n2[array_search($n1[$i],$n2)]))) {
                $n2[array_search($n1[$i],$n2)] = "<del>".$n2[array_search($n1[$i],$n2)]."</del>";
                $n1[$i] = "<del>".$n1[$i]."</del>";
            }
        }
        $n = "";
        for ($i = 0; $i < count($n1); $i++) {
            $n .= $n1[$i]."*";
        }
        $n1 = substr($n,0,-1);
        $n = "";
        for ($i = 0; $i < count($n2); $i++) {
            $n .= $n2[$i]."*";
        }
        $n2 = substr($n,0,-1);
        return $this->showFracao($n1, $n2);
    }
    
    private function subElements($ex) { // Separa um elemento em varios
        $r = [];
        if(substr_count($ex,"(")>0||count($this->sepElements($ex))>1){
            if(count($this->sepElements($ex))>1&&!substr_count($ex,"(")>0) {
                $ex = "($ex)";
            }
            $ex = str_replace("(","*(",$ex);
            $r = $this->sepElements($ex);
            for ($i = 0; $i < count($r); $i++) {
                if($r[$i]=="*"||$r[$i]=="") {
                    unset($r[$i]);
                    $r = $this->orgArray($r);
                    $i--;
                } else {
                    $r[$i] = str_replace("(","",$r[$i]);
                    $r[$i] = str_replace(")","",$r[$i]);
                }
            }
        }elseif($this->isFracao($ex)) {
            $r["numerador"] = $this->subElements($this->reverseShowFracao($ex)[0]);
            $r["denominador"] = $this->subElements($this->reverseShowFracao($ex)[1]);
        } else {
            if(substr_count($ex,"-")>0) {
                $r["sinal"] = "-";
            } else {
                $r["sinal"] = "+";
            }
            $r["numerica"] = $this->parteNumerica($ex);
            $r["numDCP"] = explode("*",$this->decompor($r["numerica"]));
            
            $r["literal"] = ($this->getLiteral($ex,"string")!==false)? str_split($this->getLiteral($ex,"string")):[];
            if($r["numDCP"]!=[""]){
                $r["all"] = $this->somaArray($r["numDCP"], $r["literal"]);
            } else {
                $r["all"] = $r["literal"];
            }
        }
        return $r;
    }
    
    private function getLiteral($e,$type) { // Retorna informações sobre um monomio
        $e = $this->tratar($e);
        $e = str_replace("+", "", $e);
        $e = str_replace("-", "", $e);
        $e = $this->sepElements($e,["<sup"],["</sup>"]);
        $a = [];
        for ($i = 0; $i < count($e); $i++) {
            if(substr_count($e[$i],"sup>")==0) {
                $a = $this->somaArray($a,str_split($e[$i]));
            } else {
                $a[] = $e[$i];
            }
        }
        $e = $a;
        for ($i = 0; $i < count($e); $i++) {
            if(is_numeric($e[$i])) {
                unset($e[$i]);
                $e = $this->orgArray($e);
                $i--;
            }
        }
        if($e!=[]) {
            $e = $this->orgArray($e);
            if(substr_count($e[0],"<sup>")==1) {
                unset($e[0]);
            }
            $e = $this->orgArray($e);
            for ($i = 0; $i < count($e); $i++) {
                if(isset($e[$i])&&(strlen($e[$i])>1)&&(isset($e[$i-1]))&&(strlen($e[$i-1])==1)) {
                    $e[$i-1] .= $e[$i];
                    unset($e[$i]);
                    $i--;
                    $e = $this->orgArray($e);
                }
            }
            for ($i = 0; $i < count($e); $i++) {
                $e[$i] = $this->reverseSup($e[$i]);
            }
            switch ($type) {
                case "string":
                    $a = "";
                    for ($i = 0; $i < count($e); $i++) {
                        $a .= $e[$i];
                    }
                    return $a;
                break;

                case "array":
                    return $e;
                break;

                default:
                    return false;
                break;
            }
        } else {
            return false;
        }
    }
    
    private function somaArray($a1,$a2) { // Concatena 2 arrays
        $a = [];
        for ($i = 0; $i < count($a1); $i++) {
            $a[] = $a1[$i];
        }
        for ($i = 0; $i < count($a2); $i++) {
            $a[] = $a2[$i];
        }
        return $a;
    }
    
    private function decompor($n) { // Decompoe um numero
        $d = 2;
        $r = "";
        while($n>1) {
           if(is_int($n/$d)) {
               $n /= $d;
               $r .= $d."*";
           } else {
               $d++;
           }
        }
        $r = substr($r,0,-1);
        return $r;
    }
    
    private function reverseSup($ex) { // Converte uma potenciação em multiplicação
        $r = "";
        if(substr_count($ex,"<sup>")==1) {
            $e = $this->sepElements($ex,["<sup"],["</sup>"]);
            $exp = $this->sepElements($e[1],["<sup>","</sup>"],["<sup>","</sup>"])[1];
            $base = $e[0];
            if(is_numeric($base)) {
                $r = pow($base,$exp);
            } else {
                $r = $this->repeteTxt($base, $exp);
            }
        } else {
            $r = $ex;
        }
        return $r;
    }



     public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }


}