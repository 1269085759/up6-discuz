<?php
/*
	将附件信息添加到Discuz!X2数据表中
		1.pre_forum_attachment
		2.pre_forum_attachment_unused
	
	更新记录：
		2012-4-6
*/

/*
	返回值：
		新添加的附件ID。
*/
function InsertToAttachmentDB($uid)
{
	$inf = array("aid"=>0
				 ,"tid"=>0
				 ,"pid"=>0
				 ,"uid"=>intval($uid)
				 ,"tableid"=>127
				 ,"downloads"=>0);
	$sql = "insert into pre_forum_attachment(tid,pid,uid,tableid,downloads) values(?,?,?,?,?)";
	$db = new HttpUploaderDB();
	$con = $db->GetCon();
	if($stmt = $con->prepare($sql))
	{
		$stmt->bind_param("iiiii"
						 ,$inf["tid"]
						 ,$inf["pid"]
						 ,$inf["uid"]
						 ,$inf["tableid"]
						 ,$inf["downloads"]);
		$stmt->execute();
		$stmt->close();
		
		//获取新插入的ID
		$sql = "SELECT LAST_INSERT_ID()";
		$result = $con->query($sql);
		$row = $result->fetch_array(MYSQLI_NUM);
		$inf["aid"] = $row[0];
		$result->close();
	}
	return $inf["aid"];
}

/*
	添加到未使用列表
	参数：
		$uid 用户ID
		$aid 附件ID
		$fileName 文件名称。原始文件名称，不进行UrlEncode编码。GB2312编码
		$fileSize 文件大小。以字节为单位
		$filePath 文件在服务器中的路径 source/plugin/httpuploader/upload/201204/06/md5.rar
*/
function InsertToAttachmentDbUnused($uid,$aid,$fileName,$fileSize,$filePath)
{	
	//转换成UTF-8
	$sql = "set names utf8;";
	$db = new HttpUploaderDB();
	$con = $db->GetCon();	
	$con->query($sql);
	
	//添加到pre_forum_attachment_unused表
	$sql = "insert into pre_forum_attachment_unused(aid,uid,dateline,filename,filesize,attachment,remote,isimage,width,thumb) values(?,?,?,?,?,?,?,?,?,?)";

	if($stmt = $con->prepare($sql))
	{
		$z = 0;
		$stmt->bind_param("iiisisiiii"
						  ,$aid
						  ,$uid
						  ,time()
						  ,$fileName
						  ,$fileSize
						  ,$filePath
						  ,$z
						  ,$z
						  ,$z
						  ,$z);
		$stmt->execute();
		$stmt->close();
	}
}
?>