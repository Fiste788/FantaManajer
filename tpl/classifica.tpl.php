<?php $i = 1; ?>
<div id="classifica-content" class="main-content">
	<div id="classifica-container" class="column last">
		<table cellpadding="0" cellspacing="0" class="column last no-margin" style="width:316px;overflow:hidden;">
			<tbody>
				<tr>
					<th style="width:20px">P.</th>
					<th class="nowrap" style="width:180px">Nome</th>
					<th style="width:70px">Punti tot</th>
				</tr>
				<?php foreach($this->classificaDett as $key => $val): ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td class="squadra no-wrap" id="squadra-<?php echo $key; ?>"><?php echo $this->squadre[$key]['nome']; ?></td>
					<td><?php echo array_sum($val); ?></td>
				 </tr>
				<?php $i++;$flag = $key; endforeach; ?>
			</tbody>
		</table>
		<div id="tab_classifica" class="column last"  style="height:<?php echo (27 * (count($this->classificaDett) +1)) +18 ?>px">
		<?php $appo = array_keys($this->classificaDett); $i = $appo[0]; ?>
		<?php if(key($this->classificaDett[$flag]) != 0): ?>
		<table class="column last" cellpadding="0" cellspacing="0" style="width:<?php echo count($this->classificaDett[$i])*50; ?>px;margin:0;">
			<tbody>
				<tr>
					<?php foreach($this->classificaDett[$flag] as $key => $val): ?>
						<th style="width:35px"><?php echo $key ?></th>
					<?php endforeach; ?>
				</tr>
				<?php foreach($this->classificaDett as $key => $val): ?>
				<tr>
				<?php foreach($val as $secondKey=>$secondVal): ?>
					<td<?php if(isset($this->penalità[$key][$secondKey])) echo ' title="Penalità: ' . $this->penalità[$key][$secondKey] . ' punti" class="rosso"' ?>>
						<a href="<?php echo $this->linksObj->getLink('dettaglioGiornata',array('giornata'=>$secondKey,'squadra'=>$this->squadre[$key]['idUtente'])); ?>"><?php echo $val[$secondKey]; ?></a>
					</td>
					<?php endforeach; ?>
				</tr>
				<?php $i++; endforeach; ?>
			</tbody>
		</table>
		<?php endif; ?>
		</div>
	</div>
	<?php if(!empty($this->giornate)): ?>
	<div id="grafico">
		<div id="placeholder" class="column last" style="width:950px;height:300px;clear:both;">&nbsp;</div>
		<div id="overview" class="column " style="width:200px;height:100px;clear:both;cursor:pointer;">&nbsp;</div>
		<p class="column" style="width:720px;">Seleziona sulla miniatura una parte di grafico per ingrandirla. Per questa funzionalità si consiglia di usare browser come Safari, Firefox o Opera invece di altri meno performanti come Internet Explorer</p><p class="column" id="selection">&nbsp;</p>
		<div id="hidden" class="hidden">&nbsp;</div>
		<a id="clearSelection" class="column hidden">(Cancella selezione)</a>
	</div>
	<script id="source" type="text/javascript">
		var datasets = {
			<?php $i=0; foreach($this->classificaDett as $key => $val): $i++; ?>"<?php echo $key; ?>": {
				label: "<?php echo $this->squadre[$key]['nome']; ?>",
				data: [<?php foreach($val as $secondKey=>$secondVal): ?><?php echo '['.$secondKey.','.$val[$secondKey].']'; if(count($secondVal)-$secondKey != $secondKey-1) echo ','; endforeach; ?>]
			}<?php if(count($this->classificaDett) != $i) echo ",\n"; ?>
			<?php endforeach; ?>
		};
		var medie = {
			<?php $i=0; foreach($this->classificaDett as $key => $val): $i++; ?>
			<?php $media = array_sum($this->classificaDett[$key])/count($this->classificaDett[$key]) ?>
			"<?php echo $key ?>" : {label: "Media <?php echo $this->squadre[$key]['nome']; ?> (<?php echo substr($media,0,5); ?>)",data: [[1,<?php echo $media; ?>],[<?php echo count($this->classificaDett[$key]) ?>,<?php echo $media ?>]]}<?php if(count($this->classificaDett) != $i) echo ",\n"; ?>
			<?php endforeach; ?>
		};
		var squadra = {val:<?php if($_SESSION['logged'] == TRUE && $_SESSION['legaView'] == $_SESSION['idLega']) echo $this->squadre[$_SESSION['idSquadra']]['idUtente'];else echo 'false'; ?>};
		$(document).ready(function() {
			$(document).classifica(datasets,medie,squadra);
		});

	</script>
	<?php endif; ?>
</div>
