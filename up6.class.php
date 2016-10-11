<?php

/*
	$ up6 Button  (C) 2010-2016 荆门泽优软件有限公司
	$ Id: hook.class.php, 2012-04-03
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_up6
{
	public function plugin_up6()
	{		
		if(isset($GLOBALS['_G']) && is_array($GLOBALS['_G'])) {
			$url = $GLOBALS['_G']['siteurl'];
		} else {
			$url = $GLOBALS['boardurl'];
		}
	
		$editorid = $GLOBALS['editorid'];
	
		//include template('up6:button');
		//return $html;		
	}
	
	public function global_footer(){
		global $_G;
		//if (!$this->appStatus) {
		//  return false;
		//}
		include template('up6:css');

		return $return;
	}
	
//	public function post_bottom_output()
//	{
//		print_r("全局页脚嵌入点");
//		include template('up6:css');

//		return $return;
//	}
}

/* For Discuz! X1.5, X2, and newer */
class plugin_up6_forum extends plugin_up6{
	//function post_bottom_output() {
	//	return up6();
	//}
	
	public function post_attach_btn_extra() {
		global $_G;
		//if (!$this->appStatus) {
		//	return false;
		//}
		include template('up6:editor');

		return $return;
	}
}
?>