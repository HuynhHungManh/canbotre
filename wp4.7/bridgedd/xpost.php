<?php
/*
	BridgeDD Cross-Post AJAX Helper
	Copyright � 2015 by Dion Designs.
	All Rights Reserved.
*/
define('IN_PHPBB', true); $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './'; $phpEx = substr(strrchr(__FILE__, '.'), 1); $wpid = (isset($_GET['p'])) ? intval($_GET['p']) : 0; $xpid = (isset($_POST['xp'])) ? intval($_POST['xp']) : 0; if ($wpid || $xpid) { require($phpbb_root_path . 'includes/startup.' . $phpEx); require($phpbb_root_path . 'config.' . $phpEx); require($phpbb_root_path . 'includes/db/' . $dbms . '.' . $phpEx); $db = new $sql_db(); if (@$db->sql_connect($dbhost, $dbuser, $dbpasswd, $dbname, $dbport, false, false)) { if ($wpid) { $sql = 'SELECT content FROM bridgedd_xpost WHERE wp_id = ' . $wpid; $result = $db->sql_query($sql); $content = $db->sql_fetchfield('content'); $db->sql_freeresult($result); echo $content; } else if ($xpid) { $sql = 'DELETE FROM bridgedd_xpost WHERE wp_id = ' . $xpid; $db->sql_query($sql); } $db->sql_close(); } } exit;