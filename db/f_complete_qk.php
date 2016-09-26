<?php
ob_start();
header('Content-Type: text/html;charset=utf-8');
/*
	快速上传成功
	逻辑：
		1.只向Discuz!数据表中添加记录
	更新记录：
		2012-4-6 创建。
*/
require('UploaderDB.php');
require('dz_attachment_db.php');

$md5 = $_GET["md5"];
$uid = $_GET["uid"];
$fid = $_GET["fid"];
$callback = $_GET["callback"];
$ret = $callback . "(0)";
$aid = 0;

//md5和uid不能为空
if ( strlen($fid) > 0 
	&& strlen($uid) > 0)
{
	$db = new HttpUploaderDB();
	$inf = $db->GetFileInfByFid($fid);
	
	//添加以DZ数据库中
	$aid = InsertToAttachmentDB($uid);
	InsertToAttachmentDbUnused($uid,$aid,$inf["FileNameLocal"],$inf["FileLength"],$inf["FilePathRelative"]);
}

//返回查询结果
echo $callback ."(" . $aid . ")";//客户端需要aid
header('Content-Length: ' . ob_get_length());
?>