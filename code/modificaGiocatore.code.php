<?php 
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'utente.inc.php');

$giocatoreObj = new giocatore();
$utenteObj = new utente();

if(isset($_POST))
{
	if(!empty($_FILES ['userfile']['tmp_name']))
	{
		echo "prova";
		require_once(INCDIR.'upload.inc.php');	
		$uploadObj = new upload();
		$img = array ("image/gif" , "image/png" , "image/pjpeg" , "image/jpeg");
		$vid = array();
		$doc = array();
		$size = 500000;		//set the max size for the upload file
		$path = 'imgs/foto/' ;
		$width_thumb = 160;
		$height_thumb = 200;
		$image_type = 1;
		switch($image_type) 
		{
			case 1: $exts = '.jpg'; break;		// the last argument indicate the compression of the image
			case 2: $exts = '.gif'; break;
			case 3: $exts = '.png'; break;
			default:die("Parametro mancante o errato");
		}
		$ext = $uploadObj -> getExtension($_FILES ['userfile']['name']);
		if(isset($_POST['idGioc']))
			$name = $_POST['idGioc'];
		switch( $uploadObj -> uploadFile ($size , $img , $vid , $doc, $path , $name.'-temp'))
		{
				case 0: 	switch (strtolower($uploadObj->getExtension($path.$name.'-temp.'.$ext)))			//switch for get the extension
								{
										case 'jpg' : $image = imagecreatefromjpeg($path.$name.'-temp.'.$ext); break;
										case 'gif' : $image = imagecreatefromgif($path.$name.'-temp.'.$ext); break;
										case 'png' : $image = imagecreatefrompng($path.$name.'-temp.'.$ext); break;
										default : die("File non supportato");
								}		
								$width = imagesx ($image);
								if($width > $width_thumb)
								{
									if($uploadObj -> resize($name , $path , $width_thumb , $height_thumb , $path.$name.'-temp.'.$ext, $image_type) )
									{
										$message[] = 0;
										$message[] = 'Upload effettuato correttamente';
										unlink($path.$name.'-temp.'.$ext);
									}
									else
									{
										$message[] = 1;
										$message[] = 'Problemi nel ridimensionamento';
									}
								}
								break;
				case 1: 	$message[] = 1;
								$message[] = 'Nessun file selezionato'; break;
				case 2: 	$message[] = 1;
								$message[] = 'File troppo grande'; break;
				case 3: 	$message[] = 1;
								$message[] = 'Tipo di file non supportato'; break;
				case 4: 	$message[] = 1;
								$message[] = 'Errore nell\'upload del file'; break;
		}
		$contenttpl->assign('message',$message);
	}

	if(!empty($_POST['nome']) && !empty($_POST['nome']))
		$giocatoreObj->aggiornaGiocatore($_POST['idGioc'],$_POST['cognome'],$_POST['nome']);
}
$contenttpl->assign('giocatori',$giocatoreObj->getAllGiocatori());
?>
