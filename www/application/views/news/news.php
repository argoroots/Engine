<div class="graybox" style="width:265px; margin:30px; padding-bottom:10px; float:right;">
	<h2 style="margin:0px; padding:10px 0px 10px 10px;">FOORUMI VIIMASED TEATED</h2>
	<ul>
<?php foreach($last_posts as $lastpost) { ?>
		<li>
			<a class="<?= ($lastpost['new'] == TRUE) ? 'newtopic' : 'lasttopiclink'; ?>" href="<?= site_url($lastpost['url']); ?>" title="<?= $lastpost['user']; ?> : <?= date_to_text($lastpost['date']); ?>">
				<span style="float:right; margin-left:10px;color:#F2F2F2;"><?= $lastpost['childs_count']; ?></span><?= $lastpost['name']; ?>
			</a>
		</li>
<?php } ?>
	</ul>
</div>

<div id="news">
<?php foreach($post['childs'] as $news){ ?>
	<h2><a href="<?= $news['url']; ?>"><?= $news['name']; ?></a></h2>
	<div class="content">
	<?= format_content($this->bbcode->Parse($news['content'])); ?>
	</div>
	<a class="linkbutton" href="<?= site_url($news['last_cild_url']); ?>"><?= ($news['childs_count'] == 1) ? '1 kommentaar' : $news['childs_count'] .' kommentaari'; ?><img src="<?= site_url('images/link_right.png'); ?>" alt="" /></a>
<?php } ?>
</div>