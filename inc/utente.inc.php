<?php 
class utente
{
	var $idSquadra;
	var $nome;
	var $cognome;
	var $nomeProp;
	var $mail;
	var $abilitaMail;
	var $username;
	var $amministratore;
	var $idLega;
	
	function getElencoSquadre()
	{		
		$q = "SELECT * 
				FROM utente 
				WHERE idLega = '" . $_SESSION['idLega'] . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe) )
			$values[$row['idUtente']] = $row;
		return $values; 
	}
	
	function getAllSquadre()
	{		
		$q = "SELECT * 
				FROM utente";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe) )
			$values[$row['idUtente']] = $row;
		return $values; 
	}
	
	function getElencoSquadreByLega($idLega)
	{		
		$q = "SELECT * 
				FROM utente
				WHERE idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe) )
			$values[$row['idUtente']] = $row;
		if(isset($values))
			return $values;
		else
			return FALSE; 
	}
	
	function getSquadraById($idUtente)
	{		
		$q = "SELECT * 
				FROM utente 
				WHERE idUtente = '" . $idUtente . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		return mysql_fetch_array($exe); 
	}
	
	function changeData($data,$id)
	{
		$q = "UPDATE utente SET ";
		if(!isset($data['amministratore']))
			$data['amministratore'] = '0';
		elseif($data['amministratore'] == 'on')
			$data['amministratore'] = '1';
		if(!isset($data['abilitaMail']))
			$data['abilitaMail'] = '0';
		else
			$data['abilitaMail'] = '1';
		if(!isset($data['abilitaMess']))
			$data['abilitaMess'] = '0';
		elseif($data['abilitaMess'] == 'on')
			$data['abilitaMess'] = '1';
		foreach($data as $key => $val)
		{
			if(!empty($val))
			{
				if($key == 'passwordnew')
				{
					$key = 'password';
					$q .= $key . " = '" . md5(trim($val)) . "',";
				} 
				else
				{
					if($key == 'usernamenew')
						$key = 'username';
					$q .= $key . " = '" . trim($val) . "',";
				}
			} 
		}
		$q = substr($q,0,-1);
		$q .= " WHERE idUtente = '" . $id . "'";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function getAllEmail()
	{
		$q = "SELECT mail,idUtente,idLega 
				FROM utente";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe) )
			$values[$row['idLega']][$row['idUtente']] = $row['mail'];
		return $values; 
	}
	
	function getAllEmailAbilitate()
	{
		$q = "SELECT mail,idUtente,idLega 
				FROM utente
				WHERE abilitaMail <> 0";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe) )
			$values[$row['idLega']][$row['idUtente']] = $row['mail'];
		return $values; 
	}
	
	function getAllEmailByLega($idLega)
	{
		$q = "SELECT mail,idUtente 
				FROM utente
				WHERE idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe) )
			$values[$row['idUtente']] = $row['mail'];
		return $values; 
	}
	
	function getAllEmailAbilitateByLega($idLega)
	{
		$q = "SELECT mail,idUtente
				FROM utente
				WHERE abilitaMail <> 0 AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe) )
			$values[$row['idUtente']] = $row['mail'];
		return $values; 
	}
	
	function addSquadra($username,$name,$admin,$password,$email,$idLega)
	{
		require_once(INCDIR.'punteggi.inc.php');
		$punteggiObj = new punteggi();
		$q = "INSERT INTO utente (nome,username,password,mail,amministratore,idLega) 
				VALUES ('" . $name . "','" . $username . "','" . md5($password) . "','" . $email . "','" . $admin . "','" . $idLega . "')";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$q = "SELECT idUtente 
				FROM utente 
				WHERE nome = '" . $name . "' AND username = '" . $username . "' AND mail = '" . $email . "' AND amministratore = '" . $admin . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe) )
			$val = $row['idUtente'];
		$punteggiObj->setPunteggiToZero($val,$idLega);
		return $val;
	}
	
	function deleteSquadra($idUtente)
	{
		$q = "DELETE 
				FROM utente 
				WHERE idUtente = '" . $idUtente . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(mysql_affected_rows() == 0)
			return FALSE;
		else
			return TRUE;
	}
	
	function getLegaByIdSquadra($idUtente)
	{
		$q = "SELECT idLega 
				FROM utente 
				WHERE idUtente = '" . $idUtente . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$val = -1;
		while ($row = mysql_fetch_array($exe) )
			$val = $row['idLega'];
		return $val;
	}
	
	function getSquadraByUsername($username,$idUtente)
	{
		$q = "SELECT * 
				FROM utente 
				WHERE username LIKE '" . $username . "' AND idUtente <> '" . $idUtente . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$val = FALSE;
		while ($row = mysql_fetch_array($exe) )
			$val = $row;
		return $val;
	}
	
	function getSquadraByNome($nome,$idUtente)
	{
		$q = "SELECT * 
				FROM utente 
				WHERE nome LIKE '" . $nome . "' AND idUtente <> '" . $idUtente . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$val = FALSE;
		while ($row = mysql_fetch_array($exe) )
			$val = $row;
		return $val;
	}
}
?>
