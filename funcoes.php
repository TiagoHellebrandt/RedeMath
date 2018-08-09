<?php
    function conStr($x){
        $A = 0;
        for($i=0;$i<=(count($x)-1);$i++){
            $A .= $x[$i];
        }
        return $A;
    }
    
    function numMult($m,$n){
        $z = 0;
        for($i=0;$i*$m<=$n;$i++){
            $z = $i;
        }
        if($z>$n){
            $z--;
        }
        return $z;
    }
    
    function geraExe(){   
        $v = "d";
        $n = 1000;
        switch($v){
            case "f":
                $n = 1000;
            break;
            case "m":
                $n = 10000;
            break;
            case "d":
                $n = 100000;
            break;
            default:
                $n = 1000;
        }
        for($i=1;$i<=10;$i++){
            echo "<li>".mt_rand(0, $n)." + ".mt_rand(0, $n)." = </li>";
        }
                        
    }
?>