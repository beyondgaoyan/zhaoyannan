<?php

/*
 * 
 * ������ʲô? һֻ����? NO! ���ǲ���ô��Ϊ!
 * ������˭? ������һ�ҹ�����, ��Ҫ������վ���, Discuzģ������, WordPress��ҵ��Ŀ, PHP+MysqlӦ�ÿ���������������.
 * ����������Ϊÿһλ�û�, ����������ԵĲ�Ʒ, �����û�����, �������վ���ӵĽӽ� Web 2.0 ��׼.
 *
 * �ֻ�: 182-3270-3356  150-7679-6160
 * QQ:  32-77558-32  2-292-191-585
 * Email: 327755832@qq.com
 * Http://www.v2my.com
 *
 * ����ʱ�䣺��һ����������8:30-11:30������1:00-5:00������8:00-10:00(ҵ����ѯʱ��)����������ǰԤԼ
 * ��վ��������ʱ�䣺ÿ����������9:30-10:00������2:00-3:00������9:00-10:00 
 *
 * �� discuz , wordpress ģ�嵽������ www.v2my.com 
 *
 */

function forumpost($fid,$num,$orderby,$messagenum,$typeid=0,$sortid=0,$daynum=0){
	$manylist=array();
	require_once libfile('function/post');
	if($typeid){
		$typeadd=" and t.typeid in ($typeid)";
	}
	if($sortid){
		$sortadd=" and t.sortid in ($sortid)";
	}
	if($daynum){
		$time=time()-$daynum*86400;
		$replyadd=" and t.dateline>=$time ";
	}
	$rs=DB::query("
SELECT t.*,p.message,p.pid,f.name
FROM ".DB::table("forum_thread")." t 
LEFT JOIN ".DB::table("forum_post")." p on p.tid=t.tid 
LEFT JOIN ".DB::table("forum_forum")." f on f.fid=t.fid
WHERE t.`fid` in ($fid) $typeadd $sortadd and t.displayorder>=0 $replyadd and p.first=1 
group by t.tid 
ORDER BY t.`$orderby` DESC
LIMIT 0 , $num");
	while ($rw=DB::fetch($rs)) {
		$rw['lastposterenc']=urlencode($rw['lastposter']);
		$rw['message']=messagecutstr($rw['message'],$messagenum,'');
		$rw['message']=dhtmlspecialchars($rw['message']);
		$manylist[]=$rw;
	}
	return $manylist;
}

?>