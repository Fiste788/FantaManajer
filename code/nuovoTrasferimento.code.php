﻿<?phprequire_once(INCDIR.'trasferimenti.inc.php');require_once(INCDIR.'utente.inc.php');require_once(INCDIR.'giocatore.inc.php');require_once(INCDIR.'eventi.inc.php');	$eventiObj = new eventi();$utenteObj = new utente();$giocatoreObj = new giocatore();$trasferimentiObj = new trasferimenti();$squadra = $_SESSION['idSquadra'];$acquisto = NULL;$lasciato = NULL;if(isset($_GET['squad']))	$squadra = $_GET['squad'];if(isset($_POST['squad']))	$squadra = $_POST['squad'];$ruo = array('Portiere','Difensori','Centrocampisti','Attaccanti');$contenttpl->assign('ruo',$ruo);$contenttpl->assign('elencosquadre',$utenteObj->getElencoSquadre());$contenttpl->assign('squadra',$squadra);$trasferimenti = $trasferimentiObj->getTrasferimentiByIdSquadra($squadra);$contenttpl->assign('trasferimenti',$trasferimenti);$numTrasferimenti = count($trasferimenti);$contenttpl->assign('numTrasferimenti',$numTrasferimenti);if($numTrasferimenti <MAXTRASFERIMENTI ){	if(isset($_POST['submit']) && $_POST['submit'] == 'OK')	{		if(isset($_POST['acquista']) && !empty($_POST['acquista']) && isset($_POST['lascia']) && !empty($_POST['lascia']) )		{			$giocatoreAcquistato = $giocatoreObj->getGiocatoreById($_POST['acquista']);			$giocatoreLasciato = $giocatoreObj->getGiocatoreById($_POST['lascia']);			if($giocatoreAcquistato[$_POST['acquista']]['ruolo'] == $giocatoreLasciato[$_POST['lascia']]['ruolo'])			{				$trasferimentiObj->transfer($_POST['lascia'],$_POST['acquista'],$squadra,$_SESSION['idLega']);				$messaggio[0] = 0;				$messaggio[1] = 'Trasferimento effettuato correttamente';			}			else			{				$messaggio[0] = 1;				$messaggio[1] = 'I giocatori devono avere lo stesso ruolo';			}		}		else		{			$messaggio[] = 1;			$messaggio[] = 'Non hai compilato correttamente';		}	}}else{	$messaggio[] = 2;	$messaggio[] = 'Hai raggiunto il limite di trasferimenti';}if(isset($messaggio))	$contenttpl->assign('messaggio',$messaggio);	$contenttpl->assign('giocSquadra',$giocatoreObj->getGiocatoriByIdSquadra($squadra));$playerFree = array();foreach($ruo as $key=>$val)	$playerFree = array_merge($playerFree,$giocatoreObj->getFreePlayer(substr($val,0,1)));$contenttpl->assign('freePlayer',$playerFree);?>