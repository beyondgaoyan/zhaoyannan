<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(submitcheck('formhash')){
	$insertarray=array(
		'uid'=>$_G['uid'],
		'username'=>$_G['username'],
		'dateline'=>TIMESTAMP,
		'bid'=>$_GET['bid'],
		'winfo'=>$_GET['winfo']
	);
	C::t('#aljbd#aljbd_winfo')->insert($insertarray);
	showmsg(lang('plugin/aljbd','s28'),'winfo');
}else{
	include template('aljbd:winfo');
}
?>