<?php
/*
	Install Uninstall Upgrade AutoStat System Code
*/
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$sql ="ALTER TABLE ".DB::table('aljbd_goods')." ADD `subtype` INT NOT NULL ;" ;
if(DB::query($sql,'SILENT')){print('add subtype succeed!<br>');}
$sql ="ALTER TABLE ".DB::table('aljbd_goods')." ADD `type` INT NOT NULL ;" ;
if(DB::query($sql,'SILENT')){print('add type succeed!<br>');}
$sql ="ALTER TABLE ".DB::table('aljbd_goods')." ADD `sign` INT NOT NULL ;" ;
if(DB::query($sql,'SILENT')){print('add sign succeed!<br>');}
$sql ="ALTER TABLE ".DB::table('aljbd')." ADD `wurl` varchar(255) NOT NULL ;" ;
if(DB::query($sql,'SILENT')){print('add wurl succeed!<br>');}
$sql ="ALTER TABLE ".DB::table('aljbd_goods')." ADD `gwurl` varchar(255) NOT NULL ;" ;
if(DB::query($sql,'SILENT')){print('add gwurl succeed!<br>');}
//finish to put your own code
$sql ="ALTER TABLE ".DB::table('aljbd')." ADD `qq`  BIGINT NOT NULL" ;
if(DB::query($sql,'SILENT')){print('add qq succeed!<br>');}
$sql ="ALTER TABLE ".DB::table('aljbd')." ADD `displayorder` INT NOT NULL" ;
if(DB::query($sql,'SILENT')){print('add displayorder succeed!<br>');}
?>