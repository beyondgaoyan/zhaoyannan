<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(submitcheck('formhash')){
		if($_GET['bid']){
			$bd=C::t('#aljbd#aljbd')->update($_GET['bid'],array('intro'=>$_GET['intro']));
		}
		showmsg(lang('plugin/aljbd','s9'));
	}else{
		if($_GET['bid']){
			$bd=C::t('#aljbd#aljbd')->fetch($_GET['bid']);
		}
		$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_status(1,'','',$_G['uid']);
		include template('aljbd:intro');
	}

?>