Hei <?= $username; ?>!<br />
<br />
<br />
Oled avaldanud soovi enda <?= anchor(site_url(''), 'eMug'); ?>'i kasutajaga siduda uut OpenID tuvastajat. Sidumiseks kliki alloleval viitel. Kui sa seda teha ei soovi siis ignoreeri seda kirja.<br />
<br />
<?= anchor(site_url('user/activate/'. $key)); ?><br />
<br />
<br />
Tänud!