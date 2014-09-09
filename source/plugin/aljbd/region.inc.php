<?php
/**
 *      [Liangjian] (C)2001-2099 Liangjian Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: region.inc.php liangjian $
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(!submitcheck('submit')){
	if($_GET['upid']){
		$upid_data=C::t('#aljbd#aljbd_region')->fetch($_GET['upid']);
	}
	$region=C::t('#aljbd#aljbd_region')->fetch_all_by_upid(0,10,$_GET['upid']);
	include template('aljbd:region');
}else{
	if($_GET['delete']){
		foreach($_GET['delete'] as $key=>$value){
			C::t('#aljbd#aljbd_region')->delete($value);
		}
	}else if($_GET['name']){
		foreach($_GET['name'] as $key=>$value){
			C::t('#aljbd#aljbd_region')->update($key,array('name'=>$value));
		}
	}
		foreach($_GET['newregion'] as $key=>$value){
			if($value){
				$insertarray=array('name'=>$value,'upid'=>$_GET['upid']);
				$insertid=C::t('#aljbd#aljbd_region')->insert($insertarray,true);
				DB::update('aljbd_region', array('subcatid'=>$insertid), "catid='$insertid'");
				if($_GET['upid']){
					$region=C::t('#aljbd#aljbd_region')->fetch($_GET['upid']);
					$region['subcatid']=trim(($region['subcatid'].','.$insertid),',');
					$level=$region['level']+1;
					$region['havechild']=1;
					C::t('#aljbd#aljbd_region')->update($region['catid'],$region);
					DB::update('aljbd_region', array('level'=>$level), "catid='$insertid'");
				}
				
			
		}
	}
	cpmsg(lang('plugin/aljbd','s10'),'action=plugins&operation=config&do='.$_GET['do'].'&identifier=aljbd&pmod=region&upid='.$_GET['upid']);
	
}
?>