<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: install.php 20324 2011-02-21 09:35:00Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF
	DROP TABLE IF EXISTS up6_files;
	DROP TABLE IF EXISTS up6_folders;
	DROP PROCEDURE IF EXISTS f_process;
	DROP PROCEDURE IF EXISTS fd_process;
	DROP PROCEDURE IF EXISTS fd_files_add_batch;
	DROP PROCEDURE IF EXISTS fd_update;
	DROP PROCEDURE IF EXISTS f_update;
EOF;
runquery($sql);

$finish = TRUE;
?>
