<?php
 
if (!defined('RAPIDLEECH')) exit;
$_T8 = array('v' => 1); // Version of this config file. (Do Not Edit)
 
/* # Plugin's Settings # */
$_T8['domain'] = 'www.mightyupload.com'; // May require the www. (Check first if the site adds the www.)
$_T8['anonUploadDisable'] = false; // Disallow non-registered users upload. (XFS Pro)
$_T8['anonUploadLimit'] = 0; // File-size limit for non-registered users (MB) | 0 = Plugin's limit | (XFS Pro)
 
// Advanced Settings (Don't edit it unless you know what are you doing)
        $_T8['xfsFree'] = false; // Change to true if the host is using XFS free.
        $_T8['path'] = '/'; // URL path to XFS script, default: '/'
        $_T8['opUploadName'] = 'upload'; // Custom ?op=value for checking upload page, default: 'upload'
 
$acc_key_name = str_ireplace(array('www.', '.'), array('', '_'), $_T8['domain']); // (Do Not Edit)
 
/* # Account Info # */
$upload_acc[$acc_key_name]['user'] = 'Your Login Here'; //Set your login
$upload_acc[$acc_key_name]['pass'] = 'Your Pass Here'; //Set your password
 
if (!file_exists(HOST_DIR . 'upload/GenericXFSHost.inc.php')) html_error('Cannot load "'.htmlentities(HOST_DIR).'upload/GenericXFSHost.inc.php" (File doesn\'t exists), please install lastest version from: http://www.rapidleech.com/index.php/topic/14014-upload-plugin-for-sites-with-xfs-pro/ or http://pastebin.com/E0z7qMU1');
require(HOST_DIR . 'upload/GenericXFSHost.inc.php');
 
// Based on Written by Th3-822 - Last Update: [05-8-2013]
// Created by Titeuf [04-09-2013]
//Work for free registered member, not tested with a premium account (I do not have a premium account at mightyupload.com)
?>
