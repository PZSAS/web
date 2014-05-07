<?php
	error_reporting(E_ALL & E_STRICT);
	ini_set('display_errors', '1');
	ini_set('log_errors', '0');
	ini_set('error_log', './');

	/***********************************************
		Global variables
	***********************************************/

	$connection;
	$result;
	$database;

	/***********************************************
		Database connection
	***********************************************/
	
	function connect(){
		$xml = simplexml_load_file("../xml/connect.xml");
		global $connection;
		$error = '';
		if($connection)
			disconnect();
		$connection = @mysql_connect($xml->server,$xml->user,base64_decode($xml->password));
		if($connection){     
			if(@mysql_select_db($xml->database,$connection) ) {
				@mysql_query('SET NAMES utf8');
				@mysql_query('SET CHARACTER_SET utf8_unicode_ci');
				return true;
			}
			else{                
				$error = @mysql_errno($connection).': '.@mysql_error($connection);
				$connection = false;
				return false;
			}
		}
		else{            
			$error = @mysql_errno($connection).': '.@mysql_error($connection);
			$connection = false;
			return false;
		}
		return true;
	}
	
	function disconnect(){
		global $connection;
		if (@mysql_close($connection))
			return true;
		else
			return false;
    }
	
	function query($query){
		global $connection, $result;
        $error = '';        
        $result = @mysql_query($query,$connection);

        if(!$result){
           $error = @mysql_errno($connection).': '.@mysql_error($connection);
           return false;
        }
        return true;
    }
	
	/***********************************************
		Functions
	***********************************************/

	function checkUser($name,$pass){
		global $result;
		
		$query = 'SELECT l.user_id
		FROM login l
		WHERE l.login_name = "'.$name.'"
		AND l.login_password = "'.sha1($pass).'";';
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function getApnea($id,$file){
		global $result;
		
		$query = 'SELECT a.*
		FROM apnea a
		WHERE a.user_id = '.$id.'
		AND a.file_name = "'.$file.'";';
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function sendLogin($name){
		global $result;
		
		$query = 'SELECT *
			FROM login
			WHERE login_name = "'.$name.'";';
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['result'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['result'] = 2;
			echo json_encode($message);
		}
	}
	
	function getLogin($id){
		global $result;
		
		$query = 'SELECT l.name
		FROM login l
		WHERE l.person_id = "'.$id.'";';
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function getScheduleInfo($id){
		global $result;
		
		$query = 'SELECT 
				s.schedule_id,
				s.name,
				s.person_id,
				CONCAT(p.name," ",p.surname) person_name,
				s.note,
				s.privilege
			FROM schedule s
			JOIN person p
			ON s.person_id = p.person_id
			WHERE s.schedule_id = '.$id.';';
			
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function getSchedule($id){
		global $result;
		
		if($id == -1){
			$query = 'SELECT 
				a.activity_id,
				a.name,
				CONCAT(p.name," ",p.surname) person_name,
				CONCAT(r.number,"/",b.name) place,
				a.begin
			FROM activity a
			JOIN schedule_activity sa
			ON a.activity_id = sa.activity_id
			JOIN person p
			ON a.person_id = p.person_id
			JOIN room r
			ON a.room_id = r.room_id
			JOIN building b
			ON r.building_id = b.building_id
			JOIN (
			SELECT d.word_id,d.name 
			FROM dictionary d 
			WHERE d.column = "period") d
			ON a.period = d.word_id';
		} else{
			$query = 'SELECT 
				a.activity_id,
				a.name,
				a.person_id,
				CONCAT(p.name," ",p.surname) person_name,
				a.priority,
				a.room_id,
				CONCAT(r.number,"/",b.name) place,
				a.begin,
				a.end,
				a.period,
				a.note
			FROM activity a
			JOIN schedule_activity sa
			ON a.activity_id = sa.activity_id
			JOIN person p
			ON a.person_id = p.person_id
			JOIN room r
			ON a.room_id = r.room_id
			JOIN building b
			ON r.building_id = b.building_id
			WHERE sa.schedule_id = '.$id.';';
			/*
			d.name period,
			
			JOIN (
			SELECT d.word_id,d.name 
			FROM dictionary d 
			WHERE d.column = "period") d
			ON a.period = d.word_id
			*/
		}
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function getScheduleList($id,$type){
		global $result;
		
		if($type == -1){
			$query = 'SELECT 
				s.schedule_id,
				s.name,
				s.person_id,
				CONCAT(p.name," ",p.surname) person_name
			FROM schedule s
			JOIN person p
			ON s.person_id = p.person_id
			WHERE s.person_id = '.$id.'
			OR s.privilege = 0
			OR (s.privilege = 1
			AND s.person_id IN(
			SELECT your_id
			FROM agroup
			WHERE friend_id = '.$id.'));';
		} else if($type == 0){
			$query = 'SELECT 
				s.schedule_id,
				s.name,
				s.person_id,
				CONCAT(p.name," ",p.surname) person_name
			FROM schedule s
			JOIN person p
			ON s.person_id = p.person_id
			WHERE s.person_id <> '.$id.'
			AND s.privilege = 0;';
		} else if($type == 1){
			$query = 'SELECT 
				s.schedule_id,
				s.name,
				s.person_id,
				CONCAT(p.name," ",p.surname) person_name
			FROM schedule s
			JOIN person p
			ON s.person_id = p.person_id
			WHERE (s.privilege = 1
			AND s.person_id IN(
			SELECT your_id
			FROM agroup
			WHERE friend_id = '.$id.'));';
		} else if($type == 2){
			$query = 'SELECT 
				s.schedule_id,
				s.name,
				s.person_id,
				CONCAT(p.name," ",p.surname) person_name
			FROM schedule s
			JOIN person p
			ON s.person_id = p.person_id
			WHERE s.person_id = '.$id.';';
		}
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function getActivity($id){
		global $result;
		
		$query = 'SELECT 
				a.activity_id,
				a.name,
				a.description,
                a.author_id,
                CONCAT(pa.name," ",pa.surname) author_name,
				a.person_id,
				CONCAT(p.name," ",p.surname) person_name,
				a.priority,
				a.room_id,
				CONCAT(r.number,"/",b.name) place,
				r.building_id,
				a.begin,
				a.end,
				a.count,
				a.period_n,
				a.period,
				a.note,
				a.privilege
			FROM activity a
			JOIN person p
			ON a.person_id = p.person_id
            JOIN person pa
			ON a.author_id = pa.person_id
			JOIN room r
			ON a.room_id = r.room_id
			JOIN building b
			ON r.building_id = b.building_id
			JOIN (
				SELECT d.word_id,d.name 
				FROM dictionary d 
				WHERE d.column = "privilege") d2
			ON a.privilege = d2.word_id
			WHERE a.activity_id = '.$id.';';
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function getActivityList($id){
		global $result;
		
		$query = 'SELECT 
				a.activity_id,
				a.name,
				CONCAT(p.name," ",p.surname) person_name,
				CONCAT(r.number,"/",b.name) place,
				a.begin
			FROM activity a
			JOIN person p
			ON a.person_id = p.person_id
            JOIN person pa
			ON a.author_id = pa.person_id
			JOIN room r
			ON a.room_id = r.room_id
			JOIN building b
			ON r.building_id = b.building_id
			WHERE a.author_id = '.$id.' 
			OR a.privilege = 0 
			OR (a.privilege = 1 
				AND a.author_id IN (
					SELECT your_id 
					FROM agroup 
					WHERE friend_id = '.$id.'));';
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function listActivity($id){
		global $result;
		
		$query = 'SELECT 
				a.activity_id,
                CONCAT(a.name," : ",r.number,"/",b.name," : ",a.begin,"-",a.end," : ",p.name," ",p.surname) activity_name
			FROM activity a
			JOIN person p
			ON a.person_id = p.person_id
            JOIN person pa
			ON a.author_id = pa.person_id
			JOIN room r
			ON a.room_id = r.room_id
			JOIN building b
			ON r.building_id = b.building_id
			WHERE a.author_id = '.$id.' 
			OR a.privilege = 0 
			OR (a.privilege = 1 
				AND a.author_id IN (
					SELECT your_id 
					FROM agroup 
					WHERE friend_id = '.$id.'));';
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function getPerson($id){
		global $result;
		
		$query = 'SELECT 
				p.person_id,
				p.author_id,
				CONCAT(pa.name," ",pa.surname) author_name,
				p.name,
				p.second_name,
				p.surname,
				p.titles,
				p.person_type,
				p.birth_date,
				p.phone,
				p.email,
				p.note,
				p.privilege
			FROM person p
			JOIN person pa
			ON p.author_id = pa.person_id
			WHERE p.person_id = '.$id.';';
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function getPersonList($id){
		global $result;
		
		$query = 'SELECT 
				p.person_id,
				p.author_id,
				CONCAT(p.name," ",p.second_name) name,
				p.surname
			FROM person p
			JOIN person pa
			ON p.author_id = pa.person_id
			WHERE p.author_id = '.$id.' 
			OR p.privilege = 0 
			OR (p.privilege = 1 
				AND p.author_id IN (
					SELECT your_id 
					FROM agroup 
					WHERE friend_id = '.$id.'));';
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function listPerson($id){
		global $result;
		
		$query = 'SELECT 
				p.person_id,
				CONCAT(p.titles," ",p.name," ",p.surname) person_name
			FROM person p
			JOIN person pa
			ON p.author_id = pa.person_id
			JOIN (
				SELECT d.word_id,d.name 
				FROM dictionary d 
				WHERE d.column = "privilege") d
			ON p.privilege = d.word_id
			WHERE p.author_id = '.$id.' 
			OR p.privilege = 0 
			OR (p.privilege = 1 
				AND p.author_id IN (
					SELECT your_id 
					FROM agroup 
					WHERE friend_id = '.$id.'));';
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function getPlace($id){
		global $result;
		
		$query = 'SELECT 
				r.room_id,
				r.name room_name,
				r.building_id,
				b.name building_name,
				b.town,
				b.street,
				b.number,
				r.number room_number,
				r.privilege,
				r.author_id,
				CONCAT(p.name," ",p.surname) author_name
			FROM room r
			JOIN building b
			ON r.building_id = b.building_id
			JOIN person p
			ON r.author_id = p.person_id
			WHERE r.room_id = '.$id.';';
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function getPlaceList($id){
		global $result;
		
		$query = 'SELECT 
				r.room_id,
				r.name room_name,
				b.name building_name
			FROM room r
			JOIN building b
			ON r.building_id = b.building_id
			JOIN person p
			ON r.author_id = p.person_id
			WHERE r.author_id = '.$id.' 
			OR r.privilege = 0 
			OR (r.privilege = 1 
				AND r.author_id IN (
					SELECT your_id 
					FROM agroup 
					WHERE friend_id = '.$id.'));';
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function listPlace($id){
		global $result;
		
		$query = 'SELECT 
				r.room_id place_id,
				CONCAT(b.name,"/",r.number) place_name
			FROM room r
			JOIN building b
			ON r.building_id = b.building_id
			JOIN (
			SELECT d.word_id,d.name 
			FROM dictionary d 
			WHERE d.column = "privilege") d
			ON r.privilege = d.word_id
			JOIN person p
			ON r.author_id = p.person_id
			WHERE r.author_id = '.$id.' 
			OR r.privilege = 0 
			OR (r.privilege = 1 
				AND r.author_id IN (
					SELECT your_id 
					FROM agroup 
					WHERE friend_id = '.$id.'));';
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function getBuilding($id){
		global $result;
		
		if($id == -1){
			$query = 'SELECT 
				b.building_id,
				b.name,
				b.town,
				b.street,
				b.number,
				b.author_id,
				CONCAT(p.name," ",p.surname) author_name
			FROM building b
			JOIN person p
			ON b.author_id = p.person_id;';
		} else{
			$query = 'SELECT 
				b.building_id,
				b.name,
				b.town,
				b.street,
				b.number,
				b.author_id,
				CONCAT(p.name," ",p.surname) author_name
			FROM building b
			JOIN person p
			ON b.author_id = p.person_id
			WHERE b.building_id = '.$id.';';
		}
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function getBuildingRooms($id,$type){
		global $result;
		
		$query = 'SELECT 
			r.room_id,
			r.building_id,
			r.number room_number,
			d.name privilege,
			r.author_id,
			CONCAT(p.name," ",p.surname) author_name
		FROM room r
		JOIN (
		SELECT d.word_id,d.name 
		FROM dictionary d 
		WHERE d.column = "privilege") d
		ON r.privilege = d.word_id
		JOIN person p
		ON r.author_id = p.person_id
        WHERE (r.author_id = '.$id.'
        OR r.privilege = 0
        OR (r.privilege = 1
		AND r.author_id IN(
		SELECT your_id
		FROM agroup
		WHERE friend_id = '.$id.')))
        AND r.building_id = '.$type.';';
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$schedule[] = $arrRow;

			header('Content-Type: application/json');
			if($schedule)
				echo json_encode($schedule);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function getPolygon($id){
		global $result;
		
		if($id==-1)
			$query = 'SELECT bp.building_id, p.latitude, p.longitude
				FROM building_point bp
				JOIN point p
				ON bp.point_id = p.point_id';
				
		else
			$query = 'SELECT p.latitude, p.longitude
				FROM building_point bp
				JOIN point p
				ON bp.point_id = p.point_id
				WHERE building_id = '.$id.';';
		
		query($query);
		
       	if($result){
			while($arrRow = mysql_fetch_assoc($result)) 
				$polygon[] = $arrRow;

			header('Content-Type: application/json');
			if($polygon)
				echo json_encode($polygon);
			else{
				$message['error'] = 1;
				echo json_encode($message);
			}
       	}
       	else{
          	$message['error'] = 2;
			echo json_encode($message);
		}
	}
	
	function setPolygon($id,$points){
		$query = 'INSERT INTO building_point';
		foreach($points as $point)
			$query .= "(".$id.",'".$point."'),";
		$query = substr($query,0,-1).";";
		mysql_real_escape_string($query);
		query($query);
	}
	
	/***********************************************
		Event handler
	***********************************************/
	
	
	
	function handler(){
		if($_POST){
			$fun = $_POST["function"];
			if($_POST["p1"])
				$p1 = $_POST["p1"];
			else
				$p1 = -1;
			if($_POST["p2"])
				$p2 = $_POST["p2"];
			else
				$p2 = -1;
			if($_POST["p3"])
				$p3 = $_POST["p3"];
			else
				$p3 = -1;
			if($_POST["p4"])
				$p4 = $_POST["p4"];
			else
				$p4 = -1;
				$type = -1;
		}
		else if($_GET){
			$fun = $_GET["function"];
			if($_GET["p1"])
				$p1 = $_GET["p1"];
			else
				$p1 = -1;
			if($_GET["p2"])
				$p2 = $_GET["p2"];
			else
				$p2 = -1;
			if($_GET["p3"])
				$p3 = $_GET["p3"];
			else
				$p3 = -1;
			if($_GET["p4"])
				$p4 = $_GET["p4"];
			else
				$p4 = -1;
		}
		
		connect();
		
		if($fun=="checkUser")
			checkUser($p1,$p2);
		else if($fun=="sendLogin")
			sendLogin($p1);
		else if($fun=="getApnea")
			getApnea($p1,$p2);
		

		disconnect();
	}
	
	handler();
	
?>