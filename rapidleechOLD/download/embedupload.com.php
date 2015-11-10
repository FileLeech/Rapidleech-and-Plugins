<?php

if (!defined('DOWNLOAD_DIR')) {

	if (substr($options['download_dir'], -1) != '/') $options['download_dir'] .= '/';

	define('DOWNLOAD_DIR', (substr($options['download_dir'], 0, 6) == 'ftp://' ? '' : $options['download_dir']));

}

$_GET['proxy'] = !empty($proxy) ? $proxy : (!empty($_GET['proxy']) ? $_GET['proxy'] : '');

$not_done = true;



	$not_done = $login = false;

	$domain = 'embedupload.com';

	$referer = "http://$domain/";





		$cookie = array('SelectedServices' => 'Fichier%3BFilerio%3BZippyshare%3BNetload%3BBayfiles%3BShareonline%3BHugefiles%3BTusfiles%3BFreakshare%3B');

		$page = geturl($domain, 80, '/', $referer, $cookie, $post, 0, $_GET['proxy'], $pauth, 0);is_page($page);

		$cookie = GetCookiesArr($page, $cookie);

		//if (empty($cookie['login'])) html_error("Login Error: Cannot find 'login' cookie.");

		$login = true;

	//} else html_error('Login failed: User/Password empty.');



	// Retrive upload ID

	echo "<table style='width:600px;margin:auto;'>\n<tr><td align='center'>\n";

	echo "<script type='text/javascript'>document.getElementById('login').style.display='none';</script>\n<div id='info' width='100%' align='center'>Retrive upload ID</div>\n";

	

	$page = geturl($domain, 80, '/', $referer, $cookie, 0, 0, $_GET['proxy'], $pauth);is_page($page);

	$form_url=cut_str($page,"var urlPlUplod_httpHostPathToUpload = '","';");

	$prog_url=$domain;

	if (!$url = parse_url($form_url)) html_error('Error getting upload url');

	// Uploading

	echo "<script type='text/javascript'>document.getElementById('info').style.display='none';</script>\n";

	$fpost = array();

	$fpost['name']=$lname;

	$fpost['Fichier']=1;

	$fpost['Filerio']=1;

	$fpost['Zippyshare']=1;

	$fpost['Netload']=1;

	$fpost['Bayfiles']=1;

	$fpost['Shareonline']=1;

	$fpost['Hugefiles']=1;

	$fpost['Tusfiles']=1;

	$fpost['Freakshare']=1;

	$fpost['site']="http://";

	$fpost['do']="upload";

	$fpost['email']="";

	

	

	$url = parse_url($form_url);

	$upfiles = upfile($url['host'], 80, $url['path'].(!empty($url['query']) ? '?'.$url['query'] : ''), $referer, $cookie, $fpost, $lfile, $lname, 'file_1', '', $_GET['proxy'], $pauth);

	//$upfiles.="made by ghost";

	// Upload Finished

	echo "<script type='text/javascript'>document.getElementById('progressblock').style.display='none';</script>";

	$resp['download_url']="http://www.embedupload.com/?d=".explode($nn,$upfiles)[8];

	if (!empty($resp['download_url'])) {

		$download_link = $resp['download_url'];

		if (!empty($resp['remove_url'])) $delete_link = $resp['remove_url'];

	} else html_error("Download link not found ({$resp['state']}).", 0);
?>
