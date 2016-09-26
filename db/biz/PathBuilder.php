<?php
/*
	路径生成器
	提供文件或文件夹的存储路径
	更新记录：
		2016-04-12 创建
*/
class PathBuilder
{	

	function __construct() 
	{
	}
	
	/*
		返回上传路径：
		示例：D:\wamp\www\HttpUploader6.1\upload\
	*/
	function getRoot()
	{		
		//$path = getcwd();// D:\wamp\www\HttpUploader6.1
		$path = $this->getRootDz();		
		$path = realpath($path);//规范化路径 up6.1/upload/
		$path = PathTool::combin($path, "upload");
		if(!is_dir($path) ) mkdir($path);//创建目录
		return realpath($path);//与操作系统保持一致
	}
	
	function getRootDz()
	{
		$file_path = dirname(__FILE__);
		$file_url  = $_SERVER["PHP_SELF"];
		$file_path 	= str_replace("\\", "/", $file_path);//转换路径，支持Linux
		return realpath($file_path . "/../../../../data/attachment/forum/");
	}
	
	function genFolder($uid,&$fd){}
	function genFile($uid,$md5,$nameLoc){}
}
?>