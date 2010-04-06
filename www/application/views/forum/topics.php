<a href="" class="button" style="width:100px; float:right; margin:30px 30px 20px 0px;">Uus teema</a>
<h2><?= $topic['name']; ?></h2>


<table class="topicstable" cellpadding="0" cellspacing="0">
	<tr>
		<th style="text-align:left;padding-left:30px;">
			Teema
		</th>
		<th style="text-align:right;padding-right:0px;">
			Vastuseid
		</th>
		<th>
			Viimati vastas
		</th>
	</tr>
<?php
	foreach($topic['childs'] as $topic) { 
		$new_topic_style = ($topic['new'] == TRUE) ? 'color:#0085CF;' : '';
		$sticky_topic_style = ($topic['sticky'] == TRUE) ? 'font-weight:bold;' : '';
?>
	<tr>
		<td class="topicname">
			<a style="<?= $new_topic_style .' '. $sticky_topic_style; ?>" href="<?= site_url($topic['url']); ?>" title="Ava teema...">
				<?= $topic['name']; ?>
				<span>
					<br /><?= $topic['username']; ?> <?= date_to_text($topic['create_time']); ?>
				</span>
			</a>
		</td>
		<td style="<?= $new_topic_style; ?>" class="childscount">
			<?= number_format($topic['childs_count'], 0, ',', ' '); ?>
		</td>
		<td class="lastposter">
<?php if($topic['childs_count'] > 0) { ?>
			<a style="<?= $new_topic_style; ?>" href="<?= site_url($topic['last_child']['url']); ?>" title="Vaata viimast postitust...">
				<?= $topic['last_child']['username']; ?>
				<span style="<?= $new_topic_style; ?>">
					<br /><?= date_to_text($topic['last_child']['create_time']); ?>
				</span>
			</a>
<?php } ?>
		</td>
	</tr>
<?php } ?>
</table>

<script type="text/javascript"> 
	
	$(document).ready(function(){
		$("tr").hover(function () {
			$("tr").removeClass("selected_row");
			$(this).addClass("selected_row");
		});
	});
 
</script> 
