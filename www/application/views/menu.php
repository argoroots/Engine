<?php
	
	if($this->sess->is_guest) {
		$menu = array(
			'uudised' => 'Uudised',
			'foorum' => 'Foorum',
			'info' => 'Info',
			'user/login' => 'Sisene',
		);
	} else {
		$menu = array(
			'uudised' => 'Uudised',
			'foorum' => 'Foorum',
			'info' => 'Info',
			'pm' => 'Erasõnumid',
			'user/logout' => 'Välju',
		);
	}
	
	foreach($menu as $link => $title) {
		$attrs = NULL;
		if($page_menu_selected == $link) $attrs['class'] = 'selected_menu';
		echo anchor(site_url($link), $title, $attrs) ."\n";
	}
	
?>

<script type="text/javascript">
	$("a.selected_menu").next().addClass("last_menu");
</script>