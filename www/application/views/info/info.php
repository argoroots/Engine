<?php
	/*foreach($online_users as $user) {
		//$users = array();
		if($user['user_id'] != 1) $users[] = strtolower($user['user']);
	} */
?>

<div style="width:220px; float:right; margin:60px 30px 0px 0px; ">
	<div class="graybox" style="margin-bottom:20px; padding:10px; text-align:justify;">
		<h2 style="padding:0px 0px 10px 0px;">Eriti suured tänud!</h2>
		eMugi tegevus ei oleks võimalik ilma mitmete vabatahtlike liikmete panuseta (te teate kes te olete)!
		<br /><br />
		Meie suured tänuavaldused ka Merikesele domeeni ja <a href="www.zone.ee">Zone.ee</a>'le majutuse eest.
		<div style="text-align:center;margin:10px 0px;">
			<a href="http://www.zone.ee" title="Zone Media"><img src="<?= site_url('images/zone.ee.png'); ?>" alt="Data Zone Logo" /></a>
		</div>
	</div>
</div>
<div style="width: 660px; float:left; margin:30px 0px 0px 30px; padding:0px; text-align:justify;">
	<h2 style="padding:0px 0px 10px 0px;">eMug?</h2>
	<a href="http://www.emug.ee">eMug</a> (Estonian Macintosh User Group) on Eesti macikasutajate ühing mis koosneb inimestest, kes on pühendunud või lihtsalt huvitatud Macintosh arvutitest ja muudest <a href="http://www.apple.com">Apple</a> seadmetest. Meie ülesandeks on laiendada macikasutajate ringkonda Eestis ning pakkuda neile abi ja tuge. Kõige olulisem on  võimaldada erinevate kogemuste ja taustaga macikasutajatel enda kogemusi teistega jagada.
	<div style="text-align:center;margin:30px 0px;">
		<img src="<?= site_url('images/emug-logo-big.png'); ?>" width="357px" height="133px" alt="eMug Logo" />
	</div>
</div>
<div style="1width: 660px; float:left; margin:0px 30px 60px 30px; padding:0px; text-align:justify;">
	<h2 style="padding:10px 0px 10px 0px;clear:both;">Ajalugu</h2>
	Macide osakaal Eesti arvutiturul on alati väike olnud, kuid aeg oli küps selle edasi arendamiseks ning sõnumi levitamiseks. Sellisel ühisel mõttel otsustasid 1999. talvel neli fanaatilist Mac’i kasutajat panna aluse organisatsioonile mis nüüdseks kannab nime eMug (alguses veel eeMug). Organisatsiooni tegevus avalikustati 2000. aasta veebruaris. 2001. aasta juuliks oli eMug piisavalt arenenud ja kasvanud, et vastata ettenähtud nõuetele ning registreeriti ametlikult Apple <a href="http://appleusergroups.com/locator/find/locate.cgi?country=189" title="AUG : Estonia">AUG</a> andmebaasis. Sellest hetkest saadik ei ole eMug enam lihtsalt mingi suvaline organisatsioon, vaid on <b>esimene ametlik Macintosh User Group Balti riikides</b>.
	<h2 style="padding:30px 0px 10px 0px;">INTERNET</h2>
	2001. aastal alustas ka meie esimene kodulehekülg (<a href="http://web.archive.org/web/20010822013925/www.hot.ee/emug/">www.hot.ee/emug</a>). Siis järgnesid segased (aga vaiksed) ajad kummalisel aadressil (mug.eu.estonia.org). 2003 sai üles pandud esimene foorum (mug.imo.ee), mis kasvas üle ootuste kiiresti tänu paljude soovitustele ning veel enamate küsimustele ja vastustele. Mõni aeg hiljem lisandus uudiste ja artiklite leht (mac.arx.ee). 21. veebruaril 2006 kolisid foorum ja uudisteleht kokku ühisele aadressile emug.ee ning uudistelehele ja foorumile lisandus wiki. 2009 aastast on eMug esindatud ka <a href="http://www.facebook.com/pages/eMug/128714643858">Facebook</a>'is.
	<br /><br />
	<!--Hetkel on meie kodulehel <?= number_format($site_statistics['total_users'], 0, ',', ' ');?> aktiivset kasutajat kes on foorumisse teinud kokku <?= number_format($site_statistics['total_posts'], 0, ',', ' ');?> postitus. Just momendil on lehel <?= isset($online_users[1]['count']) ? implode(', ', $users) .' ja '. $online_users[1]['count'] .' kontvõõrast.' : '.'; ?>-->
</div>
