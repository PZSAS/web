<?php
	error_reporting(E_ALL & E_STRICT);
	ini_set('display_errors', '1');
	ini_set('log_errors', '0');
	ini_set('error_log', './');
	
	function create($name){
	
		$filename = "..\\data\\".$name.".dat";
		$handle = fopen($filename, "cb");
		
		$n = rand() % 10 + 1;
		//echo $n;
		
		fwrite($handle, pack('N', 0x4650444D)); 			//FPDM
		fwrite($handle, pack('V', 0x53380740)); 			//timestamp
		fwrite($handle, pack('V', 0x00000000)); 			//rozmiar pliku
		fwrite($handle, pack('v', dechex($n)));				//liczba sygna��w
		fwrite($handle, pack('V', 0x00000005));				//liczba sekund
		fwrite($handle, pack('V', 0x00000000));				//zarezerwowane
		fwrite($handle, pack('V', 0x00000000));				//zarezerwowane
		
		for($j = 0; $j < $n; $j++){
			$xn2 = 0;
			$xn1 = 0x085B;
			$A = 0x7EEB;
			$aa = (rand() % 10 + 1) / 10;
			//sygna�
			fwrite($handle, pack('N', 0x44415441));				//DATA
			fwrite($handle, pack('v', 0x0001));					//id sygna�u
			fwrite($handle, pack('v', 0x0064));					//cz�stotliwo�� (Hz)
			fwrite($handle, pack('v', 0x0002));					//rozmiar pr�bki (bajt)
			fwrite($handle, pack('V', 0x000001F4));				//liczba pr�bek
			fwrite($handle, pack('V', 0x00000000));				//zarezerwowane
			
			for($i = 0; $i < 500; $i++){	//pr�bki
				$xn = ((2 * $xn1 * $A * $aa) >> 15) - $xn2 + 32000;
				$xn2 = $xn1;
				$xn1 = $xn;
				fwrite($handle, pack('v', $xn));//ubstr(md5(rand()), 0, 4)));
			}
			
			fwrite($handle, pack('N', 0x53544F50));				//nag��wek (STOP)
		}
		fclose($handle);
	}
	
	function create2($name){
		$filename = "..\\data\\".$name.".dat";
		$handle = fopen($filename, "cb");
		
		fwrite($handle, pack('N', 0x4650444D)); 			//FPDM
		fwrite($handle, pack('V', 0x53380740)); 			//timestamp
		fwrite($handle, pack('V', 0x00000000)); 			//rozmiar pliku
		fwrite($handle, pack('v', 0x0001));					//liczba sygna��w
		fwrite($handle, pack('V', 0x00000005));				//liczba sekund
		fwrite($handle, pack('V', 0x00000000));				//zarezerwowane
		fwrite($handle, pack('V', 0x00000000));				//zarezerwowane
		
		//sygna�
		fwrite($handle, pack('N', 0x44415441));				//DATA
		fwrite($handle, pack('v', 0x0001));					//typ danych
		fwrite($handle, pack('v', 0x0004));					//rozmiar pr�bki (bajt)
		fwrite($handle, pack('V', 0x000001F4));				//liczba pr�bek
		fwrite($handle, pack('V', 0x00000000));				//zarezerwowane
		
		for($i = 0; $i < 500; $i++){	//pr�bki
			fwrite($handle, pack('V', 0x00000000));			//timestamp
			fwrite($handle, pack('v', 0x0000));				//czas trwania
			fwrite($handle, pack('v', 0x0000));			//zarezerwowane
		}
		
		fwrite($handle, pack('N', 0x53544F50));				//nag��wek (STOP)
		
		fclose($handle);
	}
	
	if($_POST){
		if($_POST['name'])
			$name = $_POST['name'];
		else
			$name = 'new_'.date("d-m-Y");
	}else if($_GET){
		if($_GET['name'])
			$name = $_GET['name'];
		else
			$name = 'new_'.date("d-m-Y");
	} else
		$name = 'new_'.date("d-m-Y");
	
	create($name);
?>
