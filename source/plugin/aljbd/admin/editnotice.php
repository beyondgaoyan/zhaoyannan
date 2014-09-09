<?php
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
				$pic1 = "source/plugin/aljbd/images/notice/". $pics;
				if(@copy($_FILES['pic1']['tmp_name'], $pic1)||@move_uploaded_file($_FILES['pic1']['tmp_name'], $pic1)){
					$imageinfo=getimagesize($pic1);
					$w60=$imageinfo[0]<100?$imageinfo[0]:100;
					$h60=$imageinfo[1]<100?$imageinfo[1]:100;
					img2thumb($pic1,$pic1.'.100x100.jpg',$w60,$h60);
					@unlink($_FILES['pic1']['tmp_name']);
				}
			}
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
				$logo = "source/plugin/aljbd/images/consume/". $pics;
				if(@copy($_FILES['logo']['tmp_name'], $logo)||@move_uploaded_file($_FILES['logo']['tmp_name'], $logo)){
					@unlink($_FILES['logo']['tmp_name']);
				}
			}
		}
		$updatearray=array(
			'bid'=>$_GET['bid'],
			'username'=>$_G['username'],
			'subject'=>$_GET['subject'],
			'content'=>$_GET['intro'],
			'dateline'=>TIMESTAMP,
		);
		if($logo){
			$updatearray['pic']=$logo;
		}
		if($pic1){
			$updatearray['pic1']=$pic1;
		}
		C::t('#aljbd#aljbd_notice')->update($_GET['nid'],$updatearray);
		showmsg(lang('plugin/aljbd','s54'));
	}else{
		$bdlist=C::t('#aljbd#aljbd')->fetch_all_by_status(1,'','',$_G['uid']);
		$n=C::t('#aljbd#aljbd_notice')->fetch($_GET['nid']);
		include template('aljbd:editnotice');
	}

?>