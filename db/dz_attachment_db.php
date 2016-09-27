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
				 ,"tableid"=>127);
	$sql = "insert into pre_forum_attachment(";
	$sql = $sql . " tid";
	$sql = $sql . ",pid";
	$sql = $sql . ",uid";
	$sql = $sql . ",tableid";
	$sql = $sql . ",downloads";
	$sql = $sql . ") values(";
	$sql = $sql . " :tid";
	$sql = $sql . ",:pid";
	$sql = $sql . ",:uid";
	$sql = $sql . ",:tableid";
	$sql = $sql . ",0)";
	
	$db = new DbHelper();
	if($cmd = $db->prepare($sql))
	{
		$cmd->bindParam(":tid",$inf["tid"]);
		$cmd->bindParam(":pid",$inf["pid"]);
		$cmd->bindParam(":uid",$uid);
		$cmd->bindParam(":tableid",$inf["tableid"]);
		$f_id = $db->execGenKey($cmd);
	}
	return $f_id;
}

/*
	添加到未使用列表
	参数：
		$uid 用户ID
		$aid 附件ID
		$nameLoc 文件名称。原始文件名称，不进行UrlEncode编码。GB2312编码
		$sizeLoc 文件大小。以字节为单位
		$pathRel 文件在服务器中的路径 source/plugin/httpuploader/upload/201204/06/md5.rar
*/
function addToAttachmentDbUnused($uid,$aid,$fileArr)
{			
	//添加到pre_forum_attachment_unused表
	$sql = "insert into pre_forum_attachment_unused(";
	$sql = $sql . " aid";
	$sql = $sql . ",uid";
	$sql = $sql . ",dateline";
	$sql = $sql . ",filename";
	$sql = $sql . ",filesize";
	$sql = $sql . ",attachment";
	$sql = $sql . ",remote";
	$sql = $sql . ",isimage";
	$sql = $sql . ",width";
	$sql = $sql . ",thumb";
	
	$sql = $sql . ") values(";
	
	$sql = $sql . " :aid";
	$sql = $sql . ",:uid";
	$sql = $sql . ",:dateline";
	$sql = $sql . ",:filename";
	$sql = $sql . ",:filesize";
	$sql = $sql . ",:attachment";
	$sql = $sql . ",:remote";
	$sql = $sql . ",:isimage";
	$sql = $sql . ",:width";
	$sql = $sql . ",:thumb)";
	
	$db = new DbHelper();
	$cmd = $db->prepare_utf8($sql);
	$z = 0;
	$cmd->bindParam(":aid",$aid);
	$cmd->bindParam(":uid",$uid);
	$cmd->bindParam(":dateline",time());
	$cmd->bindParam(":filename",$fileArr["nameLoc"]);
	$cmd->bindParam(":filesize",$fileArr["lenLoc"]);
	$cmd->bindParam(":attachment",$fileArr["pathRel"]);//dz上传路径：201609/27/160517nfyj5atjvev5ak08.png
	$cmd->bindParam(":remote",$z);
	$cmd->bindParam(":isimage",$z);
	$cmd->bindParam(":width",$z);
	$cmd->bindParam(":thumb",$z);
	$db->ExecuteNonQuery($cmd);
	
}
?>