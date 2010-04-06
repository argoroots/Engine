<h2><a href="<?= site_url($topic['path'][count($topic['path'])-2]['url']); ?>"><?= $topic['path'][count($topic['path'])-2]['name']; ?></a> &raquo; <?= $topic['name']; ?></h2>
<div id="poststable">
	<img class="postavatar" src="<?= site_url(avatar($topic['user_id'])); ?>" width="72" height="72" alt="" />
	<div class="postmessage">
		<a href="<?= site_url($topic['url']); ?>" name="<?= $topic['id']; ?>" style="float:right;" title="<?= date('d.m.Y H:i', $topic['create_time']); ?>"><?= date_to_text($topic['create_time']); ?></a>
		<b><?= $topic['username']; ?>:</b><br /><br />
		<?= format_content($this->bbcode->Parse($topic['content'])); ?>
	</div>
	<div>
		<?php if(count($topic['childs']) > 3) { ?>
		<div style="float:right; padding-top:10px;"><a href="<?= site_url($topic['url']); ?>#newpost" class="linkbutton">Vasta...<img src="<?= site_url('images/link_right.png'); ?>" alt="" /></a></div>
		<?php } ?>
		<img class="poststop" src="<?= site_url('images/poststop.png'); ?>" width="29" height="15" alt="" />
		<div class="poststop"></div>
	</div>

<?php
	foreach($topic['childs'] as $reply) { 
		$new_topic_style = ($reply['new'] == TRUE) ? 'color:#0085CF;' : '';
		$sticky_topic_style = ($reply['sticky'] == TRUE) ? 'font-weight:bold;' : '';
?>
	<div class="post">
		<img class="postavatar" style="float:left;" src="<?= site_url(avatar($reply['user_id'])); ?>" width="72" height="72" alt="" />
		<div class="postcontent">
			<a href="<?= site_url($reply['url']); ?>" name="<?= $reply['id']; ?>" style="float:right;" title="<?= date('d.m.Y H:i', $reply['create_time']); ?>"><?= date_to_text($reply['create_time']); ?></a>
			<b><?= $reply['username']; ?>:</b><br /><br />
			<?= format_content($this->bbcode->Parse($reply['content'])); ?>
		</div>
	</div>
<?php } ?>
	<div id="newpost">
		<a name="newpost"></a>
	<?php if($topic['rights']['add_child'] != 1) { ?>
		<span style="padding:25px; text-align:center; display:block;font-weight:bold;"> Postitamiseks pead olema <a href="<?= site_url('user/login'); ?>">sisse loginud</a>.</span>
	<?php } else { ?>
		<img class="postavatar" src="<?= site_url(avatar($this->sess->user_id)); ?>" width="72" height="72" alt="" />
		<b><?= $this->sess->username; ?>:</b><br /><br />
		<textarea id="newpostbox" name="post" rows="5" cols=""></textarea>
		<div style="height:35px; clear:both;">
			<a class="button" style="float:right; margin-top: 10px;" href="">Saada vastus</a>
		</div>
	<?php } ?>
	</div>
	

</div>

<script type="text/javascript"> 
	
	$(document).ready(function(){
		$("#newpostbox").keyup(function () {
			var text = this.value
			var split = text.split("\n")
			if(split.length > 4) {
				this.rows = split.length + 1;
			} else {
				this.rows = 5;
			}
		});
	});
 
</script> 
