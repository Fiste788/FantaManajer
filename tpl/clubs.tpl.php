<?php foreach($this->elencoClub as $key => $val): ?>
	<a class="column" style="width:140px;text-align:center;margin:25px;" href="<?php echo Links::getLink('dettaglioClub',array('club'=>$val->id)); ?>" class="column" title="Rosa <?php echo $val->partitivo." ".$val->nome; ?>">
		<img alt="<?php echo $val->id; ?>" src="<?php echo CLUBSURL . $val->id . '.png'; ?>" />
	</a>
<?php endforeach; ?>
