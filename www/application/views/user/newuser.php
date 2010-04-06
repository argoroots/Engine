<div style="margin:30px; clear:both">
	<h2 style="padding:0px 0px 10px 0px;">Reeglid</h2>
	1. Käitu viisakalt<br />
	2. Kasuta otsingut enne postitamist<br />
	3. Anna teemale korralik pealkiri<br />
	4. Kirjuta selges keeles ja kasuta kirjavahemärke<br />
	5. Piraat-tarkvara ja häkkimise teemaline arutelu on keelatud<br />
	6. Loe läbi <a href="http://www.emug.ee/wiki/FoorumiReeglid">need nõuanded</a><br />
	7. Kui tahad, <a href="http://www.emug.ee/foorum/viewtopic.php?pid=45576">ütle tere ja tutvusta ennast</a><br />
	<p style="margin-top:10px; color:red; text-align:center;">eMugi toimkond jätab endale õiguse eemaldada reegleid eiravad postitused ning vajaduse korral ka nende autorite kontod.</p>
</div>

<div class="whitebox" style="margin-left:30px; padding:12px; width:150px; float:left; text-align:justify;">
	<h2 style="padding:0px 0px 5px 0px;">1. Kasutajanimi</h2>
	<form id="register_form" method="post" action="<?php echo site_url('user/register'); ?>">
		<fieldset style="margin:10px 0px;;">
			<input type="text" name="register_username" id="register_username" value="" style="width: 145px; margin-bottom:5px;" />
		</fieldset>
	</form>
	<p id="username_used" style="color:red; font-weight:bold; display:none;">NB! Selline nimi on juba kasutuses!</p>
</div>

<div class="graybox" style="margin:0px 30px 60px 10px; padding:10px 10px 7px 10px; float:left; text-align:justify;">
	
	<h2 style="padding:5px 5px 10px 5px;">2. Tuvastaja</h2>
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
			<input type="hidden" name="openid_username" id="openid_username" value="" />
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
			var iusername = $("input#register_username").val();
			$.post(
					"<?= site_url('user/username_check'); ?>",
					{username: iusername},
					function(data){
						if(data != "OK") {
							alert("Sisestage kasutajanimi!");
							$("input#register_username").focus().select();
						} else {
							if($("input#register_username").val() != "") {
								$(img).addClass("openid-selected");
								$("#openid_url").val(url);
								$("#openid_username").val($("input#register_username").val());
								$("#openid_form").submit();
							} else {
								alert("Sisestage kasutajanimi!");
								$("input#register_username").focus();
							};
						}
					}
				);
		}
		
		$("input#register_username").keyup(function() {
			var iusername = $("input#register_username").val();
			$.post(
					"<?= site_url('user/username_check'); ?>",
					{username: iusername},
					function(data){
						if(data != "OK") {
							$("p#username_used").show()
						} else {
							$("p#username_used").hide()
						}
					}
				);
		});
		
	});
</script>