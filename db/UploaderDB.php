<?php
/*
	更新记录：
		2012-03-30 创建
		2012-40-11 修复UpdateComplete错误的问题
		2014-04-09 修改方法：InsertArr，增加备注字段
*/
class HttpUploaderDB
{
	var $m_DbName;		//数据库名称
	var $m_TableName;	//数据表名称
	var $m_Host;		//数据库地址
	var $m_UserName;	//管理员帐号
	var $m_UserPass;	//管理员密码
	
	function __construct() 
	{
        $this->m_DbName 	= "dz3_utf8";  //
		$this->m_TableName 	= "xdb_files";
		$this->m_Host 		= "localhost";
		$this->m_UserName 	= "root";
		$this->m_UserPass 	= "";
	}

	//获取数据库连接对象
	function GetCon()
	{
		return new mysqli($this->m_Host, $this->m_UserName, $this->m_UserPass, $this->m_DbName);
	}
	
	//根据FID获取文件详细信息
	function GetFileInfByFid($fid)
	{
		$inf = NULL;
		$sql = "select * from ".$this->m_TableName." where fid=? limit 0,1";
		$con = $this->GetCon();
		
		if( $stmt = $con->prepare($sql) )
		{
			$stmt->bind_param("i",$fid);
			$stmt->execute();
			$stmt->bind_result($sfid
							   ,$uid
							   ,$FileNameLocal
							   ,$FileNameRemote
							   ,$FilePathLocal
							   ,$FilePathRemote
							   ,$FilePathRelative
							   ,$FileMD5
							   ,$FileLength
							   ,$FileSize
							   ,$FilePos
							   ,$PostedLength
							   ,$PostedPercent
							   ,$PostComplete
							   ,$PostedTime
							   ,$IsDeleted
							   ,$remark);
			if($stmt->fetch())
			{
				$inf = array("fid"=>$fid
							 ,"uid"=>$uid
							 ,"FileNameLocal"=>$FileNameLocal
							 ,"FileNameRemote"=>$FileNameRemote
							 ,"FilePathLocal"=>$FilePathLocal
							 ,"FilePathRemote"=>$FilePathRemote
							 ,"FilePathRelative"=>$FilePathRelative
							 ,"FileMD5"=>$FileMD5
							 ,"FileLength"=>$FileLength
							 ,"FilePos"=>$FilePos
							 ,"PostedLength"=>$PostedLength
							 ,"PostedPercent"=>$PostedPercent
							 ,"PostComplete"=>$PostComplete
							 ,"PostedTime"=>$PostedTime
							 ,"IsDeleted"=>$IsDeleted
							 ,"f_remark"=>$remark);
			}
			$stmt->close();
		}
		$con->close();
		return $inf;
	}
	
	/*
		根据文件MD5获取信息
	*/
	function GetFileInfByMD5($md5)
	{		
		//设置字符集，防止中文在数据库中出现乱码
		$sql = "set names utf8";		
		$con = $this->GetCon();
		$con->query($sql);
		
		$inf = NULL;
		$sql = "select * from ".$this->m_TableName." where FileMD5=? order by PostedPercent DESC limit 0,1";
		//$con = $this->GetCon();
		if( $stmt = $con->prepare($sql) )
		{
			$stmt->bind_param("s",$md5);
			$stmt->execute();
			//如果选择所有字段，绑定结果必须绑定所有字段，否则MYSQL将报错。
			$stmt->bind_result($fid
							   ,$uid
							   ,$FileNameLocal
							   ,$FileNameRemote
							   ,$FilePathLocal
							   ,$FilePathRemote
							   ,$FilePathRelative
							   ,$FileMD5
							   ,$FileLength
							   ,$FileSize
							   ,$FilePos
							   ,$PostedLength
							   ,$PostedPercent
							   ,$PostComplete
							   ,$PostedTime
							   ,$IsDeleted
							   ,$f_remark);
			if($stmt->fetch())
			{
				$inf = array("fid"=>$fid
							 ,"uid"=>$uid
							 ,"FileNameLocal"=>$FileNameLocal
							 ,"FileNameRemote"=>$FileNameRemote
							 ,"FilePathLocal"=>$FilePathLocal
							 ,"FilePathRemote"=>$FilePathRemote
							 ,"FilePathRelative"=>$FilePathRelative
							 ,"FileMD5"=>$FileMD5
							 ,"FileLength"=>$FileLength
							 ,"FilePos"=>$FilePos
							 ,"PostedLength"=>$PostedLength
							 ,"PostedPercent"=>$PostedPercent
							 ,"PostComplete"=>$PostComplete
							 ,"PostedTime"=>$PostedTime
							 ,"IsDeleted"=>$IsDeleted
							 ,"f_remark"=>$f_remark);
			}
			$stmt->close();
		}
		$con->close();
		return $inf;
	}
	
	/*
		检查文件MD5
		参数：
			$uid 用户ID
			$md5 文件MD5
		返回值：
			$ret ["exist"
				  ,"fid"
				  ,"uid"
				  ,"FileNameLocal"
				  ,"FileNameRemote"
				  ,"FilePathLocal"
				  ,"FilePathRemote"
				  ,"FileLength"
				  ,"FileMD5"
				  ,"PostedLength"
				  ,"PostedPercent"
				  ,"PostComplete"
				  ,"IsDeleted"]
	*/
	function ExistFileMD5($uid,$md5)
	{
		$inf = array("exist"=>0
						 ,"fid"=>0
						 ,"uid"=>$uid
						 ,"FileNameLocal"=>""
						 ,"FileNameRemote"=>""
						 ,"FilePathLocal"=>""
						 ,"FilePathRemote"=>""
						 ,"FilePathRelative"=>""
						 ,"FileLength"=>0
						 ,"FileSize"=>""
						 ,"FileMD5"=>$md5
						 ,"PostedLength"=>0
						 ,"PostedPercent"=>"0%"
						 ,"PostComplete"=>0
						 ,"IsDeleted"=>0
						 ,"f_remark"=>"");
		//注意：这里取所有字段，下面绑定字段必须将所有字段都绑定，否则会报错。
		$sql = "select * from ".$this->m_TableName." where FileMD5=? order by PostedPercent DESC limit 0,1";
		$con = $this->GetCon();
		
		if ($stmt = $con->prepare($sql)) 
		{
			$stmt->bind_param("s",$md5);
			$stmt->execute();
			$stmt->bind_result($fid
							   ,$uid
							   ,$FileNameLocal
							   ,$FileNameRemote
							   ,$FilePathLocal
							   ,$FilePathRemote
							   ,$FilePathRelative
							   ,$FileMD5
							   ,$FileLength
							   ,$FileSize
							   ,$FilePos
							   ,$PostedLength
							   ,$PostedPercent
							   ,$PostComplete
							   ,$PostedTime
							   ,$IsDeleted
							   ,$f_remark);
			if($stmt->fetch())
			{
				$inf["exist"]			= 1;
				$inf["fid"] 			= $fid;
				$inf["FileNameLocal"] 	= $FileNameLocal;
				$inf["FileNameRemote"] 	= $FileNameRemote;
				$inf["FilePathLocal"] 	= $FilePathLocal;
				$inf["FilePathRemote"] 	= $FilePathRemote;
				$inf["FilePathRelative"]= $FilePathRelative;
				$inf["FileLength"] 		= $FileLength;
				$inf["FileSize"] 		= $FileSize;
				$inf["PostedLength"] 	= $PostedLength;
				$inf["PostedPercent"] 	= $PostedPercent;
				$inf["PostComplete"] 	= $PostComplete;
				$inf["IsDeleted"] 		= $IsDeleted;
				$inf["f_remark"]		= $f_remark;
			}
			$stmt->close();
		}
	
		/* 关闭连接 */
		$con->close();
		return $inf;
	}
	
	/*
		向数据库添加一条记录
		参数：
			$uid 用户ID
			$fileNameLocal	文件在本地电脑中的名称。例：QQ.exe，客户端通过encodeURIComponent编辑
			$fileNameRemote	文件在服务器中的名称。一般情况下指用户重新设置的文件名称。QQ.exe
			$filePathLocal	文件在本地电脑中的完整路径。示例：D:\Soft\QQ.exe
			$filePathRemote	文件在服务器中的完整路径。示例：F:\ftp\user1\QQ2012.exe
			$fileLength		文件总长度。以字节为单位
			$fileMD5		文件MD5
	*/
	function Insert($uid,$fileNameLocal,$fileNameRemote,$filePathLocal,$filePathRemote,$fileLength,$fileMD5)
	{
		$ret = 0;
		$fields = "uid,fileNameLocal,fileNameRemote,filePathLocal,filePathRemote,fileLength,fileMD5";
		$sql = "insert into ".$this->m_TableName."(".$fields.") ";
		$sql = $sql . "values(?,?,?,?,?,?,?)";
		$con = $this->GetCon();
		
		if ($stmt = $con->prepare($sql)) 
		{	
			$stmt->bind_param("issssss"
							  ,$uid
							  ,$fileNameLocal
							  ,$fileNameRemote
							  ,$filePathLocal
							  ,$filePathRemote
							  ,$fileLength
							  ,$fileMD5);
			if(!$stmt->execute())
			{
				printf("Errormessage: %s\n", $con->error);
			}
	
			//有数据
			$ret = $stmt->affected_rows;
			$stmt->close();
		}
		$con->close();
		return $ret;
	}
	
	/*
		向数据库添加一条记录
		一般在ajax_create.php中调用
		参数：
			$arr
				uid
				FileNameLocal
				FileNameRemote
				FilePathLocal
				FilePathRemote
				FileLength
				FileMD5
				PostedLength
				PostedPercent
				PostComplete
				IsDeleted
				f_remark

		返回值：
			新添数据的ID
	*/
	function InsertArr(&$arr)
	{
		$fid = 0;
		//设置字符集，防止中文在数据库中出现乱码
		$sql = "set names utf8";		
		$con = $this->GetCon();
		$con->query($sql);
		
		$fields = "uid";
		$fields .= ",FileNameLocal";
		$fields .= ",FileNameRemote";
		$fields .= ",FilePathLocal";
		$fields .= ",FilePathRemote";
		$fields .= ",FilePathRelative";
		$fields .= ",FileLength";
		$fields .= ",PostedPercent";
		$fields .= ",PostComplete";
		$fields .= ",FileSize";
		$fields .= ",FileMD5";
		$fields .= ",f_remark";
		
		$sql = "insert into ".$this->m_TableName."(".$fields.") ";
		$sql = $sql . "values(";
		
		$sql .= " ?";//uid
		$sql .= ",?";//FileNameLocal
		$sql .= ",?";//FileNameRemote
		$sql .= ",?";//FilePathLocal
		$sql .= ",?";//FilePathRemote
		$sql .= ",?";//FilePathRelative
		$sql .= ",?";//FileLength
		$sql .= ",?";//PostedPercent
		$sql .= ",?";//PostComplete
		$sql .= ",?";//FileSize
		$sql .= ",?";//FileMD5
		$sql .= ",?";//f_remark
		$sql .= ")";
		
		//开始事务
		$con->autocommit(false);
		if ($stmt = $con->prepare($sql)) 
		{	
			$stmt->bind_param("isssssisisss"
							  ,$arr["uid"]
							  ,$arr["FileNameLocal"]
							  ,$arr["FileNameRemote"]
							  ,$arr["FilePathLocal"]
							  ,$arr["FilePathRemote"]
							  ,$arr["FilePathRelative"]
							  ,$arr["FileLength"]
							  ,$arr["PostedPercent"]
							  ,$arr["PostComplete"]
							  ,$arr["FileSize"]
							  ,$arr["FileMD5"]
							  ,$arr["f_remark"]
							  );
			if(!$stmt->execute())
			{
				printf("Errormessage: %s\n", $con->error);
			}
			//获取新插入数据的ID
			$sql = "SELECT LAST_INSERT_ID()";
			$result = $con->query($sql);
			$con->commit();//提交事务
			
			$row = $result->fetch_array(MYSQLI_NUM);
			$fid = $row[0];
			$result->close();
			$stmt->close();
		}
		$con->close();
		return $fid;
	}
	
	/*
		更新进度。
		参数：
			$uid			用户ID
			$fid			文件ID
			$filePos		文件续传位置
			$postedLength	已上传长度
			$postedPercent	已上传百分比
	*/
	function UpdateProgress($uid,$fid,$filePos,$postedLength,$postedPercent)
	{
		$ret = 0;
		$sql = "update ".$this->m_TableName." set FilePos=?,PostedLength=?,PostedPercent=? where uid=? and fid=?";
		$con = $this->GetCon();
		
		if($stmt = $con->prepare($sql))
		{
			$stmt->bind_param("iisii"
							  ,$filePos
							  ,$postedLength
							  ,$postedPercent
							  ,$uid
							  ,$fid);
			if(!$stmt->execute())
			{
				printf("Error Message: %s\n",$con->error);
			}
			else
			{
				$ret = $stmt->affected_rows;
			}
			$stmt->close();
		}
		$con->close();
		return $ret;
	}
	
	/*
		更新完成。将所有相同MD5文件进度都设为100%
		参数：
			$md5
	*/
	function UpdateComplete($md5)
	{
		$ret = 0;
		//设置字符集
		$con = $this->GetCon();
		$sql = "set names utf8";
		$con->query($sql);
		
		$sql = "update ".$this->m_TableName." set PostedLength=FileLength,PostedPercent='100%',PostComplete=1 where FileMD5=?";		
		
		if( $stmt = $con->prepare($sql) )
		{
			$stmt->bind_param("s",$md5);
			if(!$stmt->execute())
			{
				printf("Error Message: %s\n",$con->error);
			}
			else
			{
				$ret = $stmt->affected_rows;
			}
			$stmt->close();
		}
		$con->close();
		return $ret;
	}
	
	/*
		删除一条记录，只在数据表中标识删除状态。
		参数：
			$uid 用户ID
			$fid 文件ID
	*/
	function Remove($uid,$fid)
	{
		$ret = 0;
		$sql = "update ".$this->m_TableName." set IsDeleted=true where uid=? and fid=?";
		$con = $this->GetCon();
		
		if( $stmt = $con->prepare($sql) )
		{
			$stmt->bind_param("ii"
							  ,$uid
							  ,$fid);
			if(!$stmt->execute())
			{
				printf("Error Message: %s\n",$con->error);
			}
			else
			{
				$ret = $stmt->affected_rows;
			}
			$stmt->close();
		}
		$con->close();
		return $ret;
	}
}
?>