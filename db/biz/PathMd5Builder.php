<?php
class PathMd5Builder extends PathBuilder
{
	var $pathRel;
	
	function genFolder($uid,&$fd)
	{
	}
	
	function genFile($uid,$md5,$nameLoc)
	{
		date_default_timezone_set("PRC");//设置北京时区
		$path = $this->getRoot();
		$path = PathTool::combin($path, date("Y"));
		$path = PathTool::combin($path, date("m"));
		$path = PathTool::combin($path, date("d"));
		
		//
		$this->pathRel = date("Y");
		$this->pathRel = PathTool::combin($this->pathRel,date("m"));
		$this->pathRel = PathTool::combin($this->pathRel,date("d"));
		$this->pathRel = PathTool::combin($this->pathRel,$md5);
		$this->pathRel = $this->pathRel . ".attach";
		
		if(!is_dir($path)) mkdir($path,0777,true);
		$path = realpath($path);//规范化路径
		$path = PathTool::combin($path, $md5);
		$path .= ".";
		$path .= "attach";//增强安全性，防止用户上传php脚本
		return $path;
	}
	
	function genRelPath()
	{
		return $this->pathRel;
	}
}
?>