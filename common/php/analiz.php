<?php
	function sampleEntropy($signal,$N,$r,$m,$pp,$w,$delta){
		
		
	}
	
	function approximateEntropy($signal,$N,$r,$m,$pp,$w,$delta){
		
		
	}
	
	function permutationEntropy($signal,$N,$r,$m,$pp,$w,$delta){
		
		
	}
	
	function zeros($row,$column){
		return array_fill(0,$row,array_fill(0,$column,0));
	}
	
	function repmat($A,$m,$n){
		$B = array();
		$row = sizeof($A);
		$column = sizeof($A[0]);
		//$B = array_fill(0,$m*$row,array_fill(0,$n*$column,0));
		//echo $row.':'.$m.'/'.$column.':'.$n.'<br/>';
		//echoArray($B);
		/*for($r = 0; $r < $row; $r++){
			for($c = 0; $c < $column; $c++){
				for($i = 0; $i < $m; $i++){
					for($j = 0; $j < $n; $j++){
						//echoArray(array_fill(0,$m,array_fill(0,$n,$A)));
						//$B = array_fill($i*$m,$m,array_fill($j*$n,$n,$A));
						$B[]
					}
				}
			}
		}*/
		for($i = 0; $i < $m*$row; $i++){
			for($j = 0; $j < $n*$column; $j++){
				$B[$i][$j] = $A[$i%$row][$j%$column];
			}
		}
		
		return $B;
	}
	
	function absMLE($value1, $value2, $compare){
		$result = array();
		if(sizeof($value1) == sizeof($value2))
			$row = sizeof($value1);
		else
			$row = 0;
		if(sizeof($value1[0]) == sizeof($value2[0]))
			$column = sizeof($value1[0]);
		else 
			$row = 0;
		for($i = 0; $i < $row; $i++){
			for($j = 0; $j < $column; $j++){
				if(abs($value1[$i][$j]-$value2[$i][$j]) <= $compare)
					$result[$i][$j] = 1;
				else
					$result[$i][$j] = 0;
			}
		}
		return $result;
	}
	
	function allNZ($value, $dim){
		if(!isset($value[0]))
			$value = array($value);
	
		if($dim == 1){
			$row = sizeof($value);
			$column = sizeof($value[0]);
		}
		else{
			$row = sizeof($value[0]);
			$column = sizeof($value);
		}
		//$result = array_fill(0,$column,1);
		for($i = 0; $i < $row; $i++){
			for($j = 0; $j < $column; $j++){
				if($dim == 1){
					if($value[$i][$j] == 0)
						$result[$j] = 0;
				}
				else{
					if($value[$j][$i] == 0)
						$result[$j] = 0;
				}
			}
		}
		return $result;
	}
	
	/*function toMatrix($){
		
	}*/
	
	function entropy($signal,$N,$r,$m,$pp,$w,$delta){
		if($pp > 0)
		   $signal = array_slice($signal,$pp);

		$pomocnicze_m = $m;
		$ilosc_okien = $N/$w;
		
		if($N < 5000){
			for($m = $m; $m <= $m+1; $m++){
				$xi = zeros($N-$m,$m);
				for($i = 0; $i < $N-$m; $i++)
					$xi[$i]=array_slice($signal,$i,$m);            //wszystkie wektory do porównania
				for($j = 0; $j < $N-$m; $j++){
					$xj = repmat(array($xi[$j]),$N-$m,1);
					//var_dump($xj);
					$d = absMLE($xi,$xj,$r); 
					array_splice($d, $j, 1);//d(j,:) = [];                        %usuwa j wiersz
					$k = allNZ($d,2);
					echo '----<br/>';
					//echoArray($k);
					var_dump($k);
					echo '----<br/>';
					$suma = array_sum($k);
					$Bm[$j] = $suma/($N-$m);
					$Biem[$m]=array_sum($Bm)/($N-$m);
				}
			}
			$Sample_entropy = log($Biem[$m-1]/$Biem[$m]);
		}
	}
	
	function echoArray($a){
		$row = sizeof($a);
		$column = sizeof($a[0]);
		for($i = 0; $i < $row; $i++){
			for($j = 0; $j < $column; $j++){
				echo $a[$i][$j].' ';
			}
			echo '<br/>';
		}
	}
	
	function echoArray2($a){
		foreach(array_keys($a) as $r){
			foreach(array_keys($a[$r]) as $c){
				echo $r.':'.$c.'<br/>';
				echo $a[$r][$c].' ';
			}
			echo '<br/>';
		}
	}
	
	
	
	//$a = array(array(1,0,3,4,-5),array(1,-2,3,1,-5),array(1,-2,3,4,0));
	$a = array(1,2,3,4,5,7,12,57,21,2);
	
	//$tmp = zeros(3,5);
	//$tmp = repmat($a,2,3);
	//$tmp = absLE($a,2);
	//$tmp = allNZ(array(array(1,0,3,4,-5),array(1,-2,3,4,-5),array(1,-2,3,4,-5)),2);
	$tmp = entropy($a,sizeof($a),2,1,0,5,4);
	
	//var_dump($tmp);
	echoArray($tmp);
?>