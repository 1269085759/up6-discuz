DROP TABLE IF EXISTS up6_files;
DROP PROCEDURE IF EXISTS f_process;
DROP PROCEDURE IF EXISTS fd_process;
DROP TABLE IF EXISTS up6_folders;
DROP PROCEDURE IF EXISTS fd_files_add_batch;
DROP PROCEDURE IF EXISTS fd_update;
DROP PROCEDURE IF EXISTS f_update;

CREATE TABLE IF NOT EXISTS `up6_files` (
  `f_id` 				int(11) NOT NULL auto_increment,
  `f_pid` 				int(11) default '0',		
  `f_pidRoot` 			int(11) default '0',		
  `f_fdTask` 			tinyint(1) default '0',		
  `f_fdID` 				int(11) default '0',		
  `f_fdChild` 			tinyint(1) default '0',		
  `f_uid` 				int(11) default '0',
  `f_nameLoc` 			varchar(255) default '',	
  `f_nameSvr` 			varchar(255) default '',	
  `f_pathLoc` 			varchar(255) default '',	
  `f_pathSvr` 			varchar(255) default '',	
  `f_pathRel` 			varchar(255) default '',
  `f_md5` 				varchar(40) default '',	
  `f_lenLoc` 			bigint(19) default '0',		
  `f_sizeLoc` 			varchar(10) default '0',	
  `f_pos` 				bigint(19) default '0',		
  `f_lenSvr` 			bigint(19) default '0',		
  `f_perSvr` 			varchar(7) default '0%',	
  `f_complete` 			tinyint(1) default '0',		
  `f_time` 				timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `f_deleted` 			tinyint(1) default '0',
  PRIMARY KEY  (`f_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*--更新文件进度*/
CREATE PROCEDURE f_process(
in `posSvr` bigint(19),
in `lenSvr` bigint(19),
in `perSvr` varchar(6),
in `uidSvr` int,
in `fidSvr` int,
in `complete` tinyint)
update up6_files set f_pos=posSvr,f_lenSvr=lenSvr,f_perSvr=perSvr,f_complete=complete where f_uid=uidSvr and f_id=fidSvr;

/*更新文件夹进度*/
CREATE PROCEDURE fd_process(
in uidSvr int,
in fd_idSvr int,
in fd_lenSvr bigint(19),
in perSvr varchar(6))
update up6_files set f_lenSvr=fd_lenSvr ,f_perSvr=perSvr  where f_uid=uidSvr and f_id=fd_idSvr;

/*文件夹表*/
CREATE TABLE IF NOT EXISTS up6_folders (
  `fd_id` 				int(11) NOT NULL auto_increment,
  `fd_name` 			varchar(50) default '',
  `fd_pid` 				int(11) default '0',
  `fd_uid` 				int(11) default '0',
  `fd_length` 			bigint(19) default '0',
  `fd_size` 			varchar(50) default '0',
  `fd_pathLoc` 			varchar(255) default '',
  `fd_pathSvr` 			varchar(255) default '',
  `fd_folders` 			int(11) default '0',
  `fd_files` 			int(11) default '0',
  `fd_filesComplete` 	int(11) default '0',
  `fd_complete` 		tinyint(1) default '0',
  `fd_delete` 			tinyint(1) default '0',
  `fd_json` 			varchar(20000) default '',
  `timeUpload` 			timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `fd_pidRoot` 			int(11) default '0',
  `fd_pathRel` 			varchar(255) default '',
  PRIMARY KEY  (`fd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*文件夹初始化*/
DELIMITER $$
CREATE PROCEDURE fd_files_add_batch(
 in fCount int	
,in fdCount int)
begin
	declare ids_f text default '0';
	declare ids_fd text default '0';
	declare i int;
	set i = 0;	
	
	while(i<fdCount) do	
		insert into up6_folders(fd_pid) values(0);	
		set ids_fd = concat( ids_fd,",",last_insert_id() );
		set i = i + 1;
	end while;
	set ids_fd = substring(ids_fd,3);
	
	set i = 0;
	while(i<fCount) do	
		insert into up6_files(f_pid) values(0);	
		set ids_f = concat( ids_f,",",last_insert_id() );
		set i = i + 1;
	end while;	
	set ids_f = substring(ids_f,3);
	
	select ids_f,ids_fd;
end$$
DELIMITER ;
	
/*文件夹更新*/
DELIMITER $$
CREATE PROCEDURE fd_update(
 in _name			varchar(50)
,in _pid			int
,in _uid			int
,in _length			bigint
,in _size			varchar(50)
,in _pathLoc		varchar(255)
,in _pathSvr		varchar(255)
,in _folders		int
,in _files			int
,in _filesComplete	int
,in _complete		tinyint
,in _delete			tinyint
,in _pidRoot		int
,in _pathRel		varchar(255)
,in _id				int
)
begin
	update up6_folders set
	 fd_name			= _name
	,fd_pid				= _pid
	,fd_uid				= _uid
	,fd_length			= _length
	,fd_size			= _size
	,fd_pathLoc			= _pathLoc
	,fd_pathSvr			= _pathSvr
	,fd_folders			= _folders
	,fd_files			= _files
	,fd_filesComplete	= _filesComplete
	,fd_complete		= _complete
	,fd_delete			= _delete
	,fd_pidRoot			= _pidRoot
	,fd_pathRel			= _pathRel
	where 
	fd_id = _id;
end$$
DELIMITER ;

/*文件更新*/
DELIMITER $$
CREATE PROCEDURE f_update(
 in _pid		int
,in _pidRoot	int
,in _fdTask		tinyint
,in _fdID		int
,in _fdChild	tinyint
,in _uid		int
,in _nameLoc	varchar(255)
,in _nameSvr	varchar(255)
,in _pathLoc	varchar(255)
,in _pathSvr	varchar(255)
,in _md5		varchar(40)
,in _lenLoc		bigint
,in _lenSvr		bigint
,in _perSvr		varchar(7)
,in _sizeLoc	varchar(10)
,in _complete	tinyint
,in _id			int
)
begin
	update up6_files set
	 f_pid		= _pid
	,f_pidRoot	= _pidRoot
	,f_fdTask	= _fdTask
	,f_fdID		= _fdID
	,f_fdChild	= _fdChild
	,f_uid		= _uid
	,f_nameLoc	= _nameLoc
	,f_nameSvr	= _nameSvr
	,f_pathLoc	= _pathLoc
	,f_pathSvr	= _pathSvr
	,f_md5		= _md5
	,f_lenLoc	= _lenLoc
	,f_lenSvr	= _lenSvr
	,f_perSvr	= _perSvr
	,f_sizeLoc	= _sizeLoc
	,f_complete	= _complete
	where f_id = _id;
end$$
DELIMITER ;
select f_id from up6_files limit 0,1;