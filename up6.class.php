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
	public function up6()
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
	
	//添加公共头
	public function global_footer(){
/*		if (!$this->appStatus) {
		   return false;
		}*/
		global $_G;
		include template('up6:css');

		return $return;
	}
}

/* For Discuz! X1.5, X2, and newer */
class plugin_up6_forum extends plugin_up6
{
	//为编辑器增加按钮
/*	public function post_image_btn_extra()
	{
		global $_G;
		include template('up6:btn');
		return $return;
	}*/
	
/*	public function post_btn_extra()
	{
		global $_G;
		include template('up6:btn');
		return $return;
	}*/
	
	//在发帖页面增加
	function post_bottom_output() 
	{
		global $_G;
		include template('up6:upload');

		return $return;
	}
}
?>