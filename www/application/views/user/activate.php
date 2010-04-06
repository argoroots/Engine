<div style="margin:30px; clear:both">
	<h2 style="padding:0px 0px 10px 0px;">Hei <?= $username; ?>!</h2>
	Vali alltpoolt endale uus OpenID tuvastaja.
</div>

<div class="graybox" style="margin:0px auto 60px auto; width:690px; padding:10px 10px 7px 10px;">
	
	<h2 style="padding:5px 5px 10px 5px;">Tuvastaja</h2>
	<?php if (isset($msg)) { echo "<div class=\"alert\">$msg</div>"; } ?>
	<?php if (isset($success)) { echo "<div class=\"success\">$success</div>"; } ?>
	
	<img id="mobiilid" class="openid active" src="<?= base_url(); ?>/images/openid/mobiilid.png" width="64" height="23" alt="Mobiil ID" />
	<img id="idkaart" class="openid active" src="<?= base_url(); ?>/images/openid/idkaart.png" width="64" height="23" alt="ID Kaart" />
	<img id="google" class="openid active" src="<?= base_url(); ?>/images/openid/google.png" width="64" height="23" alt="Google" />
	<img id="yahoo" class="openid active" src="<?= base_url(); ?>/images/openid/yahoo.png" width="64" height="23" alt="Yahoo" />
	<img id="myspace" class="openid active" src="<?= base_url(); ?>/images/openid/myspace.png" width="64" height="23" alt="mySpace" />
	<img id="myopenid" class="openid active" src="<?= base_url(); ?>/images/openid/myopenid.png" width="64" height="23" alt="myOpenID" />
	<img id="openid" class="openid active" src="<?= base_url(); ?>/images/openid/openid.png" width="64" height="23" alt="OpenID" />
	
	<form id="openid_form" method="post" action="<?php echo site_url('user/login'); ?>" style="margin-top: 5px; float:right;display:none;" >
		<fieldset>
			<b>OpenID URL:</b>
			<a href="" id="submit_openid_form" class="button" style="margin-left: 10px; float: right;">Sisene</a>
			<input type="text" name="openid_url" id="openid_url" value="" style="width: 225px; margin-bottom:5px;" />
		</fieldset>
	</form>
	
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		$("#mobiilid").click(function () {openid_submit("openid.ee/server/xrds/mid", this);});
		$("#idkaart").click(function () {openid_submit("openid.ee/server/xrds/eid", this);});
		$("#google").click(function () {openid_submit("google.com/accounts/o8/id", this);});
		$("#yahoo").click(function () {openid_submit("yahoo.com", this);});
		$("#myspace").click(function () {openid_submit("myspace.com", this);});
		$("#myopenid").click(function () {openid_submit("myopenid.com", this);});
		
		$("img#openid").click(function () {
			$("form#openid_form").toggle('blind', {direction:'vertical'}, 250);
		});
		
		function openid_submit(url, img) {
			$(img).addClass("openid-selected");
			$("#openid_url").val(url);
			$("#openid_form").submit();
		}
		
	});
</script>