<?php
require_once(INCDIR.'utente.inc.php');
require_once(INCDIR.'punteggi.inc.php');
require_once(INCDIR.'punteggi.inc.php');

$punteggiObj = new punteggi();
$utenteObj = new utente();
$punteggiObj = new punteggi();

$squadra = NULL;
if(isset($_GET['squadra']))
	$squadra = $_GET['squadra'];
$contenttpl->assign('squadra',$squadra);

$contenttpl->assign('posizioni',$punteggiObj->getPosClassifica($_SESSION['legaView']));
$elencoSquadre = $utenteObj->getElencoSquadreByLega($_SESSION['legaView']);
$contenttpl->assign('elencosquadre',$elencoSquadre);
$contenttpl->assign('ultimaGiornata',$punteggiObj->getGiornateWithPunt());

?>
