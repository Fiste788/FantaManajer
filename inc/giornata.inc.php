<?php
class giornata 
{
	var $idGiornata;
	var $dataInizio;
	var $dataFine;
	
	function getGiornataByDate()
	{
		$minuti = isset($_SESSION['datiLega']) ? $_SESSION['datiLega']['minFormazione'] : 0;
		$q = "SELECT idGiornata 
				FROM giornate 
				WHERE NOW() BETWEEN dataInizio AND dataFine - INTERVAL " . $minuti . " MINUTE";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$valore = mysql_fetch_array($exe);
		if (!empty($valore))
			$valore['partiteInCorso'] = FALSE;
		else
		{
			$q = "SELECT MIN( idGiornata -1 ) as idGiornata
				FROM giornate
				WHERE NOW() < dataFine - INTERVAL " . $minuti . " MINUTE";
			$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
			$valore = mysql_fetch_array($exe);
			$valore['partiteInCorso'] = TRUE;
		}
		$valore['stagioneFinita'] = $valore['idGiornata'] > ($this->getNumberGiornate()-1) ? TRUE : FALSE;
		return $valore;
	}
	
	function checkDay($day)
	{
		$q = "SELECT dataInizio,idGiornata 
				FROM giornate 
				WHERE '" . $day . "' BETWEEN dataInizio AND dataFine";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$value = mysql_fetch_row($exe);
		if(!empty($value))
		{
			$array = explode(" ",$value[0]);
			$data = explode("-",$array[0]);
			$data2dayAfter = date ("Y-m-d", mktime(0,0,0,$data[1],$data[2]+2,$data[0]));
			if($day == $data2dayAfter)
				return $value[1];
			else
				return FALSE;
		}
		else
			return FALSE;
	}
	
	function getDataByGiornata($giorn)
	{
		$q = "SELECT * 
				FROM giornate 
				WHERE idGiornata = '" . $giorn . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$valore = mysql_fetch_row($exe);
		return $valore;
	}
	
	function getNumberGiornate()
	{
		$q = "SELECT COUNT(idGiornata) 
				FROM giornate";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$valore = mysql_fetch_row($exe);
		return $valore[0];
	}
	
	function getAllGiornate()
	{
		$q = "SELECT * 
				FROM giornate";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while($row = mysql_fetch_array($exe,MYSQL_ASSOC))
			$giornate[$row['idGiornata']] = $row;
		return $giornate;
	}
	
	function getTargetCountdown()
	{
		$minuti = isset($_SESSION['datiLega']) ? $_SESSION['datiLega']['minFormazione'] : 0;
		$q = "SELECT MAX(dataFine) - INTERVAL " . $minuti . " MINUTE 
				FROM giornate
				WHERE NOW() > dataInizio";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$valore = mysql_fetch_row($exe);
		return $valore[0];
	}
	
	function updateGiornate($giornate)
	{
		$bool = TRUE;
		foreach($giornate as $key => $val)
		{
			foreach($val as $key2 => $val2)
			{
				$q = "UPDATE giornate SET " . $key2 . " = '" . $val2 . "' WHERE idGiornata = '" . $key . "'";
				$bool *= mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
			}
		}
		return $bool;
	}
}
?>
