<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$_G['setting']['switchwidthauto']=0;
$_G['setting']['allowwidthauto']=1;
$config=$_G['cache']['plugin']['aljbd'];

if($_GET['act']=='goods'){
	$navtitle = lang('plugin/aljbd','s57').$config['title'];

	$metakeywords =  $config['keywords'];
	$metadescription = $config['description'];
	
	$typecount=C::t('#aljbd#aljbd_goods')->count_by_type();
	//debug($typecount);
	foreach($typecount as $tc){
		$tcs[$tc['type']]=$tc['num'];
	} 
	//debug($tcs);
	if($_GET['type']){
		$subtypecount=C::t('#aljbd#aljbd_goods')->count_by_type($_GET['type']);
	}
	$aljbd=C::t('#aljbd#aljbd')->range();
	//debug($aljbd);
	$config=$_G['cache']['plugin']['aljbd'];
	$typelist=C::t('#aljbd#aljbd_type_goods')->range();
	$tlist=C::t('#aljbd#aljbd_type_goods')->fetch_all_by_upid(0);
	//$rlist=C::t('#aljbd#aljbd_region')->fetch_all_by_upid(0);
	$currpage=$_GET['page']?$_GET['page']:1;
	$perpage=$config['page'];
	if(submitcheck('submit')){
		
		$search=$_GET['kw'];
	
	}
	$num=C::t('#aljbd#aljbd_goods')->count_by_status('','',$_GET['type'],$_GET['subtype'],$_GET['region'],$_GET['subregion'],$search);
	
	
	$allpage=ceil($num/$perpage);
	$start=($currpage-1)*$perpage;
	$recommendlist=C::t('#aljbd#aljbd')->fetch_all_by_recommend(1,0,10);
	$recommendlist_goods=C::t('#aljbd#aljbd_goods')->fetch_all_by_recommend(1,0,10);
	if($config['isrewrite']){
		if($_GET['order']=='1'){
			$_GET['order']='view';
		}else if($_GET['order']=='2'){
			$_GET['order']='price1';
		}else{
			$_GET['order']='';
		}
		if($_GET['view']=='3'){
			$_GET['view']="pic";
		}else if($_GET['view']=='4'){
			$_GET['view']="list";
		}else{
			$_GET['view']='';
		}
	}
	
	$bdlist=C::t('#aljbd#aljbd_goods')->fetch_all_by_status('',$start,$perpage,'',$_GET['type'],$_GET['subtype'],$_GET['region'],$_GET['subregion'],$_GET['order'],$search);
	//
	$notice=C::t('#aljbd#aljbd_notice')->fetch_all_by_uid_bid('','',0,9);
	foreach($bdlist as $k=>$v){
		$bdlist[$k]['c']=C::t('#aljbd#aljbd_comment')->fetch_by_bid($v['id']);
		$bdlist[$k]['q']=str_replace('{qq}',$v['qq'],$config['qq']);
	}//debug($bdlist);
	$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=goods&type='.$_GET['type'].'&subtype='.$_GET['subtype'].'&order='.$_GET['order'].'&kw='.$_GET['kw'].'&view='.$_GET['view'], 0, 11, false, false);
	
	include template('aljbd:list_goods');
}else if($_GET['act']=='attend'){
	if(submitcheck('submit')){
		$bd=C::t('#aljbd#aljbd')->fetch_by_uid($_G['uid']);
		/*if($bd&&$_G['groupid']!=1){
			showerror(lang('plugin/aljbd','s18'));
		}*/
		if(!$_GET['name']){
			showerror(lang('plugin/aljbd','aljbd_3'));
		}
		if(!$_GET['type']){
			showerror(lang('plugin/aljbd','aljbd_4'));
		}
		if(!$_GET['region']){
			showerror(lang('plugin/aljbd','aljbd_5'));
		}
		if($_FILES['logo']['tmp_name']) {
			$picname = $_FILES['logo']['name'];
			$picsize = $_FILES['logo']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s19'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$logo = "source/plugin/aljbd/images/logo/". $pics;
				if(@copy($_FILES['logo']['tmp_name'], $logo)||@move_uploaded_file($_FILES['logo']['tmp_name'], $logo)){
					@unlink($_FILES['logo']['tmp_name']);
				}
			}
		}
		
		if($config['islogo']){
			if(!$_FILES['logo']['tmp_name']) {
				showerror(lang('plugin/aljbd','logo'));
			}
		}
		if(in_array($_G['groupid'],unserialize($config['mgroups']))){
			$status=1;
		}
		$insertarray=array(
			'username'=>$_G['username'],
			'uid'=>$_G['uid'],
			'name'=>$_GET['name'],
			'tel'=>$_GET['tel'],
			'logo'=>$logo,
			'addr'=>$_GET['addr'],
			'intro'=>$_GET['intro'],
			'other'=>$_GET['other'],
			'type'=>$_GET['type'],
			'subtype'=>$_GET['subtype'],
			'region'=>$_GET['region'],
			'subregion'=>$_GET['subregion'],
			'qq'=>$_GET['qq'],
			'wurl'=>$_GET['wurl'],
			'status'=>$status,
			'dateline'=>TIMESTAMP
		);
		C::t('#aljbd#aljbd')->insert($insertarray);
		if(in_array($_G['groupid'],unserialize($config['mgroups']))){
			showmsg(lang('plugin/aljbd','mgroups'));
		}else{
			showmsg(lang('plugin/aljbd','s20'));
		}
	}else{
		if(empty($_G['uid'])){
			showmessage(lang('plugin/aljbd','s21'));
		}
		$typelist=C::t('#aljbd#aljbd_type')->fetch_all_by_upid(0);
		$rlist=C::t('#aljbd#aljbd_region')->fetch_all_by_upid();
		include template('aljbd:adddp');
	}
}else if($_GET['act']=='glmsg'){
	$num=C::t('#aljbd#aljbd')->count_by_status(1,$_GET['uid']);
	$currpage=$_GET['page']?$_GET['page']:1;
	$perpage=$config['page'];
	$num=C::t('#aljbd#aljbd')->count_by_status(1,$_GET['uid']);
	$start=($currpage-1)*$perpage;
	$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_status(1,$start,$perpage,$_GET['uid']);
	$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=glmsg&username='.$_GET['username'], 0, 11, false, false);
	include template('aljbd:glmsg');
}else if($_GET['act']=='member'){
	if(empty($_G['uid'])){
		showmessage(lang('plugin/aljbd','s22'));
	}
	$typelist=C::t('#aljbd#aljbd_type')->range();
	$rlist=C::t('#aljbd#aljbd_region')->range();
	$currpage=$_GET['page']?$_GET['page']:1;
	$perpage=$config['page'];
	$num=C::t('#aljbd#aljbd')->count_by_status(1,$_G['uid']);
	$start=($currpage-1)*$perpage;
	$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_status(1,$start,$perpage,$_G['uid']);
	$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=member', 0, 11, false, false);
	include template('aljbd:member');
}else if($_GET['act']=='yes'){
	if(empty($_G['uid'])){
		showmessage(lang('plugin/aljbd','s23'));
	}
	$typelist=C::t('#aljbd#aljbd_type')->range();
	$rlist=C::t('#aljbd#aljbd_region')->range();
	$currpage=$_GET['page']?$_GET['page']:1;
	$perpage=$config['page'];
	$num=C::t('#aljbd#aljbd')->count_by_status(0,$_G['uid']);
	$start=($currpage-1)*$perpage;
	$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_status(0,$start,$perpage,$_G['uid']);
	$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=member', 0, 11, false, false);
	include template('aljbd:member');
}else if($_GET['act']=='gg'){
	if(file_exists('source/plugin/aljbd/com/gg.php')){
		include_once 'source/plugin/aljbd/com/gg.php';
	}
}else if($_GET['act']=='intro'){
	if(file_exists('source/plugin/aljbd/com/intro.php')){
		include_once 'source/plugin/aljbd/com/intro.php';
	}
}else if($_GET['act']=='adv'){
	if(submitcheck('formhash')){
		for($i=1;$i<=3;$i++){
			if($_FILES['adv']['tmp_name'][$i]) {
				$picname = $_FILES['adv']['name'][$i];
				$picsize = $_FILES['adv']['size'][$i];
			
				if ($picname != "") {
					$type = strstr($picname, '.');
					if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
						showerror(lang('plugin/aljbd','s25'));
					}
					$rand = rand(100, 999);
					$pics = date("YmdHis") . $rand . $type;
					$dir='source/plugin/aljbd/images/adv/';
					if(!is_dir($dir)) {
						@mkdir($dir, 0777);
					}
					$adv[$i] = $dir. $pics;
					if(@copy($_FILES['adv']['tmp_name'][$i], $adv[$i])||@move_uploaded_file($_FILES['adv']['tmp_name'][$i], $adv[$i])){
						@unlink($_FILES['adv']['tmp_name'][$i]);
					}
				}
			}
		}
		$bd=C::t('#aljbd#aljbd')->fetch($_GET['bid']);
		$badv=unserialize($bd['adv']);
		$badvurl=unserialize($bd['advurl']);
		if($_GET['advdelete']){
			foreach($_GET['advdelete'] as $k=>$d){
				unset($badv[$k]);
			}
		}
		if(empty($adv)){
			$adv=$badv;
		}
		C::t('#aljbd#aljbd')->update($_GET['bid'],array('advurl'=>serialize($_GET['advurl']),'adv'=>serialize($adv)));
		showmsg(lang('plugin/aljbd','s27'),'edit');
	}
	$bd=C::t('#aljbd#aljbd')->fetch($_GET['bid']);
	$adv=unserialize($bd['adv']);
	$advurl=unserialize($bd['advurl']);
	include template('aljbd:adv');
}else if($_GET['act']=='winfo'){
	if(file_exists('source/plugin/aljbd/com/winfo.php')){
		include_once 'source/plugin/aljbd/com/winfo.php';
	}
}else if($_GET['act']=='winfolist'){
	$currpage=$_GET['page']?$_GET['page']:1;
	$perpage=1;
	$start=($currpage-1)*$perpage;
	$num=C::t('#aljbd#aljbd_winfo')->count_by_bid($_GET['bid']);
	$winfolist=C::t('#aljbd#aljbd_winfo')->fetch_all_by_bid($_GET['bid'],$start,$perpage);
	$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=winfolist&bid='.$_GET['bid'], 0, 11, false, false);
	include template('aljbd:winfolist');
}else if($_GET['act']=='commentlist'){
	$currpage=$_GET['page']?$_GET['page']:1;
	$perpage=10;
	$start=($currpage-1)*$perpage;
	$num=C::t('#aljbd#aljbd_comment')->count_by_bid_all($_GET['bid']);
	$commentlist=C::t('#aljbd#aljbd_comment')->fetch_all_by_bid_page($_GET['bid'],$start,$perpage);
	$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=commentlist&bid='.$_GET['bid'], 0, 11, false, false);
	include template('aljbd:commentlist');
}else if($_GET['act']=='deletecomment'){
	C::t('#aljbd#aljbd_comment')->delete($_GET['cid']);
	$currpage=$_GET['page']?$_GET['page']:1;
	$perpage=10;
	$num=C::t('#aljbd#aljbd_comment')->count_by_bid_all($_GET['bid']);
	$commentlist=C::t('#aljbd#aljbd_comment')->fetch_all_by_bid_page($_GET['bid'],$start,$perpage);
	$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=commentlist&bid='.$_GET['bid'], 0, 11, false, false);
	include template('aljbd:commentlist');
}else if($_GET['act']=='edit'){
	if(submitcheck('submit')){
		if($_FILES['logo']['tmp_name']) {
			$picname = $_FILES['logo']['name'];
			$picsize = $_FILES['logo']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s29'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$logo = "source/plugin/aljbd/images/logo/". $pics;
				if(@copy($_FILES['logo']['tmp_name'], $logo)||@move_uploaded_file($_FILES['logo']['tmp_name'], $logo)){
					@unlink($_FILES['logo']['tmp_name']);
				}
			}
		}
		$updatearray=array(
			'name'=>$_GET['name'],
			'tel'=>$_GET['tel'],
			'addr'=>$_GET['addr'],
			'intro'=>$_GET['intro'],
			'qq'=>$_GET['qq'],
			'wurl'=>$_GET['wurl'],
			'other'=>$_GET['other'],
			'type'=>$_GET['type'],
			'subtype'=>$_GET['subtype'],
			'region'=>$_GET['region'],
			'subregion'=>$_GET['subregion'],
		);
		if($logo){
			$updatearray['logo']=$logo;
		}
		C::t('#aljbd#aljbd')->update($_GET['bid'],$updatearray);
		showmsg(lang('plugin/aljbd','s30'));
	}else{
		$bd=C::t('#aljbd#aljbd')->fetch($_GET['bid']);
		$typelist=C::t('#aljbd#aljbd_type')->fetch_all_by_upid(0);
		$rlist=C::t('#aljbd#aljbd_region')->fetch_all_by_upid();
		include template('aljbd:adddp');
	}
}else if($_GET['act']=='gettype'){
	if($_GET['upid']){
		if($_GET['type']=='goods'){
			$typelist=C::t('#aljbd#aljbd_type_goods')->fetch_all_by_upid($_GET['upid']);
		}else if($_GET['type']=='notice'){
			$typelist=C::t('#aljbd#aljbd_type_notice')->fetch_all_by_upid($_GET['upid']);
		}else if($_GET['type']=='quan'){
			$typelist=C::t('#aljbd#aljbd_type_quan')->fetch_all_by_upid($_GET['upid']);
		}else{
			$typelist=C::t('#aljbd#aljbd_type')->fetch_all_by_upid($_GET['upid']);
		}
	}
	include template('aljbd:gettype');
}else if($_GET['act']=='getregion'){
	if($_GET['upid']){
		$rlist=C::t('#aljbd#aljbd_region')->fetch_all_by_upid('','',$_GET['upid']);
	}
	include template('aljbd:getregion');
}else if($_GET['act']=='view'){
	$check=C::t('#aljbd#aljbd_username')->fetch_by_uid_bid($_G['uid'],$_GET['bid']);
	//$check=C::t('#aljbd#aljbd_user')->fetch($_G['uid']);
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
	$commentlist=dhtmlspecialchars($commentlist);
	$asklist=C::t('#aljbd#aljbd_comment')->fetch_all_by_bid_upid($_GET['bid'],0,1);
	$asklist=dhtmlspecialchars($asklist);
	$bd=C::t('#aljbd#aljbd')->fetch($_GET['bid']);
	$tell=str_replace('{qq}',$bd['qq'],str_replace('{tel}',$bd['tel'],$config['tel']));
	$qq=str_replace('{qq}',$bd['qq'],$config['qq']);
	require_once libfile('function/discuzcode');
	if(!file_exists('source/plugin/aljbd/com/intro.php')){
		$bd['intro']=discuzcode($bd['intro']);
	}
	$avg=C::t('#aljbd#aljbd_comment')->count_avg_by_bid($bd['id']);
	$avg=intval($avg);
	$gg=explode("\n",str_replace(array("\r\n","\r"),array("\n","\n"),discuzcode($bd['gg'])));
	$adv=unserialize($bd['adv']);
	$advurl=unserialize($bd['advurl']);
	$notice=C::t('#aljbd#aljbd_notice')->fetch_all_by_uid_bid($bd['uid'],$_GET['bid'],0,9);
	$navtitle = $bd['name'].'-'.$config['title'];
	$metakeywords =  $bd['other']?$bd['other']:$config['keywords'];
	$metadescription = $config['description'];
	//$t=C::t('#aljbd#aljbd_goods')->fetch_all_by_uid_bid_view('',$_GET['bid'],0,3);
	$t=C::t('#aljbd#aljbd_goods')->fetch_all_by_uid_bid_view($bd['uid'],$_GET['bid'],0,3);
		//debug($t);	
	include template('aljbd:view');
}else if($_GET['act']=='comment'){
	if(submitcheck('formhash')){
		if(empty($_GET['commentmessage_1'])){
			showerror(lang('plugin/aljbd','s31'));
		}
		if(empty($_G['uid'])){
			showerror(lang('plugin/aljbd','s21'));
		}
		$insertarray=array(
			'uid'=>$_G['uid'],
			'username'=>$_G['username'],
			'content'=>$_GET['commentmessage_1'],
			'bid'=>$_GET['bid'],
			'displayorder'=>$_GET['isprivate'],
			'dateline'=>TIMESTAMP,
			'upid'=>0,
		);
		$cs=explode('@',$_GET['commentscorestr']);
		foreach($cs as $k=>$v){
			if($v==11){
				$insertarray['k']=$cs[$k+1];
			}elseif($v==12){
				$insertarray['h']=$cs[$k+1];
			}elseif($v==13){
				$insertarray['f']=$cs[$k+1];
			}	
		}
		$insertarray['avg']=intval(($insertarray['h']+$insertarray['f'])/2);
		C::t('#aljbd#aljbd')->update_comment_by_bid($_GET['bid']);
		C::t('#aljbd#aljbd_comment')->insert($insertarray);
		showmsg(lang('plugin/aljbd','s32'));
	}
}else if($_GET['act']=='ask'){
	if(submitcheck('formhash')){
		if(empty($_GET['commentmessage_2'])){
			showerror(lang('plugin/aljbd','s33'));
		}
		if(empty($_G['uid'])){
			showerror(lang('plugin/aljbd','s21'));
		}
		$insertarray=array(
			'uid'=>$_G['uid'],
			'username'=>$_G['username'],
			'content'=>$_GET['commentmessage_2'],
			'bid'=>$_GET['bid'],
			'displayorder'=>$_GET['isprivate'],
			'dateline'=>TIMESTAMP,
			'upid'=>0,
			'ask'=>$_GET['ask']
		);
		C::t('#aljbd#aljbd_comment')->insert($insertarray);
		showmsg(lang('plugin/aljbd','s34'));
	}
}else if($_GET['act']=='reply'){
	if(submitcheck('formhash')){
		if(empty($_GET['commentmessage_1'])){
			showerror(lang('plugin/aljbd','s35'));
		}
		if(empty($_G['uid'])){
			showerror(lang('plugin/aljbd','s21'));
		}
		$insertarray=array(
			'uid'=>$_G['uid'],
			'username'=>$_G['username'],
			'content'=>$_GET['commentmessage_1'],
			'bid'=>$_GET['bid'],
			'displayorder'=>$_GET['isprivate'],
			'dateline'=>TIMESTAMP,
			'upid'=>$_GET['upid'],
		);
		C::t('#aljbd#aljbd_comment')->insert($insertarray);
		showmsg(lang('plugin/aljbd','s36'));
	}
}else if($_GET['act']=='map'){
	$bd=C::t('#aljbd#aljbd')->fetch($_GET['bid']);
	$navtitle = $bd['name'].'-'.$config['title'];
	$metakeywords =  $config['keywords'];
	$metadescription = $config['description'];
	include template('aljbd:map');
}else if($_GET['act']=='mark'){
	if(file_exists('source/plugin/aljbd/com/mark.php')){
		include_once 'source/plugin/aljbd/com/mark.php';
	}
}else if($_GET['act']=='point'){
	if(empty($_G['uid'])){
		showmessage(lang('plugin/aljbd','s39'));
	}
	$currpage=$_GET['page']?$_GET['page']:1;
	$perpage=10;
	$start=($currpage-1)*$perpage;
	$num=C::t('#aljbd#aljbd_point')->count_by_buid($_G['uid']);
	$pointlist=C::t('#aljbd#aljbd_point')->fetch_all_by_buid($_G['uid'],$start,$perpage);
	$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=point', 0, 11, false, false);
	include template('aljbd:point');
}else if($_GET['act']=='pointok'){
	if(empty($_G['uid'])){
		showerror(lang('plugin/aljbd','s40'));
	}
	C::t('#aljbd#aljbd')->update($_GET['bid'],array('x'=>$_GET['x'],'y'=>$_GET['y']));
	C::t('#aljbd#aljbd_point')->delete($_GET['pid']);
	showmsg(lang('plugin/aljbd','s41'));
}else if($_GET['act']=='pointdel'){
	if(empty($_G['uid'])){
		showerror(lang('plugin/aljbd','s42'));
	}
	C::t('#aljbd#aljbd_point')->delete($_GET['pid']);
	showmsg(lang('plugin/aljbd','s43'));
}else if($_GET['act']=='iwantclaim'){
	if(submitcheck('formhash')){
		if(empty($_GET['name'])){
			showerror(lang('plugin/aljbd','s47'));
		}
		$user=C::t('common_member')->fetch_by_username($_GET['name']);
		if(empty($user)){
			showerror(lang('plugin/aljbd','s48'));
		}
		C::t('#aljbd#aljbd')->update($_GET['bid'],array('uid'=>$user['uid'],'username'=>$_GET['name']));
		DB::update('aljbd_album',array('uid'=>$user['uid'],'username'=>$_GET['name']),'bid='.$_GET['bid']);
		DB::update('aljbd_consume',array('uid'=>$user['uid'],'username'=>$_GET['name']),'bid='.$_GET['bid']);
		DB::update('aljbd_notice',array('uid'=>$user['uid'],'username'=>$_GET['name']),'bid='.$_GET['bid']);
		DB::update('aljbd_goods',array('uid'=>$user['uid']),'bid='.$_GET['bid']);
		DB::update('aljbd_album_attachments',array('uid'=>$user['uid']),'bid='.$_GET['bid']);
		
		showmsg(lang('plugin/aljbd','s49'));
	}else{
		include template('aljbd:iwantclaim');
	}
}else if($_GET['act']=='delete'){
	if(empty($_G['uid'])){
		showmessage(lang('plugin/aljbd','s39'));
	}
	if($_GET['bid']){
		C::t('#aljbd#aljbd')->delete($_GET['bid']);
	}
	showmessage(lang('plugin/aljbd','s50'),'plugin.php?id=aljbd&act=member');
}else if($_GET['act']=='goodslist'){
	if(empty($_G['uid'])){
		showmessage(lang('plugin/aljbd','s39'));
	}
	$bdlist=C::t('#aljbd#aljbd')->range();
	$glist=C::t('#aljbd#aljbd_goods')->fetch_all_by_uid_bid($_G['uid'],$_GET['bid']);
	include template('aljbd:goodslist');
}else if($_GET['act']=='good'){
	if(file_exists('source/plugin/aljbd/com/good.php')){
		include_once 'source/plugin/aljbd/com/good.php';
	}
}else if($_GET['act']=='goodview'){
	C::t('#aljbd#aljbd_goods')->update_view_by_gid($_GET['gid']);
	//$check=C::t('#aljbd#aljbd_user')->fetch($_G['uid']);
	$check=C::t('#aljbd#aljbd_username')->fetch_by_uid_bid($_G['uid'],$_GET['bid']);
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
	$bdlist=C::t('#aljbd#aljbd')->range();
	$g=C::t('#aljbd#aljbd_goods')->fetch($_GET['gid']);
	
	$t=C::t('#aljbd#aljbd_goods')->fetch_all_by_uid_bid_view($g['uid'],$_GET['bid'],0,6);

	$notice=C::t('#aljbd#aljbd_notice')->fetch_all_by_uid_bid($bd['uid'],$_GET['bid'],0,9);
	$navtitle = $g['name'].'-'.$bd['name'].'-'.$config['title'];
	$metakeywords =  $config['keywords'];
	$metadescription = $config['description'];
	include template('aljbd:goodview');
}else if($_GET['act']=='addgoods'){

	if(submitcheck('formhash')){
		if(empty($_GET['bid'])){
			showerror(lang('plugin/aljbd','s51'));
		}
		if(empty($_GET['name'])){
			showerror(lang('plugin/aljbd','s52'));
		}
		if($_FILES['pic1']['tmp_name']) {
			$picname = $_FILES['pic1']['name'];
			$picsize = $_FILES['pic1']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s19'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$pic1 = "source/plugin/aljbd/images/logo/". $pics;
				if(@copy($_FILES['pic1']['tmp_name'], $pic1)||@move_uploaded_file($_FILES['pic1']['tmp_name'], $pic1)){
					$imageinfo=getimagesize($pic1);
					$w60=$imageinfo[0]<60?$imageinfo[0]:60;
					$h60=$imageinfo[1]<60?$imageinfo[1]:60;
					$w205=$imageinfo[0]<205?$imageinfo[0]:205;
					$h205=$imageinfo[1]<205?$imageinfo[1]:205;
					$w470=$imageinfo[0]<470?$imageinfo[0]:470;
					$h470=$imageinfo[1]<470?$imageinfo[1]:470;
					img2thumb($pic1,$pic1.'.60x60.jpg',$w60,$h60);
					img2thumb($pic1,$pic1.'.205x205.jpg',$w205,$h205);
					img2thumb($pic1,$pic1.'.470x470.jpg',$w470,$h470);
					@unlink($_FILES['pic1']['tmp_name']);
				}
			}
		}else{
			showerror(lang('plugin/aljbd','s56'));
		}
		if($_FILES['pic2']['tmp_name']) {
			$picname = $_FILES['pic2']['name'];
			$picsize = $_FILES['pic2']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s19'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$pic2 = "source/plugin/aljbd/images/logo/". $pics;
				if(@copy($_FILES['pic2']['tmp_name'], $pic2)||@move_uploaded_file($_FILES['pic2']['tmp_name'], $pic2)){
					$imageinfo=getimagesize($pic2);
					$w60=$imageinfo[0]<60?$imageinfo[0]:60;
					$h60=$imageinfo[1]<60?$imageinfo[1]:60;
					$w205=$imageinfo[0]<205?$imageinfo[0]:205;
					$h205=$imageinfo[1]<205?$imageinfo[1]:205;
					$w470=$imageinfo[0]<470?$imageinfo[0]:470;
					$h470=$imageinfo[1]<470?$imageinfo[1]:470;
					img2thumb($pic2,$pic2.'.60x60.jpg',$w60,$h60);
					img2thumb($pic2,$pic2.'.205x205.jpg',$w205,$h205);
					img2thumb($pic2,$pic2.'.470x470.jpg',$w470,$h470);
					@unlink($_FILES['pic2']['tmp_name']);
				}
			}
		}
		if($_FILES['pic3']['tmp_name']) {
			$picname = $_FILES['pic3']['name'];
			$picsize = $_FILES['pic3']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s19'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$pic3 = "source/plugin/aljbd/images/logo/". $pics;
				if(@copy($_FILES['pic3']['tmp_name'], $pic3)||@move_uploaded_file($_FILES['pic3']['tmp_name'], $pic3)){
					$imageinfo=getimagesize($pic3);
					$w60=$imageinfo[0]<60?$imageinfo[0]:60;
					$h60=$imageinfo[1]<60?$imageinfo[1]:60;
					$w205=$imageinfo[0]<205?$imageinfo[0]:205;
					$h205=$imageinfo[1]<205?$imageinfo[1]:205;
					$w470=$imageinfo[0]<470?$imageinfo[0]:470;
					$h470=$imageinfo[1]<470?$imageinfo[1]:470;
					img2thumb($pic3,$pic3.'.60x60.jpg',$w60,$h60);
					img2thumb($pic3,$pic3.'.205x205.jpg',$w205,$h205);
					img2thumb($pic3,$pic3.'.470x470.jpg',$w470,$h470);
					@unlink($_FILES['pic3']['tmp_name']);
				}
			}
		}
		if($_FILES['pic4']['tmp_name']) {
			$picname = $_FILES['pic4']['name'];
			$picsize = $_FILES['pic4']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s19'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$pic4 = "source/plugin/aljbd/images/logo/". $pics;
				if(@copy($_FILES['pic4']['tmp_name'], $pic4)||@move_uploaded_file($_FILES['pic4']['tmp_name'], $pic4)){
					$imageinfo=getimagesize($pic4);
					$w60=$imageinfo[0]<60?$imageinfo[0]:60;
					$h60=$imageinfo[1]<60?$imageinfo[1]:60;
					$w205=$imageinfo[0]<205?$imageinfo[0]:205;
					$h205=$imageinfo[1]<205?$imageinfo[1]:205;
					$w470=$imageinfo[0]<470?$imageinfo[0]:470;
					$h470=$imageinfo[1]<470?$imageinfo[1]:470;
					img2thumb($pic4,$pic4.'.60x60.jpg',$w60,$h60);
					img2thumb($pic4,$pic4.'.205x205.jpg',$w205,$h205);
					img2thumb($pic4,$pic4.'.470x470.jpg',$w470,$h470);
					@unlink($_FILES['pic4']['tmp_name']);
				}
			}
		}
		if($_FILES['pic5']['tmp_name']) {
			$picname = $_FILES['pic5']['name'];
			$picsize = $_FILES['pic5']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s19'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$pic5 = "source/plugin/aljbd/images/logo/". $pics;
				if(@copy($_FILES['pic5']['tmp_name'], $pic5)||@move_uploaded_file($_FILES['pic5']['tmp_name'], $pic5)){
					$imageinfo=getimagesize($pic5);
					$w60=$imageinfo[0]<60?$imageinfo[0]:60;
					$h60=$imageinfo[1]<60?$imageinfo[1]:60;
					$w205=$imageinfo[0]<205?$imageinfo[0]:205;
					$h205=$imageinfo[1]<205?$imageinfo[1]:205;
					$w470=$imageinfo[0]<470?$imageinfo[0]:470;
					$h470=$imageinfo[1]<470?$imageinfo[1]:470;
					img2thumb($pic5,$pic5.'.60x60.jpg',$w60,$h60);
					img2thumb($pic5,$pic5.'.205x205.jpg',$w205,$h205);
					img2thumb($pic5,$pic5.'.470x470.jpg',$w470,$h470);
					@unlink($_FILES['pic5']['tmp_name']);
				}
			}
		}
		C::t('#aljbd#aljbd_goods')->insert(array(
				'bid'=>$_GET['bid'],
				'uid'=>$_G['uid'],
				'name'=>$_GET['name'],
				'price1'=>$_GET['price1'],
				'price2'=>$_GET['price2'],
				'pic1'=>$pic1,
				'pic2'=>$pic2,
				'pic3'=>$pic3,
				'pic4'=>$pic4,
				'pic5'=>$pic5,
				'intro'=>$_GET['intro'],
				'gwurl'=>$_GET['gwurl'],
				'dateline'=>TIMESTAMP,
				'type'=>$_GET['type'],
				'subtype'=>$_GET['subtype'],
		));
		showmsg(lang('plugin/aljbd','s53'));
	}else{
		if(empty($_G['uid'])){
			showmessage(lang('plugin/aljbd','s39'));
		}
		if($_GET['bid']){
			$bd=C::t('#aljbd#aljbd')->fetch($_GET['bid']);
		}
		$typelist=C::t('#aljbd#aljbd_type_goods')->fetch_all_by_upid(0);
		//$rlist=C::t('#aljbd#aljbd_region')->fetch_all_by_upid();
		$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_status(1,'','',$_G['uid']);
		include template('aljbd:addgoods');
	}
}else if($_GET['act']=='editgoods'){
	if(submitcheck('formhash')){
		if(empty($_GET['bid'])){
			showerror(lang('plugin/aljbd','s51'));
		}
		if(empty($_GET['name'])){
			showerror(lang('plugin/aljbd','s52'));
		}
		if($_FILES['pic1']['tmp_name']) {
			$picname = $_FILES['pic1']['name'];
			$picsize = $_FILES['pic1']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s19'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$pic1 = "source/plugin/aljbd/images/logo/". $pics;
				if(@copy($_FILES['pic1']['tmp_name'], $pic1)||@move_uploaded_file($_FILES['pic1']['tmp_name'], $pic1)){
					$imageinfo=getimagesize($pic1);
					$w60=$imageinfo[0]<60?$imageinfo[0]:60;
					$h60=$imageinfo[1]<60?$imageinfo[1]:60;
					$w205=$imageinfo[0]<205?$imageinfo[0]:205;
					$h205=$imageinfo[1]<205?$imageinfo[1]:205;
					$w470=$imageinfo[0]<470?$imageinfo[0]:470;
					$h470=$imageinfo[1]<470?$imageinfo[1]:470;
					img2thumb($pic1,$pic1.'.60x60.jpg',$w60,$h60);
					img2thumb($pic1,$pic1.'.205x205.jpg',$w205,$h205);
					img2thumb($pic1,$pic1.'.470x470.jpg',$w470,$h470);
					@unlink($_FILES['pic1']['tmp_name']);
				}
			}
		}
		if($_FILES['pic2']['tmp_name']) {
			$picname = $_FILES['pic2']['name'];
			$picsize = $_FILES['pic2']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s19'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$pic2 = "source/plugin/aljbd/images/logo/". $pics;
				if(@copy($_FILES['pic2']['tmp_name'], $pic2)||@move_uploaded_file($_FILES['pic2']['tmp_name'], $pic2)){
					$imageinfo=getimagesize($pic2);
					$w60=$imageinfo[0]<60?$imageinfo[0]:60;
					$h60=$imageinfo[1]<60?$imageinfo[1]:60;
					$w205=$imageinfo[0]<205?$imageinfo[0]:205;
					$h205=$imageinfo[1]<205?$imageinfo[1]:205;
					$w470=$imageinfo[0]<470?$imageinfo[0]:470;
					$h470=$imageinfo[1]<470?$imageinfo[1]:470;
					img2thumb($pic2,$pic2.'.60x60.jpg',$w60,$h60);
					img2thumb($pic2,$pic2.'.205x205.jpg',$w205,$h205);
					img2thumb($pic2,$pic2.'.470x470.jpg',$w470,$h470);
					@unlink($_FILES['pic2']['tmp_name']);
				}
			}
		}
		if($_FILES['pic3']['tmp_name']) {
			$picname = $_FILES['pic3']['name'];
			$picsize = $_FILES['pic3']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s19'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$pic3 = "source/plugin/aljbd/images/logo/". $pics;
				if(@copy($_FILES['pic3']['tmp_name'], $pic3)||@move_uploaded_file($_FILES['pic3']['tmp_name'], $pic3)){
					$imageinfo=getimagesize($pic3);
					$w60=$imageinfo[0]<60?$imageinfo[0]:60;
					$h60=$imageinfo[1]<60?$imageinfo[1]:60;
					$w205=$imageinfo[0]<205?$imageinfo[0]:205;
					$h205=$imageinfo[1]<205?$imageinfo[1]:205;
					$w470=$imageinfo[0]<470?$imageinfo[0]:470;
					$h470=$imageinfo[1]<470?$imageinfo[1]:470;
					img2thumb($pic3,$pic3.'.60x60.jpg',$w60,$h60);
					img2thumb($pic3,$pic3.'.205x205.jpg',$w205,$h205);
					img2thumb($pic3,$pic3.'.470x470.jpg',$w470,$h470);
					@unlink($_FILES['pic3']['tmp_name']);
				}
			}
		}
		if($_FILES['pic4']['tmp_name']) {
			$picname = $_FILES['pic4']['name'];
			$picsize = $_FILES['pic4']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s19'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$pic4 = "source/plugin/aljbd/images/logo/". $pics;
				if(@copy($_FILES['pic4']['tmp_name'], $pic4)||@move_uploaded_file($_FILES['pic4']['tmp_name'], $pic4)){
					$imageinfo=getimagesize($pic4);
					$w60=$imageinfo[0]<60?$imageinfo[0]:60;
					$h60=$imageinfo[1]<60?$imageinfo[1]:60;
					$w205=$imageinfo[0]<205?$imageinfo[0]:205;
					$h205=$imageinfo[1]<205?$imageinfo[1]:205;
					$w470=$imageinfo[0]<470?$imageinfo[0]:470;
					$h470=$imageinfo[1]<470?$imageinfo[1]:470;
					img2thumb($pic4,$pic4.'.60x60.jpg',$w60,$h60);
					img2thumb($pic4,$pic4.'.205x205.jpg',$w205,$h205);
					img2thumb($pic4,$pic4.'.470x470.jpg',$w470,$h470);
					@unlink($_FILES['pic4']['tmp_name']);
				}
			}
		}
		if($_FILES['pic5']['tmp_name']) {
			$picname = $_FILES['pic5']['name'];
			$picsize = $_FILES['pic5']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s19'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$pic5 = "source/plugin/aljbd/images/logo/". $pics;
				if(@copy($_FILES['pic5']['tmp_name'], $pic5)||@move_uploaded_file($_FILES['pic5']['tmp_name'], $pic5)){
					$imageinfo=getimagesize($pic5);
					$w60=$imageinfo[0]<60?$imageinfo[0]:60;
					$h60=$imageinfo[1]<60?$imageinfo[1]:60;
					$w205=$imageinfo[0]<205?$imageinfo[0]:205;
					$h205=$imageinfo[1]<205?$imageinfo[1]:205;
					$w470=$imageinfo[0]<470?$imageinfo[0]:470;
					$h470=$imageinfo[1]<470?$imageinfo[1]:470;
					img2thumb($pic5,$pic5.'.60x60.jpg',$w60,$h60);
					img2thumb($pic5,$pic5.'.205x205.jpg',$w205,$h205);
					img2thumb($pic5,$pic5.'.470x470.jpg',$w470,$h470);
					@unlink($_FILES['pic5']['tmp_name']);
				}
			}
		}
		$updatearray=array(
			'bid'=>$_GET['bid'],
			'uid'=>$_G['uid'],
			'name'=>$_GET['name'],
			'price1'=>$_GET['price1'],
			'price2'=>$_GET['price2'],
			'intro'=>$_GET['intro'],
			'gwurl'=>$_GET['gwurl'],
			'dateline'=>TIMESTAMP,
			'type'=>$_GET['type'],
			'subtype'=>$_GET['subtype'],
		);
		if($pic1){
			$updatearray['pic1']=$pic1;
		}
		if($pic2){
			$updatearray['pic2']=$pic2;
		}
		if($pic3){
			$updatearray['pic3']=$pic3;
		}
		if($pic4){
			$updatearray['pic4']=$pic4;
		}
		if($pic5){
			$updatearray['pic5']=$pic5;
		}
		C::t('#aljbd#aljbd_goods')->update($_GET['gid'],$updatearray);
		showmsg(lang('plugin/aljbd','s54'));
	}else{
		if(empty($_G['uid'])){
			showmessage(lang('plugin/aljbd','s39'));
		}
		$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_status(1,'','',$_G['uid']);
		$typelist=C::t('#aljbd#aljbd_type_goods')->fetch_all_by_upid(0);
		$g=C::t('#aljbd#aljbd_goods')->fetch($_GET['gid']);
		include template('aljbd:addgoods');
	}
}else if($_GET['act']=='deletegoods'){
	if($_GET['formhash']!=FORMHASH){
		exit('Access Denied!');
	}
	if($_GET['gid']){
		C::t('#aljbd#aljbd_goods')->delete($_GET['gid']);
	}
	showmsg(lang('plugin/aljbd','s55'));
}else if($_GET['act']=='consume'){
	if(file_exists('source/plugin/aljbd/com/consume.php')){
		include_once 'source/plugin/aljbd/com/consume.php';
	}
}else if($_GET['act']=='consumeview'){
	C::t('#aljbd#aljbd_consume')->update_view_by_gid($_GET['cid']);
	//$check=C::t('#aljbd#aljbd_user')->fetch($_G['uid']);
	$check=C::t('#aljbd#aljbd_username')->fetch_by_uid_bid($_G['uid'],$_GET['bid']);
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
	
	$bdlist=C::t('#aljbd#aljbd')->range();
	$c=C::t('#aljbd#aljbd_consume')->fetch($_GET['cid']);
	//$t=C::t('#aljbd#aljbd_goods')->fetch_all_by_uid_bid_view($g['uid'],$_GET['bid'],0,6);
	$notice=C::t('#aljbd#aljbd_notice')->fetch_all_by_uid_bid($bd['uid'],$_GET['bid'],0,9);
	$navtitle = $c['subject'].'-'.$bd['name'].'-'.$config['title'];
	$metakeywords =  $config['keywords'];
	$metadescription = $config['description'];
	include template('aljbd:consumeview');
}else if($_GET['do']=='print') {
	$c=C::t('#aljbd#aljbd_consume')->fetch($_GET['cid']);
	DB::query('UPDATE '.DB::table('aljbd_consume').' SET downnum=downnum+1 WHERE id=\''.$_GET['cid'].'\'');
	echo '<body onload="window.print()"><img src="'.$c['pic'].'"></body>';
	exit();
}else if($_GET['act']=='consumelist'){
	if(empty($_G['uid'])){
		showmessage(lang('plugin/aljbd','s39'));
	}
	$bdlist=C::t('#aljbd#aljbd')->range();
	$nlist=C::t('#aljbd#aljbd_consume')->fetch_all_by_uid_bid($_G['uid'],$_GET['bid']);
	include template('aljbd:consumelist');
}else if($_GET['act']=='cask'){
	if(submitcheck('formhash')){
		if(empty($_GET['commentmessage_2'])){
			showerror(lang('plugin/aljbd','s33'));
		}
		if(empty($_G['uid'])){
			showerror(lang('plugin/aljbd','s21'));
		}
		$insertarray=array(
			'uid'=>$_G['uid'],
			'username'=>$_G['username'],
			'content'=>$_GET['commentmessage_2'],
			'bid'=>$_GET['bid'],
			'dateline'=>TIMESTAMP,
			'upid'=>0,
			'cid'=>$_GET['cid']
		);
		C::t('#aljbd#aljbd_comment_consume')->insert($insertarray);
		showmsg(lang('plugin/aljbd','s34'));
	}
}else if($_GET['act']=='creply'){
	if(submitcheck('formhash')){
		if(empty($_GET['commentmessage_1'])){
			showerror(lang('plugin/aljbd','s35'));
		}
		if(empty($_G['uid'])){
			showerror(lang('plugin/aljbd','s21'));
		}
		$insertarray=array(
			'uid'=>$_G['uid'],
			'username'=>$_G['username'],
			'content'=>$_GET['commentmessage_1'],
			'bid'=>$_GET['bid'],
			'cid'=>$_GET['cid'],
			'dateline'=>TIMESTAMP,
			'upid'=>$_GET['upid'],
		);
		C::t('#aljbd#aljbd_comment_consume')->insert($insertarray);
		showmsg(lang('plugin/aljbd','s36'));
	}
}else if($_GET['act']=='addconsume'){
	if(submitcheck('formhash')){
		if($_FILES['logo']['tmp_name']) {
			$picname = $_FILES['logo']['name'];
			$picsize = $_FILES['logo']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s19'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$logo = "source/plugin/aljbd/images/consume/". $pics;
				if(@copy($_FILES['logo']['tmp_name'], $logo)||@move_uploaded_file($_FILES['logo']['tmp_name'], $logo)){
					@unlink($_FILES['logo']['tmp_name']);
				}
			}
		}else{
			showerror(lang('plugin/aljbd','logo'));
		}
		
		
		$insertarray=array(
			'username'=>$_G['username'],
			'uid'=>$_G['uid'],
			'subject'=>$_GET['name'],
			'bid'=>$_GET['bid'],
			'pic'=>$logo,
			'jieshao'=>$_GET['jieshao'],
			'xianzhi'=>$_GET['xianzhi'],
			'start'=>TIMESTAMP,
			'end'=>strtotime($_GET['end']),
			'mianze'=>$_GET['mianze'],
			'dateline'=>TIMESTAMP,
			'type'=>$_GET['type'],
			'subtype'=>$_GET['subtype'],
		);
		C::t('#aljbd#aljbd_consume')->insert($insertarray);
		
		showmsg(lang('plugin/aljbd','s53'));
	}else{
		if(empty($_G['uid'])){
			showmessage(lang('plugin/aljbd','s39'));
		}
		$g=C::t('#aljbd#aljbd_consume')->fetch($_GET['id']);
		$typelist=C::t('#aljbd#aljbd_type_quan')->fetch_all_by_upid(0);
		$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_status(1,'','',$_G['uid']);
		include template('aljbd:addconsume');
	}
}else if($_GET['act']=='editconsume'){
	if(submitcheck('formhash')){
		if($_FILES['logo']['tmp_name']) {
			$picname = $_FILES['logo']['name'];
			$picsize = $_FILES['logo']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s29'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$logo = "source/plugin/aljbd/images/consume/". $pics;
				if(@copy($_FILES['logo']['tmp_name'], $logo)||@move_uploaded_file($_FILES['logo']['tmp_name'], $logo)){
					@unlink($_FILES['logo']['tmp_name']);
				}
			}
		}
		$updatearray=array(
			'subject'=>$_GET['name'],
			'bid'=>$_GET['bid'],
			'jieshao'=>$_GET['jieshao'],
			'xianzhi'=>$_GET['xianzhi'],
			'end'=>strtotime($_GET['end']),
			'mianze'=>$_GET['mianze'],
			'type'=>$_GET['type'],
			'subtype'=>$_GET['subtype'],
		);
		if($logo){
			$updatearray['pic']=$logo;
		}
	
		C::t('#aljbd#aljbd_consume')->update($_GET['cid'],$updatearray);
		showmsg(lang('plugin/aljbd','s54'));
	}else{
		if(empty($_G['uid'])){
			showmessage(lang('plugin/aljbd','s39'));
		}
		$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_status(1,'','',$_G['uid']);
		$g=C::t('#aljbd#aljbd_consume')->fetch($_GET['id']);
		$typelist=C::t('#aljbd#aljbd_type_quan')->fetch_all_by_upid(0);	
		$n=C::t('#aljbd#aljbd_consume')->fetch($_GET['nid']);
		include template('aljbd:addconsume');
	}
}else if($_GET['act']=='deleteconsume'){
	if($_GET['formhash']!=FORMHASH){
		exit('Access Denied!');
	}
	if(empty($_G['uid'])){
		showmessage(lang('plugin/aljbd','s39'));
	}
	if($_GET['nid']){
		C::t('#aljbd#aljbd_consume')->delete($_GET['nid']);
	}
	showmsg(lang('plugin/aljbd','s55'));
}else if($_GET['act']=='deletenotice'){
	if($_GET['formhash']!=FORMHASH){
		exit('Access Denied!');
	}
	if(empty($_G['uid'])){
		showmessage(lang('plugin/aljbd','s39'));
	}
	if($_GET['nid']){
		C::t('#aljbd#aljbd_notice')->delete($_GET['nid']);
	}
	showmsg(lang('plugin/aljbd','s55'));
}else if($_GET['act']=='noticeview'){
	C::t('#aljbd#aljbd_notice')->update_view_by_gid($_GET['nid']);
	//$check=C::t('#aljbd#aljbd_user')->fetch($_G['uid']);
	$check=C::t('#aljbd#aljbd_username')->fetch_by_uid_bid($_G['uid'],$_GET['bid']);
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
	$bdlist=C::t('#aljbd#aljbd')->range();
	$n=C::t('#aljbd#aljbd_notice')->fetch($_GET['nid']);
	$t=C::t('#aljbd#aljbd_goods')->fetch_all_by_uid_bid_view($g['uid'],$_GET['bid'],0,6);
	$notice=C::t('#aljbd#aljbd_notice')->fetch_all_by_uid_bid($bd['uid'],$_GET['bid'],0,9);
	$navtitle = $n['subject'].'-'.$bd['name'].'-'.$config['title'];
	$metakeywords =  $config['keywords'];
	$metadescription = $config['description'];
	include template('aljbd:noticeview');
}else if($_GET['act']=='noticelist'){
	if(empty($_G['uid'])){
		showmessage(lang('plugin/aljbd','s39'));
	}
	$bdlist=C::t('#aljbd#aljbd')->range();
	$nlist=C::t('#aljbd#aljbd_notice')->fetch_all_by_uid_bid($_G['uid'],$_GET['bid']);
	include template('aljbd:noticelist');
}else if($_GET['act']=='addnotice'){
	if(submitcheck('formhash')){
		if(empty($_GET['bid'])){
			showerror(lang('plugin/aljbd','s51'));
		}
		if(empty($_GET['subject'])){
			showerror(lang('plugin/aljbd','aljbd_1'));
		}
		if(empty($_GET['intro'])){
			showerror(lang('plugin/aljbd','aljbd_2'));
		}
		if($_FILES['logo']['tmp_name']) {
			$picname = $_FILES['logo']['name'];
			$picsize = $_FILES['logo']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s19'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$logo = "source/plugin/aljbd/images/notice/". $pics;
				if(@copy($_FILES['logo']['tmp_name'], $logo)||@move_uploaded_file($_FILES['logo']['tmp_name'], $logo)){
					@unlink($_FILES['logo']['tmp_name']);
				}
			}
		}else{
			showerror(lang('plugin/aljbd','noticead'));
		}
		
		C::t('#aljbd#aljbd_notice')->insert(array(
				'bid'=>$_GET['bid'],
				'uid'=>$_G['uid'],
				'username'=>$_G['username'],
				'subject'=>$_GET['subject'],
				'content'=>$_GET['intro'],
				'dateline'=>TIMESTAMP,
				'type'=>$_GET['type'],
				'pic'=>$logo,
		));
		showmsg(lang('plugin/aljbd','s53'));
	}else{
		if(empty($_G['uid'])){
			showmessage(lang('plugin/aljbd','s39'));
		}
		if($_GET['bid']){
			$bd=C::t('#aljbd#aljbd')->fetch($_GET['bid']);
		}
		$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_status(1,'','',$_G['uid']);
		$g=C::t('#aljbd#aljbd_notice')->fetch($_GET['id']);
		$typelist=C::t('#aljbd#aljbd_type_notice')->fetch_all_by_upid(0);
		//debug($g);
		include template('aljbd:addnotice');
	}
}else if($_GET['act']=='editnotice'){
	if(submitcheck('formhash')){
		if(empty($_GET['bid'])){
			showerror(lang('plugin/aljbd','s51'));
		}
		if(empty($_GET['subject'])){
			showerror(lang('plugin/aljbd','aljbd_1'));
		}
		if(empty($_GET['intro'])){
			showerror(lang('plugin/aljbd','aljbd_2'));
		}
		if($_FILES['logo']['tmp_name']) {
			$picname = $_FILES['logo']['name'];
			$picsize = $_FILES['logo']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s29'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$logo = "source/plugin/aljbd/images/notice/". $pics;
				if(@copy($_FILES['logo']['tmp_name'], $logo)||@move_uploaded_file($_FILES['logo']['tmp_name'], $logo)){
					@unlink($_FILES['logo']['tmp_name']);
				}
			}
		}
		$updatearray=array(
			'bid'=>$_GET['bid'],
			'uid'=>$_G['uid'],
			'username'=>$_G['username'],
			'subject'=>$_GET['subject'],
			'content'=>$_GET['intro'],
			'dateline'=>TIMESTAMP,
			'type'=>$_GET['type'],
		);
		if($logo){
			$updatearray['pic']=$logo;
		}
		C::t('#aljbd#aljbd_notice')->update($_GET['nid'],$updatearray);
		showmsg(lang('plugin/aljbd','s54'));
	}else{
		if(empty($_G['uid'])){
			showmessage(lang('plugin/aljbd','s39'));
		}
		$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_status(1,'','',$_G['uid']);
		$n=C::t('#aljbd#aljbd_notice')->fetch($_GET['nid']);
		$g=C::t('#aljbd#aljbd_notice')->fetch($_GET['id']);
		$typelist=C::t('#aljbd#aljbd_type_notice')->fetch_all_by_upid(0);
		include template('aljbd:addnotice');
	}
}else if($_GET['act']=='notice'){
	if(file_exists('source/plugin/aljbd/com/notice.php')){
		include_once 'source/plugin/aljbd/com/notice.php';
	}
}else if($_GET['act']=='nask'){
	if(submitcheck('formhash')){
		if(empty($_GET['commentmessage_2'])){
			showerror(lang('plugin/aljbd','s33'));
		}
		if(empty($_G['uid'])){
			showerror(lang('plugin/aljbd','s21'));
		}
		$insertarray=array(
			'uid'=>$_G['uid'],
			'username'=>$_G['username'],
			'content'=>$_GET['commentmessage_2'],
			'bid'=>$_GET['bid'],
			'dateline'=>TIMESTAMP,
			'upid'=>0,
			'nid'=>$_GET['nid']
		);
		C::t('#aljbd#aljbd_comment_notice')->insert($insertarray);
		showmsg(lang('plugin/aljbd','s34'));
	}
}else if($_GET['act']=='nreply'){
	if(submitcheck('formhash')){
		if(empty($_GET['commentmessage_1'])){
			showerror(lang('plugin/aljbd','s35'));
		}
		if(empty($_G['uid'])){
			showerror(lang('plugin/aljbd','s21'));
		}
		$insertarray=array(
			'uid'=>$_G['uid'],
			'username'=>$_G['username'],
			'content'=>$_GET['commentmessage_1'],
			'bid'=>$_GET['bid'],
			'nid'=>$_GET['nid'],
			'dateline'=>TIMESTAMP,
			'upid'=>$_GET['upid'],
		);
		C::t('#aljbd#aljbd_comment_notice')->insert($insertarray);
		showmsg(lang('plugin/aljbd','s36'));
	}
}else if($_GET['act']=='albumlist'){
	if(empty($_G['uid'])){
		showmessage(lang('plugin/aljbd','s39'));
	}
	$bdlist=C::t('#aljbd#aljbd')->range();
	$currpage=$_GET['page']?$_GET['page']:1;
	$perpage=9;
	$allpage=ceil($num/$perpage);
	$start=($currpage-1)*$perpage;
	$num=C::t('#aljbd#aljbd_album')->count_by_uid_bid($_G['uid'],$_GET['bid']);
	$alist=C::t('#aljbd#aljbd_album')->fetch_all_by_uid_bid($_G['uid'],$_GET['bid'],$start,$perpage);
	$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=notice&bid='.$_GET['bid'], 0, 11, false, false);
	include template('aljbd:albumlist');
}else if($_GET['act']=='album'){
	
	if(file_exists('source/plugin/aljbd/com/album.php')){
		include_once 'source/plugin/aljbd/com/album.php';
	}
}else if($_GET['act']=='addalbum'){
	if(submitcheck('formhash')){
		if(empty($_GET['bid'])){
			showerror(lang('plugin/aljbd','s51'));
		}
		if(empty($_GET['albumname'])){
			showerror(lang('plugin/aljbd','album_1'));
		}
		
		C::t('#aljbd#aljbd_album')->insert(array(
				'bid'=>$_GET['bid'],
				'uid'=>$_G['uid'],
				'username'=>$_G['username'],
				'albumname'=>$_GET['albumname'],
				'description'=>$_GET['description'],
				'dateline'=>TIMESTAMP,
				'displayorder'=>'100',
		));
		showmsg(lang('plugin/aljbd','s53'));
	}else{
		if(empty($_G['uid'])){
			showmessage(lang('plugin/aljbd','s39'));
		}
		if($_GET['bid']){
			$bd=C::t('#aljbd#aljbd')->fetch($_GET['bid']);
		}
		$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_status(1,'','',$_G['uid']);
		include template('aljbd:addalbum');
	}
}else if($_GET['act']=='delalbum'){
	if(!$_GET['aid']){
		showmessage(lang('plugin/aljbd','aljbd_6'));
	}
	if(empty($_G['uid'])){
		showmessage(lang('plugin/aljbd','s39'));
	}
	if($_GET['formhash']!=formhash()){
		showmessage(lang('plugin/aljbd','aljbd_7'));
	}
	if(C::t('#aljbd#aljbd_album_attachments')->conut_by_aid($_GET['aid'])){
		foreach(C::t('#aljbd#aljbd_album_attachments')->fetch_all_by_status(' where aid='.$_GET['aid']) as $atid){
/*
			unlink($atid['pic']);
			unlink($atid['pic'].'.72x72.jpg');
			unlink($atid['pic'].'.100x100.jpg');
			unlink($atid['pic'].'.550x550.jpg');
*/
			C::t('#aljbd#aljbd_album_attachments')->delete($atid['id']);
		}
	}
	C::t('#aljbd#aljbd_album')->delete($_GET['aid']);
	showmessage(lang('plugin/aljbd','admin_8'),'plugin.php?id=aljbd&act=albumlist');
}else if($_GET['act']=='delalbum_1'){
	if(!$_GET['aaid']){
		showmessage(lang('plugin/aljbd','aljbd_6'));
	}
	if(empty($_G['uid'])){
		showmessage(lang('plugin/aljbd','s39'));
	}
	if($_GET['formhash']!=formhash()){
		showmessage(lang('plugin/aljbd','aljbd_7'));
	}
	$al=C::t('#aljbd#aljbd_album_attachments')->fetch($_GET['aaid']);
	if($al){
/*
		unlink($al['pic']);
		unlink($al['pic'].'.72x72.jpg');
		unlink($al['pic'].'.100x100.jpg');
		unlink($al['pic'].'.550x550.jpg');
*/
		C::t('#aljbd#aljbd_album_attachments')->delete($_GET['aaid']);
	}
	
	DB::query("UPDATE ".DB::table('aljbd_album')." SET picnum=picnum-1 WHERE id='".$_GET['aid']."'", 'UNBUFFERED');
	if(!DB::result_first("select count(*) from ".DB::table('aljbd_album_attachments')." where aid=".$_GET['aid'])){
		DB::query("UPDATE ".DB::table('aljbd_album')." SET subjectimage='' WHERE id='".$_GET['aid']."'", 'UNBUFFERED');
		DB::query("UPDATE ".DB::table('aljbd_album')." SET picnum=0 WHERE id='".$_GET['aid']."'", 'UNBUFFERED');
	}
	showmessage(lang('plugin/aljbd','admin_8'),'plugin.php?id=aljbd&act=albumall&aid='.$_GET['aid']);
}else if($_GET['act']=='editalbum'){
	if(submitcheck('formhash')){
		if(empty($_GET['bid'])){
			showerror(lang('plugin/aljbd','s51'));
		}
		if(empty($_GET['albumname'])){
			showerror(lang('plugin/aljbd','album_1'));
		}
		$updatearray=array(
			'bid'=>$_GET['bid'],
			'uid'=>$_G['uid'],
			'username'=>$_G['username'],
			'albumname'=>$_GET['albumname'],
			'description'=>$_GET['description'],
			'dateline'=>TIMESTAMP,
			'displayorder'=>'100',
		);
		C::t('#aljbd#aljbd_album')->update($_GET['aid'],$updatearray);
		showmsg(lang('plugin/aljbd','s53'));
	}else{
		if(empty($_G['uid'])){
			showmessage(lang('plugin/aljbd','s39'));
		}
		if($_GET['bid']){
			$bd=C::t('#aljbd#aljbd')->fetch($_GET['bid']);
		}
		$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_status(1,'','',$_G['uid']);
		$a=C::t('#aljbd#aljbd_album')->fetch($_GET['aid']);
		include template('aljbd:addalbum');
	}
}else if($_GET['act']=='albumall'){
		$a=C::t('#aljbd#aljbd_album')->range();
		$currpage=$_GET['page']?$_GET['page']:1;
		$perpage=9;
		$allpage=ceil($num/$perpage);
		$start=($currpage-1)*$perpage;
		$num=C::t('#aljbd#aljbd_album_attachments')->count_by_uid_bid($_G['uid'],$_GET['aid']);
		$alist=C::t('#aljbd#aljbd_album_attachments')->fetch_all_by_uid_bid($_G['uid'],$_GET['aid'],$start,$perpage);
		$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=notice&bid='.$_GET['bid'], 0, 11, false, false);
		include template('aljbd:albumall');
}else if($_GET['act']=='addalbumimg'){
	if(submitcheck('formhash')){
		if(empty($_GET['aid'])){
			showerror(lang('plugin/aljbd','album_2'));
		}
		if($_FILES['pic']['tmp_name']) {
			$picname = $_FILES['pic']['name'];
			$picsize = $_FILES['pic']['size'];
		
			if ($picname != "") {
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
					showerror(lang('plugin/aljbd','s19'));
				}
				$rand = rand(100, 999);
				$pics = date("YmdHis") . $rand . $type;
				$pic1 = "source/plugin/aljbd/images/album/". $pics;
				if(@copy($_FILES['pic']['tmp_name'], $pic1)||@move_uploaded_file($_FILES['pic']['tmp_name'], $pic1)){
					$imageinfo=getimagesize($pic1);
					$w60=$imageinfo[0]<72?$imageinfo[0]:72;
					$h60=$imageinfo[1]<72?$imageinfo[1]:72;
					$w205=$imageinfo[0]<100?$imageinfo[0]:100;
					$h205=$imageinfo[1]<100?$imageinfo[1]:100;
					$w470=$imageinfo[0]<550?$imageinfo[0]:550;
					$h470=$imageinfo[1]<550?$imageinfo[1]:550;
					img2thumb($pic1,$pic1.'.72x72.jpg',$w60,$h60);
					img2thumb($pic1,$pic1.'.100x100.jpg',$w205,$h205);
					img2thumb($pic1,$pic1.'.550x550.jpg',$w470,$h470);
					@unlink($_FILES['pic']['tmp_name']);
				}
			}
		}else{
			showerror(lang('plugin/aljbd','album_3'));
		}
		//debug($pic1);
		$apic=C::t('#aljbd#aljbd_album')->fetch($_GET['aid']);
		if(!$apic['subjectimage']){
			DB::query("UPDATE ".DB::table('aljbd_album')." SET subjectimage='".$pic1."' WHERE id='".$_GET['aid']."'", 'UNBUFFERED');
		}
		DB::query("UPDATE ".DB::table('aljbd_album')." SET picnum=picnum+1 WHERE id='".$_GET['aid']."'", 'UNBUFFERED');
		DB::query("UPDATE ".DB::table('aljbd_album')." SET lastpost=".$_G['timestamp']." WHERE id='".$_GET['aid']."'", 'UNBUFFERED');
		C::t('#aljbd#aljbd_album_attachments')->insert(array(
				'bid'=>$_GET['bid'],
				'uid'=>$_G['uid'],
				'aid'=>$_GET['aid'],
				'pic'=>$pic1,
				'dateline'=>TIMESTAMP,
				'displayorder'=>'100',
				'alt'=>$_GET['alt'],
		));
		showmsg(lang('plugin/aljbd','s53'));
	}else{
		if($_GET['bid']){
			$bd=C::t('#aljbd#aljbd')->fetch($_GET['bid']);
		}
		$alist=C::t('#aljbd#aljbd_album')->range();
		include template('aljbd:addalbumimg');
	}
}else if($_GET['act']=='albumview'){
	C::t('#aljbd#aljbd_notice')->update_view_by_gid($_GET['nid']);
	//$check=C::t('#aljbd#aljbd_user')->fetch($_G['uid']);
	$check=C::t('#aljbd#aljbd_username')->fetch_by_uid_bid($_G['uid'],$_GET['bid']);
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
	
	$bdlist=C::t('#aljbd#aljbd')->range();
	$num=C::t('#aljbd#aljbd_album_attachments')->count_by_uid_bid($bd['uid'],$_GET['aid']);
	$alist=C::t('#aljbd#aljbd_album_attachments')->fetch_all_by_uid_bid($bd['uid'],$_GET['aid'],$start,$perpage);
	//debug($_GET['aid']);
	$ab=C::t('#aljbd#aljbd_album')->fetch($_GET['aid']);
	$notice=C::t('#aljbd#aljbd_notice')->fetch_all_by_uid_bid($bd['uid'],$_GET['bid'],0,9);
	//debug($a['albumname']);
	$navtitle = $ab['albumname'].'-'.$bd['name'].'-'.$config['title'];
	$metakeywords =  $config['keywords'];
	$metadescription = $config['description'];
	include template('aljbd:albumview');
}else if($_GET['act']=='shoplist'){
		$navtitle = '商品';
		$metakeywords =  $config['keywords'];
		$metadescription = $config['description'];
		
		
		/*
$bdlist=C::t('#aljbd#aljbd_goods')->fetch_all();
		$bdlist=C::t('#aljbd#aljbd_goods')->fetch_all_by_status('',$start,$perpage,'',$_GET['type'],$_GET['subtype'],$_GET['region'],$_GET['subregion'],$_GET['order'],$search);

		if($_GET['type']){
			$bdlist=C::t('#aljbd#aljbd_goods')->counts_by_type($_GET['type']);
		}
		$typecount=C::t('#aljbd#aljbd_goods')->count_by_type();
		//debug($typecount);
		foreach($typecount as $tc){
			$tcs[$tc['type']]=$tc['num'];
		}
		//debug($tcs);
		$recommendlist=C::t('#aljbd#aljbd')->fetch_all_by_recommend(1,0,10);
		$tlist=C::t('#aljbd#aljbd_type_goods')->fetch_all_by_upid(0);
		$tlist_n=C::t('#aljbd#aljbd_type_goods')->fetch_all_by_type($_GET['type']);
		$tlist_name=$tlist_n[0]['subject'];
		$num=count($bdlist);
*/
		$typecount=C::t('#aljbd#aljbd_goods')->count_by_type();
	//debug($typecount);
	foreach($typecount as $tc){
		$tcs[$tc['type']]=$tc['num'];
	} 
	//debug($tcs);
	if($_GET['type']){
		$subtypecount=C::t('#aljbd#aljbd_goods')->count_by_type($_GET['type']);
	}
	$aljbd=C::t('#aljbd#aljbd')->range();

	//debug($aljbd);
	$config=$_G['cache']['plugin']['aljbd'];
	$typelist=C::t('#aljbd#aljbd_type_goods')->range();
	$tlist=C::t('#aljbd#aljbd_type_goods')->fetch_all_by_upid(0);
	//$rlist=C::t('#aljbd#aljbd_region')->fetch_all_by_upid(0);
	$currpage=$_GET['page']?$_GET['page']:1;
	$perpage=$config['page'];
	if(submitcheck('submit')){
		
		$search=$_GET['kw'];
	
	}
	$num=C::t('#aljbd#aljbd_goods')->count_by_status('','',$_GET['type'],$_GET['subtype'],$_GET['region'],$_GET['subregion'],$search);
	
	
	$allpage=ceil($num/$perpage);
	$start=($currpage-1)*$perpage;
	$recommendlist=C::t('#aljbd#aljbd')->fetch_all_by_recommend(1,0,10);
	$recommendlist_goods=C::t('#aljbd#aljbd_goods')->fetch_all_by_recommend(1,0,10);
	if($config['isrewrite']){
		if($_GET['order']=='1'){
			$_GET['order']='view';
		}else if($_GET['order']=='2'){
			$_GET['order']='price1';
		}else{
			$_GET['order']='';
		}
		if($_GET['view']=='3'){
			$_GET['view']="pic";
		}else if($_GET['view']=='4'){
			$_GET['view']="list";
		}else{
			$_GET['view']='';
		}
	}
	
	$bdlist=C::t('#aljbd#aljbd_goods')->fetch_all_by_status('',$start,$perpage,'',$_GET['type'],$_GET['subtype'],$_GET['region'],$_GET['subregion'],$_GET['order'],$search);
	//
	$notice=C::t('#aljbd#aljbd_notice')->fetch_all_by_uid_bid('','',0,9);
	foreach($bdlist as $k=>$v){
		$bdlist[$k]['c']=C::t('#aljbd#aljbd_comment')->fetch_by_bid($v['id']);
		$bdlist[$k]['q']=str_replace('{qq}',$v['qq'],$config['qq']);
	}//debug($bdlist);
	$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=shoplist&type='.$_GET['type'].'&subtype='.$_GET['subtype'].'&order='.$_GET['order'].'&kw='.$_GET['kw'].'&view='.$_GET['view'], 0, 11, false, false);
	
		//debug($tlist_name);
	include template('aljbd:shoplist');
	
}else if($_GET['act']=='shanglist'){
		$keyword=$_GET['keyword'];	
		if($keyword!=''){
			$bdlist=C::t('#aljbd#aljbd_notice')->find_by_like($keyword);
		}else{
			$bdlist=C::t('#aljbd#aljbd_notice')->fetch_all();
			}
		$tlist=C::t('#aljbd#aljbd_type_notice')->fetch_all_by_upid(0);
		if($_GET['type']){
			$bdlist=C::t('#aljbd#aljbd_notice')->counts_by_type($_GET['type']);
		}
		if($_GET['order']!=''){
			if($_GET['order']=='view'){
			$bdlist=C::t('#aljbd#aljbd_notice')->get_order_view();
			}else{
			$oder=$_GET['order'];
			$time = time()-3600*24*$oder;
			$bdlist=C::t('#aljbd#aljbd_notice')->find_by_order($time);
			}
		}
		$recommendlist=C::t('#aljbd#aljbd')->fetch_all_by_recommend(1,0,10);
		$typecount=C::t('#aljbd#aljbd_notice')->count_by_type();
		//debug($bdlist);
		foreach($typecount as $tc){
			$tcs[$tc['type']]=$tc['num'];
		}
		$num=count($bdlist);
		$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=shanglist&type='.$_GET['type'].'&subtype='.$_GET['subtype'].'&region='.$_GET['region'].'&subregion='.$_GET['subregion'].'&order='.$_GET['order'].'&kw='.$_GET['kw'].'&view='.$_GET['view'], 0, 11, false, false);
		
	include template('aljbd:shanglist');
	
}else if($_GET['act']=='quanlist'){
		$metakeywords =  $config['keywords'];
		$keyword=$_GET['keyword'];	
		$type1=$_GET['type'];
		$sub=$_GET['subtype'];
		if($type1!=''&&$type1!=0)$name1=C::t('#aljbd#aljbd_type_quan')->fetch_type_name($type1);
		if($sub!='')$name2=C::t('#aljbd#aljbd_type_quan')->fetch_type_name($sub);
		if($type1!=''&&$type1!=0)$nav_name='<em>&rsaquo;</em><a href="plugin.php?id=aljbd&act=pulist&type='.$type1.'">'.$name1.'</a>';
		if($name2!='')$nav_name2='<em>&rsaquo;</em><a href="plugin.php?id=aljbd&act=pulist&type='.$type1.'&subtype='.$sub.'">'.$name2.'</a>';
		
		if($keyword!=''){
			$bdlist=C::t('#aljbd#aljbd_consume')->find_by_like($keyword);
		}else{
			$bdlist=C::t('#aljbd#aljbd_consume')->fetch_all();
			}
		if($_GET['type']&&$_GET['subtype']==""){
			$bdlist=C::t('#aljbd#aljbd_consume')->counts_by_type($_GET['type']);
		}else if($_GET['subtype']){
			$bdlist=C::t('#aljbd#aljbd_consume')->counts_by_subtype($_GET['subtype']);
		}
		$typecount=C::t('#aljbd#aljbd_consume')->count_by_type();
		//debug($bdlist);
		foreach($typecount as $tc){
			$tcs[$tc['type']]=$tc['num'];
		}
		//获取子类消费券数by gaoyan
		if($type1!=''&&$type1!=0){
			$subtypecount=C::t('#aljbd#aljbd_consume')->count_by_subtype_gy();
			foreach($subtypecount as $subtc){
				$subtcs[$subtc['subtype']]=$subtc['num'];
			}
		}
		$recommendlist=C::t('#aljbd#aljbd')->fetch_all_by_recommend(1,0,10);
		$num=count($bdlist);
		$tlist=C::t('#aljbd#aljbd_type_quan')->fetch_all_by_upid(0);
		//debug($bdlist);
		$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=shanglist&type='.$_GET['type'].'&subtype='.$_GET['subtype'].'&region='.$_GET['region'].'&subregion='.$_GET['subregion'].'&order='.$_GET['order'].'&kw='.$_GET['kw'].'&view='.$_GET['view'], 0, 11, false, false);
	include template('aljbd:quanlist');
	
}else if($_GET['act']=='search'){
	$mod=isset($_GET['mod']) ? $_GET['mod'] : $_POST['mod'];
	$name=$_GET['keyword'];
	if($mod=='shop'){
		$bdlist=C::t('#aljbd#aljbd')->find_by_like($name);
		$mod='商家';
	}else if($mod=='goods'){
		$bdlist=C::t('#aljbd#aljbd_goods')->find_by_like($name);
		$mod='商品';
	}
	$num=count($bdlist);
	$recommendlist=C::t('#aljbd#aljbd')->fetch_all_by_recommend(1,0,10);
	//debug($bdlist);
	include template('aljbd:search');
}else if($_GET['act']=='pulist'){
		$navtitle = lang('plugin/aljbd','s44').$config['title'];
		$metakeywords =  $config['keywords'];
		$metadescription = $config['description']; 
		$khf=C::t('#aljbd#aljbd_comment')->count_by_bid($_GET['bid']);
		$typecount=C::t('#aljbd#aljbd')->count_by_type($_GET['renzheng']);
		
		$renzheng=C::t('#aljbd#aljbd')->fetch_all_by_renzheng();
		$type1=$_GET['type'];
		$sub=$_GET['subtype'];
		if($type1!=''&&$type1!=0)$name1=C::t('#aljbd#aljbd_type')->fetch_type_name($type1);
		if($sub!='')$name2=C::t('#aljbd#aljbd_type')->fetch_type_name($sub);
		if($type1!=''&&$type1!=0)$nav_name='<em>&rsaquo;</em><a href="plugin.php?id=aljbd&act=pulist&type='.$type1.'">'.$name1.'</a>';
		if($name2!='')$nav_name2='<em>&rsaquo;</em><a href="plugin.php?id=aljbd&act=pulist&type='.$type1.'&subtype='.$sub.'">'.$name2.'</a>';
		//debug($nav_name2);
		//debug($renzheng);
		foreach($typecount as $tc){
			$tcs[$tc['type']]=$tc['num'];
		}
		//获取子类商家数by gaoyan
		if($type1!=''&&$type1!=0){
			$subtypecount=C::t('#aljbd#aljbd')->count_by_subtype_gy($_GET['renzheng']);
			foreach($subtypecount as $subtc){
				$subtcs[$subtc['subtype']]=$subtc['num'];
			}
		}
		//debug($tcs);
/*
		if($_GET['type']){
			$subtypecount=C::t('#aljbd#aljbd')->count_by_type($_GET['type']);
		}
*/
		$aljbd=C::t('#aljbd#aljbd')->fetch_by_uid($_G['uid']);
		//debug($aljbd);
		$config=$_G['cache']['plugin']['aljbd'];
		$typelist=C::t('#aljbd#aljbd_type')->range();
		$tlist=C::t('#aljbd#aljbd_type')->fetch_all_by_upid(0);
		$rlist=C::t('#aljbd#aljbd_region')->fetch_all_by_upid(0);
		$currpage=$_GET['page']?$_GET['page']:1;
		$perpage=$config['page'];
		if(submitcheck('submit')){
			
			$search=$_GET['kw'];
		
		}
		$num=C::t('#aljbd#aljbd')->count_by_status(1,'',$_GET['type'],$_GET['subtype'],$_GET['region'],$_GET['subregion'],$search,$_GET['renzheng']);
		
		$total_page = ceil($num/$perpage);
	//debug($currpage > 1);
			//第一页的时候没有上一页
		if($total_page>1){
			if($currpage > 1){
				$shangyiye='<a href="plugin.php?id=aljbd&page='.($currpage-1).'&kw='.$_GET['kw'].'">'.lang('plugin/aljbd','sj_3').'</a>&nbsp;&nbsp;';
			}else{
				$shangyiye='<span>'.lang('plugin/aljbd','sj_3').'</span>';
			}
			//尾页的时候不显示下一页
			if($currpage < $total_page){
				//debug(123);
				$xiayiye= '<a href="plugin.php?id=aljbd&page='.($currpage+1).'&kw='.$_GET['kw'].'">'.lang('plugin/aljbd','sj_2').'</a>&nbsp;&nbsp;';
				//debug($xiayiye);
			}else{
				$xiayiye='<span>'.lang('plugin/aljbd','sj_2').'</span>';
			}
		}
		$allpage=ceil($num/$perpage);
		$start=($currpage-1)*$perpage;
		$recommendlist=C::t('#aljbd#aljbd')->fetch_all_by_recommend(1,0,10);
		$recommendlist_goods=C::t('#aljbd#aljbd_goods')->fetch_all_by_recommend(1,0,10);
		if($config['isrewrite']){
			if($_GET['order']=='1'){
				$_GET['order']='view';
			}else if($_GET['order']=='2'){
				$_GET['order']='dateline';
			}else{
				$_GET['order']='';
			}
			if($_GET['view']=='3'){
				$_GET['view']="pic";
			}else if($_GET['view']=='4'){
				$_GET['view']="list";
			}else{
				$_GET['view']='';
			}
		}
/*
		if($_GET['renzheng']==1){
			$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_ren();
		}else if($_GET['ruzhu']==1){
			$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_no_ren();
		}else{
			$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_status(1,$start,$perpage,'',$_GET['type'],$_GET['subtype'],$_GET['region'],$_GET['subregion'],$_GET['order'],$search,$_GET['renzheng']);			
			$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=pulist&type='.$_GET['type'].'&subtype='.$_GET['subtype'].'&region='.$_GET['region'].'&subregion='.$_GET['subregion'].'&order='.$_GET['order'].'&kw='.$_GET['kw'].'&view='.$_GET['view'], 0, 11, false, false);
		}
*/
		//改为全部搜索 by gaoyan
		$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_status(1,$start,$perpage,'',$_GET['type'],$_GET['subtype'],$_GET['region'],$_GET['subregion'],$_GET['order'],$search,$_GET['renzheng']);	

			$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=aljbd&act=pulist&type='.$_GET['type'].'&subtype='.$_GET['subtype'].'&region='.$_GET['region'].'&subregion='.$_GET['subregion'].'&order='.$_GET['order'].'&kw='.$_GET['kw'].'&view='.$_GET['view'].'&renzheng='.$_GET['renzheng'], 0, 11, false, false);
		foreach($bdlist as $k=>$v){
			$bdlist[$k]['c']=C::t('#aljbd#aljbd_comment')->fetch_by_bid($v['id']);
			$bdlist[$k]['q']=str_replace('{qq}',$v['qq'],$config['qq']);
		}//debug($bdlist);
		$notice=C::t('#aljbd#aljbd_notice')->fetch_all_by_uid_bid('','',0,9);

			
		include template('aljbd:list');
	
}else{
		$shanglist=C::t('#aljbd#aljbd_notice')->fetch_limit(5);
		$limit_one=C::t('#aljbd#aljbd')->fetch_limit(1);
		$limit_two=C::t('#aljbd#aljbd')->fetch_limit(2);
		$limit_three=C::t('#aljbd#aljbd')->fetch_limit(3);
		$limit_four=C::t('#aljbd#aljbd')->fetch_limit(4);
		$limit_five=C::t('#aljbd#aljbd')->fetch_limit(5);
		$limit_six=C::t('#aljbd#aljbd')->fetch_limit(6);
		$limit_seven=C::t('#aljbd#aljbd')->fetch_limit(7);
		$limit_fox=C::t('#aljbd#aljbd')->fetch_limit(8);
		$typelist=C::t('#aljbd#aljbd_type')->fetch_all_by_upid(0);

		//var_dump($tcs);
		include template('aljbd:index');
}
function showmsg($msg,$close){
	if($close){
		$str="parent.hideWindow('$close');";
	}else{
		$str="parent.location=parent.location;";
	}
	include template('aljbd:showmsg');
	exit;
}
function showerror($msg){
	include template('aljbd:showerror');
	exit;
}
function img2thumb($src_img, $dst_img, $width = 75, $height = 75, $cut = 0, $proportion = 0)
{
    if(!is_file($src_img))
    {
        return false;
    }
    $ot = fileext($dst_img);
    $otfunc = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);
    $srcinfo = getimagesize($src_img);
    $src_w = $srcinfo[0];
    $src_h = $srcinfo[1];
    $type  = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
    $createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);

    $dst_h = $height;
    $dst_w = $width;
    $x = $y = 0;

    if(($width> $src_w && $height> $src_h) || ($height> $src_h && $width == 0) || ($width> $src_w && $height == 0))
    {
        $proportion = 1;
    }
    if($width> $src_w)
    {
        $dst_w = $width = $src_w;
    }
    if($height> $src_h)
    {
        $dst_h = $height = $src_h;
    }

    if(!$width && !$height && !$proportion)
    {
        return false;
    }
    if(!$proportion)
    {
        if($cut == 0)
        {
            if($dst_w && $dst_h)
            {
                if($dst_w/$src_w> $dst_h/$src_h)
                {
                    $dst_w = $src_w * ($dst_h / $src_h);
                    $x = 0 - ($dst_w - $width) / 2;
                }
                else
                {
                    $dst_h = $src_h * ($dst_w / $src_w);
                    $y = 0 - ($dst_h - $height) / 2;
                }
            }
            else if($dst_w xor $dst_h)
            {
                if($dst_w && !$dst_h)  
                {
                    $propor = $dst_w / $src_w;
                    $height = $dst_h  = $src_h * $propor;
                }
                else if(!$dst_w && $dst_h)  
                {
                    $propor = $dst_h / $src_h;
                    $width  = $dst_w = $src_w * $propor;
                }
            }
        }
        else
        {
            if(!$dst_h)  
            {
                $height = $dst_h = $dst_w;
            }
            if(!$dst_w)  
            {
                $width = $dst_w = $dst_h;
            }
            $propor = min(max($dst_w / $src_w, $dst_h / $src_h), 1);
            $dst_w = (int)round($src_w * $propor);
            $dst_h = (int)round($src_h * $propor);
            $x = ($width - $dst_w) / 2;
            $y = ($height - $dst_h) / 2;
        }
    }
    else
    {
        $proportion = min($proportion, 1);
        $height = $dst_h = $src_h * $proportion;
        $width  = $dst_w = $src_w * $proportion;
    }

    $src = $createfun($src_img);
    $dst = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefill($dst, 0, 0, $white);

    if(function_exists('imagecopyresampled'))
    {
        imagecopyresampled($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
    }
    else
    {
        imagecopyresized($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
    }
    $otfunc($dst, $dst_img);
    imagedestroy($dst);
    imagedestroy($src);
    return true;
}
?>