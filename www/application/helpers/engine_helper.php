<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function date_to_text($date) {
	
	$period = floor((time() - $date) / 60);
	if($period == 0) return 'vähem kui minut tagasi';
	if($period == 1) return '1 minut tagasi';
	if($period < 60) return $period .' minutit tagasi';
	
	$period = floor((time() - $date) / (60 * 60));
	if($period <= 1) return '1 tund tagasi';
	if($period < 24) return $period .' tundi tagasi';

	$period = floor((time() - $date) / (60 * 60 * 24));
	if($period <= 1) return 'eile';
	if($period <= 2) return 'üleeile';
	if($period < 7) return $period .' päeva tagasi';

	$period = floor((time() - $date) / (60 * 60 * 24 * 7));
	if($period <= 1) return '1 nädal tagasi';
	if($period < 4) return $period .' nädalat tagasi';

	$period = floor((time() - $date) / (60 * 60 * 24 * 30));
	if($period <= 1) return '1 kuu tagasi';

	return date('d.m.Y', $date);

}

function avatar($user_id) {
	if(file_exists(APPPATH .'../images/avatars/'. $user_id .'.png')) {
		return 'images/avatars/'. $user_id .'.png';
	} else {
		return 'images/guest.png';
	}
}

function format_content($content) {
	//$result = parse_message($content, FALSE);
	$result = $content;
	$result = (substr($result, 0, 4) == '<img') ? '<img style="float:right;max-height:100px;padding:5px 0px 10px 10px;"'. substr($result, 4) : $result;
	return $result;
}

?>