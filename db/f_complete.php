<?php
ob_start();
header('Content-Type: text/html;charset=utf-8');
/*
	此文件只负责将数据表中文件上传进度更新为100%
		向数据库添加新记录在 ajax_create_fid.php 文件中处理
	如果服务器不存在此文件，则添加一条记录，百分比为100%
	如果服务器已存在相同文件，则将文件上传百分比更新为100%
*/
require('DbHelper.php');
require('DBFile.php');
require('DBFolder.php');
require('dz_attachment_db.php');

$md5 		= $_GET["md5"];
$uid 		= $_GET["uid"];
$fid 		= $_GET["idSvr"];
$fileSvr	= $_GET["fileInf"];//json字段,url编码
$quick		= $_GET["quick"];//快速上传？
$fileSvr	= urldecode($fileSvr);
$fileArr	= json_decode($fileSvr,true);//解析成数组
$fd_idSvr	= "";
if(!empty($fd_idSvr)) $fd_idSvr = $_GET["fd_idSvr"];
$cbk 		= $_GET["callback"];
$ret 		= $cbk . "({\"aid\":0})";

//md5和uid不能为空
if ( strlen($md5) > 0 )
{
	//不是快速上传
	if($quick=="0")
	{
		$db = new DBFile();
		$db->Complete($md5);
	}
	
	//添加到dz数据表
	$aid = InsertToAttachmentDB($uid);
	//添加到dz附件表(未使用)
	addToAttachmentDbUnused($uid,$aid,$fileArr);
	$ret = $cbk . "({\"aid\":$aid})";
}

//更新文件夹已上传文件数
if(strlen($fd_idSvr)>0)
{
	DBFolder::child_complete(intval($fd_idSvr));
}

//返回查询结果
echo $ret;
header('Content-Length: ' . ob_get_length());
?>