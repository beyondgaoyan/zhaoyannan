<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(submitcheck('formhash')){
	C::t('#aljbd#aljbd')->update($_GET['bid'],array('gg'=>$_GET['gg']));
	showmsg(lang('plugin/aljbd','s24'),'edit');
}
$bd=C::t('#aljbd#aljbd')->fetch($_GET['bid']);
include template('aljbd:gg');
?>