<?php 
class squadra
{
	var $idLega;
	var $idUtente;
	var $idGioc;
	
	function setSquadraGiocatoreByArray($idLega,$giocatori,$idUtente)
	{
		$q = "INSERT INTO squadra VALUES ";
		foreach($giocatori as $key => $val)
			$row[] = "('" . $idLega . "','" . $idUtente . "','" . $val . "')";
		$q .= implode(',',$row);
		if(DEBUG)
			echo $q . "<br />";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function unsetSquadraGiocatoreByIdSquadra($idUtente)
	{
		$q = "DELETE 
				FROM squadra 
				WHERE idUtente = '" . $idUtente . "'";
		if(DEBUG)
			echo $q . "<br />";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function updateGiocatore($giocatoreNew,$giocatoreOld,$idUtente)
	{
		$q = "UPDATE squadra 
				SET idGioc = '" . $giocatoreNew . "' 
				WHERE idGioc = '" . $giocatoreOld . "' AND idUtente = '" . $idUtente . "'";
		if(DEBUG)
			echo $q . "<br />";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function updateGiocatoreSquadra($idGioc,$idLega,$idUtente)
	{
		$q = "UPDATE squadra 
				SET idUtente = '" . $idUtente . "' 
				WHERE idGioc = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
		if(DEBUG)
			echo $q . "<br />";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function getSquadraByIdGioc($idGioc,$idLega)
	{
		$q = "SELECT idUtente
				FROM squadra 
				WHERE idGioc = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$values = FALSE;
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_assoc($exe))
			$values = $row['idUtente'];
		return $values;
	}
	
	function setSquadraByIdGioc($idGioc,$idLega,$idUtente)
	{
		$q = "INSERT INTO squadra 
				VALUES ('" . $idLega . "','" . $idUtente . "','" . $idGioc . "')";
		if(DEBUG)
			echo $q . "<br />";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function unsetSquadraByIdGioc($idGioc,$idLega)
	{
		$q = "DELETE
				FROM squadra 
				WHERE idGioc = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
		if(DEBUG)
			echo $q . "<br />";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
}
?>
