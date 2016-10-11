<?php

/*
	$ up6 Button  (C) 2010-2016 荆门泽优软件有限公司
	$ Id: up6.class.php, 2016-10-11
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
	}
	
	public function global_footer(){
		global $_G;
		include template('up6:css');

		return $return;
	}	
}

/* For Discuz! X1.5, X2, and newer */
class plugin_up6_forum extends plugin_up6{
	
	//tab标签按钮
	public function post_attach_btn_extra() {
		global $_G;
		include template('up6:editor');

		return $return;
	}
	
	//tab标签面板
	public function post_attach_tab_extra() {
		global $_G;
		$editorid = 'e';
		include template('up6:upload');

		return $return;
	}
	
	//
/*	public function viewthread_attach_extra_output() {
		global $postlist, $_G;
		$return = array();
		foreach ($postlist as $pid => $post) {
			foreach ($post['attachments'] as $aid => $attachment) {
				if (strpos($attachment['attachment'], 'storage:') !== false) {
					$sha1 = substr($attachment['attachment'], -40);
					$return[$aid] = $this->_output($aid, $sha1, $attachment['filename']);
					if (in_array($aid, $_G['forum_attachtags'][$pid])) {
						$postlist[$pid]['message'] .= $this->_jsOutput($aid, $return[$aid]);
						unset($return[$aid]);
					}
					if ($attachment['isimage']) {
						unset($return[$aid]);
					}
				}
			}
		}
		return $return;
	}*/
}
?>