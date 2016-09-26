<?php

/*
	$ up6 Button  (C) 2010-2016 荆门泽优软件有限公司
	$ Id: hook.class.php, 2012-04-03
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function up6() {

	if(isset($GLOBALS['_G']) && is_array($GLOBALS['_G'])) {
		$url = $GLOBALS['_G']['siteurl'];
	} else {
		$url = $GLOBALS['boardurl'];
	}

	$editorid = $GLOBALS['editorid'];

	include template('up6:button');
	return $html;

}

/* For Discuz! 7.2 */
class plugin_up6 {
	function post_bottom_output() {
		return up6();
	}
}

/* For Discuz! X1.5, X2, and newer */
class plugin_up6_forum {
	function post_bottom_output() {
		return up6();
	}
}
?>