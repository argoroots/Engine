<?php

function rnd_image() {
	$path = './images/header/';
	$narray=array();
	$dir_handle = @opendir($path) or die();
	$i=0;
	while($file = readdir($dir_handle)) {
		if($file != '.' && $file != '..' && $file !='index.php') {
			$narray[$i]=$file;
			$i++;
		}
	}
	closedir($dir_handle);
	$j = rand(0, $i-1);
	return $narray[$j];
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head profile="http://gmpg.org/xfn/11">
		
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-XRDS-Location" content="<?= site_url('user/xrds'); ?>" />
		<meta name="viewport" content="width = 1000" />
		
		<title>eMug<?= $page_title ? ' : '.  $page_title : ''; ?></title>
		
		<link rel="stylesheet" type="text/css" media="screen" href="<?= site_url('css'); ?>/screen.css" />
		<!--[if IE]>
		<link rel="stylesheet" type="text/css" media="screen" href="<?= site_url('css'); ?>/ie.css" />
		<![endif]-->
		
		
		<link rel="shortcut icon" href="<?= site_url('images/emug.png'); ?>" />
		<link rel="apple-touch-icon" href="<?= site_url('images/emug.png'); ?>" />
		<link rel="apple-touch-startup-image" href="<?= site_url('images/emug.png'); ?>" />
		
		<script src="http://www.google.com/jsapi" type="text/javascript"></script>
		<script type="text/javascript">
			google.load("jquery", "1");
			google.load("jqueryui", "1");
		</script>
		
		<!-- Load IxEdit ( You can delete these two lines after deploying. ) -->
		<script type="text/javascript" src="<?= site_url(''); ?>javascript/ixedit/ixedit.packed.js1"></script>
		<link type="text/css" href="<?= site_url(''); ?>javascript/ixedit/ixedit.css" rel="stylesheet" />	
		
		<script src="<?= site_url(''); ?>javascript/curvycorners.js" type="text/javascript"></script>
		
	</head>
	<body>
		
		<div><img src="<?= site_url(); ?>images/beta.png" style="position:fixed; right:0px; top:0px;z-index:1000;" alt="beta" /></div>
		
		<div id="page" style="background-image:url('<?= site_url(); ?>images/header/<?= rnd_image(); ?>');">
			<div id="header">
				<a href="<?= site_url(); ?>">
					<span style="font-size:24px; font-weight:bold;">EMUG<br /></span>
					<span>Estonian Mac User Group</span>
				</a>
			</div>
			<div id="menu">

<?= $page_menu; ?>

			</div>
			<!-- div id="searchbar">
				<input type="search" class="textfield" name="keywords" results="16" autosave="emug_search" placeholder="Otsi..." title="" value="" maxlength="80">
			</div -->
			
			<div id="content">

<?= $page_content; ?>

			</div>
			
			<div id="aug">
				<img src="<?= site_url(); ?>images/aug.png" width="120px" alt="logo" />
			</div>
			
		</div>
		
		
		<div id="footer">
			<a href="http://validator.w3.org/check?uri=referer" style="float:right; padding-right:10px;">Copyright ©<?= date('Y'); ?></a>
			<a href="mailto:argo@roots.ee" style="float:left; padding-left:10px;">Made by Argo Roots</a>
		</div>
		
		<script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
			try {
				//var pageTracker = _gat._getTracker("UA-260765-1");
				pageTracker._trackPageview();
			} catch(err) {}
		</script>
		
	</body>
</html>