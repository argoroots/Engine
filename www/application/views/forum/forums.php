<?php

$topics = $topic['childs'];


function show_category($categories, $id) {
	
	if(!isset($categories[$id])) return FALSE;
	
	$category_forum_style = array(
		6 => 'forumverticallist',
		7 => '',
		8 => '',
		9 => '',
		10 => 'forumverticallist',
	);
	
	$category = $categories[$id];
	
	$result = '';
	$result .= '<h2>'. $category['name'] .'</h2>' ."\n";
	
	foreach($category['childs'] as $key => $value) {
		$result .= '<a class="forumlink '. $category_forum_style[$id] .'" href="'. site_url($value['url']) .'" title="'. $value['last_child']['username'] .' : '. date_to_text($value['last_child']['create_time']) .'">' ."\n";
		$result .= '<span class="forumtopiccount">'. number_format($value['childs_count'], 0, ',', ' ') .'</span>' ."\n";
		if($value['new'] == TRUE) {
			$result .= '<span style="color:#0085CF;"><img style="float:left; margin:0px; padding:2px 5px 0px 0px;" src="'. site_url('images/new_post.png') .'" width="10px" heght="10px"> '. $value['name'] .'</span>' ."\n";
		} else {
			$result .= $value['name'] ."\n";
		}
		$result .= '<span class="forumdesc">'. $value['content'] .'</span>' ."\n";
		$result .= '</a>' ."\n";
	}
	
	return $result;
	
}

?>

<table class="forumstable" cellpadding="0" cellspacing="10" border="0">
<?php if(isset($topics[6]['name'])) { ?>
	<tr>
		<td colspan="3">
			<?= show_category($topics, 6); ?>
		</td>
	</tr>
<?php } ?>
	<tr>
		<td class="whitebox" style="width:38%;">
			<img src="<?= site_url('images/globe.png'); ?>" width="64px" height="64px" alt="" />
			<?= show_category($topics, 7); ?>
		</td>
		<td rowspan="2" class="whitebox" style="width:38%;">
			<img src="<?= site_url('images/iphoto.png'); ?>" width="64px" height="64px" alt="" />
			<?= show_category($topics, 8); ?>
		</td>
		<td rowspan="2" class="graybox" style="width:24%;">
			<h2>VIIMASED TEATED</h2>
			<ul>
		<?php foreach($last_topics as $lasttopic) { ?>
				<li>
					<a class="<?= ($lasttopic['new'] == TRUE) ? 'newtopic' : 'lasttopiclink'; ?>" href="<?= site_url($lasttopic['url']); ?>" title="<?= $lasttopic['username']; ?> : <?= date_to_text($lasttopic['create_time']); ?>">
						<span style="float:right; margin-left:10px;color:#F2F2F2;"><?= $lasttopic['childs_count']; ?></span><?= $lasttopic['name']; ?>
					</a>
				</li>
		<?php } ?>
			</ul>
		</td>
	</tr>
	<tr>
		<td class="whitebox">
			<img src="<?= site_url('images/imac.png'); ?>" width="64px" height="64px" alt="" />
			<?= show_category($topics, 9); ?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<?= show_category($topics, 10); ?>
		</td>
	</tr>
</table>
