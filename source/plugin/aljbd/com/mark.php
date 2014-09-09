<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$bd=C::t('#aljbd#aljbd')->fetch($_GET['bid']);
if(submitcheck('formhash','get')){
	if($bd['uid']!=$_G['uid']&&$_G['groupid']!=1){
		C::t('#aljbd#aljbd_point')->insert(array('uid'=>$_G['uid'],'username'=>$_G['username'],'bid'=>$_GET['bid'],'name'=>$bd['name'],'x'=>$_GET['x'],'y'=>$_GET['y'],'dateline'=>TIMESTAMP));
		echo lang('plugin/aljbd','s37');
	}else{
		C::t('#aljbd#aljbd')->update($_GET['bid'],array('x'=>$_GET['x'],'y'=>$_GET['y']));
		echo lang('plugin/aljbd','s38');
	}
}else{
	include template('aljbd:mark');
}
?>