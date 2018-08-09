<?php
    require_once '../../Calculadora.class.php';
	function geraSoma($nivel){
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

	function geraSubtracao($nivel){
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

	function geraMultiplicacao($nivel){
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

	function geraDivisao($nivel){
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

	function geraEquacao1($nivel){
		$alfabeto = range("a", "z");
		$l = $alfabeto[rand(0,count($alfabeto)-1)];
		$max = 0;
		$qtMax = 0;
		$qtMin = 0;
		switch ($nivel) {
			case 'facil':
				$max = 10;
				$qtMax = 2;
				$qtMin = 1;
				break;
			case 'medio':
				$max = 50;
				$qtMax = 3;
				$qtMin = 2;
				break;
			case 'dificil':
				$max = 100;
				$qtMax = 4;
				$qtMin = 3;
				break;
		}
		$parte1 = "";
		$qtNum1 = rand($qtMin,$qtMax);
		for($i=0;$i<$qtNum1;$i++){
			$c = 0;
			if($i==$qtNum1-1){
				if(substr_count($parte1, $l)==0){
					$c = geraMonomio($l,$max);
					$parte1 .= $c;
					$i++;
				}
			}
			if(rand(0,1)==0){
				$c = rand(1,$max);
				if(rand(0,1)==0){
					$c *= -1;
				}else{
					$c = "+".$c;
				}
			}else{
				$c = geraMonomio($l,$max);
			}
			$parte1 .= $c;
		}
		$parte2 = "";
		$qtNum2 = rand($qtMin,$qtMax);
		$qtNP2 = 0;
		for($i=0;$i<$qtNum2;$i++){
			$c = 0;
			if($i==$qtNum2-1){
				if($qtNP2==0){
					$c = rand(1,$max);
					if(rand(0,1)==0){
						$c *= -1;
					}else{
						$c = "+".$c;
					}
					$parte2 .= $c;
					$i++;
				}
			}
			if(rand(0,1)==0){
				$c = rand(1,$max);
				$qtNP2++;
				if(rand(0,1)==0){
					$c *= -1;
				}else{
					$c = "+".$c;
				}
			}else{
				$c = geraMonomio($l,$max);
			}
			$parte2 .= $c;
			$res = $parte1."=".$parte2;
		}
		return $res;
	}

	function geraMonomio($l,$max){
		$tipo = rand(0,1);
		$c = rand(1,$max);
		if($tipo==0){
			$c *= -1;
		}else{
			$c = "+".$c;
		}
		return $c.$l;
	}

	function equacao1($nivel){
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
		$e = sepElements($e, "=");
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
		$e = orgArray($e);
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
		$res = misturaElementos(sepElements($pt1."=".$pt2,"="));
		return $res;
	}

	function misturaElementos($a){
		$p1 = sepArray($a,"=",-1);
		$p2 = sepArray($a,"=",1);
		for($i=0;$i<count($p1);$i++){
			if(rand(0,1)==0&&count($p1)>1){
				$p2[] = (str_replace(verVar($p1[$i]),"",$p1[$i])*(-1)).verVar($p1[$i]);
				unset($p1[$i]);
				$p1 = orgArray($p1);
			}
		}
		if(count($p1)==0){
			$p1[] = (str_replace(verVar($p2[0]),"",$p2[0])*(-1)).verVar($p2[0]);
			unset($p2[0]);
			$p2 = orgArray($p2);
		}
		for($i=0;$i<count($p2);$i++){
			if(rand(0,1)==0&&count($p2)>1){
				$p1[] = (str_replace(verVar($p2[$i]),"",$p2[$i])*(-1)).verVar($p2[$i]);
				unset($p2[$i]);
				$p2 = orgArray($p2);
			}
		}
		if(count($p2)==0){
			$p2[] = (str_replace(verVar($p1[0]),"",$p1[0])*(-1)).verVar($p1[0]);
			unset($p2[0]);
			$p1 = orgArray($p1);
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

	function inverteNumArray($a){
		for($i=0;$i<count($a);$i++){
			$a[$i] *= -1;
		}
		return $a;
	}

	function geraMmc($nivel){
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
?>