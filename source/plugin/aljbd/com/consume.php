<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$check=C::t('#aljbd#aljbd_username')->fetch($_G['uid']);
if(empty($check)&&$_G['uid']){
	C::t('#aljbd#aljbd_username')->insert(array('uid'=>$_G['uid'],'username'=>$_G['username'],'bid'=>$_GET['bid']));
}
C::t('#aljbd#aljbd')->update_view_by_bid($_GET['bid']);
$khf=C::t('#aljbd#aljbd_comment')->count_by_bid($_GET['bid']);
foreach($khf[0] as $k=>$v){
	$khf[0][$k]=intval($v);
}
$typelist=C::t('#aljbd#aljbd_type')->range();
$rlist=C::t('#aljbd#aljbd_region')->range();
$commentcount=C::t('#aljbd#aljbd_comment')->count_by_bid_upid($_GET['bid'],0,0);
$askcount=C::t('#aljbd#aljbd_comment')->count_by_bid_upid($_GET['bid'],0,1);
$commentlist=C::t('#aljbd#aljbd_comment')->fetch_all_by_bid_upid($_GET['bid'],0,0);
$asklist=C::t('#aljbd#aljbd_comment')->fetch_all_by_bid_upid($_GET['bid'],0,1);
$bd=C::t('#aljbd#aljbd')->fetch($_GET['bid']);
require_once libfile('function/discuzcode');
if(!file_exists('source/plugin/aljbd/com/intro.php')){
	$bd['intro']=discuzcode($bd['intro']);
}
$avg=C::t('#aljbd#aljbd_comment')->count_avg_by_bid($bd['id']);
$avg=intval($avg);
$adv=unserialize($bd['adv']);
$advurl=unserialize($bd['advurl']);
$navtitle = $bd['name'].'-'.$config['title'];
$metakeywords =  $config['keywords'];
$metadescription = $config['description'];
$bdlist=C::t('#aljbd#aljbd')->range();
$currpage=$_GET['page']?$_GET['page']:1;
$perpage=9;
$num=C::t('#aljbd#aljbd_consume')->count_by_uid_bid($bd['uid'],$_GET['bid']);
$allpage=ceil($num/$perpage);
$start=($currpage-1)*$perpage;
$nlist=C::t('#aljbd#aljbd_consume')->fetch_all_by_uid_bid($bd['uid'],$_GET['bid'],$start,$perpage);
$notice=C::t('#aljbd#aljbd_notice')->fetch_all_by_uid_bid($bd['uid'],$_GET['bid'],0,9);
$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=consume&bid='.$_GET['bid'], 0, 11, false, false);
include template('aljbd:consume');
?>