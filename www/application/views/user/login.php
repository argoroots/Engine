<div style="margin:30px;">
	<div style="width: 610px;; text-align:justify; float:right;">
		eMug.ee kasutab kasutajate autentimiseks <a href="http://en.wikipedia.org/wiki/OpenID">OpenID</a> standardit. See tähendab, et meie lehe kasutamiseks ei ole vaja meeles pidada (järjekordset!) kasutajanime ja parooli. Isiku tuvastamiseks kõlbab ID-kaart, mobiil-ID, Google (Orkut'i?), Yahoo või mõni muud OpenID teenust pakkuva lehe (e. tuvastaja) konto. Kui valid mõne tuvastaja, küsib ta sinult, kas sa lubad emug.ee'l selle kontoga sisse logida. Paroole esialgne tuvastaja meie serverisse ei saada. See toimib umbes nagu ID kaart, ainult et ID kaardi asemel saad kasutada ka mõnda olemasolevat kontot kuskil mujal.<br />
		<br />
		Näiteks: Sul on Google'i konto. Selleks, et emug.ee foorumit kasutada klikid lihtsalt vasakul Google logole. Kui oled täna juba Google'isse sisse loginud, saad kohe ka emug.ee foorumisse sisse. Kui sa veel ei ole Google kontoga sees, siis palutakse sul sinna oma parooliga sisse logida. Nii polegi sul vaja luua ega meeles pidada üht eraldi kontot siin foorumis.
	</div>

	<div class="graybox" style="margin-bottom: 30px; padding:5px; width:244px;">
		
		<h2 style="padding:5px 5px 10px 5px;">Sisene</h2>
		<?php if (isset($msg)) { echo "<div class=\"alert\">$msg</div>"; } ?>
		<?php if (isset($success)) { echo "<div class=\"success\">$success</div>"; } ?>
		
		<img id="mobiilid" class="openid active" src="<?= base_url(); ?>/images/openid/mobiilid.png" width="88" height="31" alt="Mobiil ID" />
		<img id="idkaart" class="openid active" src="<?= base_url(); ?>/images/openid/idkaart.png" width="88" height="31" alt="ID Kaart" />
		<img id="google" class="openid active" src="<?= base_url(); ?>/images/openid/google.png" width="88" height="31" alt="Google" />
		<img id="yahoo" class="openid active" src="<?= base_url(); ?>/images/openid/yahoo.png" width="88" height="31" alt="Yahoo" />
		<img id="myspace" class="openid active" src="<?= base_url(); ?>/images/openid/myspace.png" width="88" height="31" alt="mySpace" />
		<img id="myopenid" class="openid active" src="<?= base_url(); ?>/images/openid/myopenid.png" width="88" height="31" alt="myOpenID" />
		<img id="openid" class="openid active" src="<?= base_url(); ?>/images/openid/openid.png" width="88" height="31" alt="OpenID" />
		
		<form id="openid_form" method="post" action="<?php echo site_url('user/login'); ?>" style="margin-top: 10px; display:none;" >
			<fieldset style="margin: 0px; padding: 5px;">
				<b>OpenID URL:</b>
				<input type="text" name="openid_url" id="openid_url" value="" style="width: 225px; margin-bottom:5px;" />
				<a href="" id="submit_openid_form" class="button" style="margin-right: 1px; float: right;">Sisene</a>
			</fieldset>
		</form>
		
	</div>

	<div class="whitebox" style="padding:10px; float:left; width:230px; text-align:justify;">
		<h2 style="padding:0px 0px 10px 0px;">Mölli</h2>
		emug.ee kasutajaks saamiseks sisesta siia enda email* ning sellele saadetakse aktiveerimisviide.
		<form id="register_form" method="post" action="<?php echo site_url('user/register'); ?>">
			<fieldset style="margin:10px 0px;;">
				<b>Email:</b>
				<input type="email" name="register_email" id="register_email" value="" style="width: 220px; margin-bottom:5px;" />
				<a href="" id="submit_register_form" class="button" style="margin-right: 1px; float: right;">Saada kiri</a>
			</fieldset>
		</form>
		*vana foorumi kasutaja ületoomiseks või enda konto uue tuvastajaga sidumiseks sobib ka kasutajanimi.
		
	</div>
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
			$("form#openid_form").toggle('blind', { direction: 'vertical' }, 250);
		});
		
		function openid_submit(url, img) {
			$(img).addClass("openid-selected");
			$("#openid_url").val(url);
			$("#openid_form").submit();
		}
		
		
		$("#submit_register_form").click(function () {
			if($("input#register_email").val() != "") {
				$("form#register_form").submit();
			};
			return false;
		});

	});
</script>