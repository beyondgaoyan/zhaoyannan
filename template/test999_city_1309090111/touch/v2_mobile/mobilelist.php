<?php

/*
 * 
 * 威兔是什么? 一只兔子? NO! 我们不这么认为!
 * 我们是谁? 我们是一家工作室, 主要从事网站设计, Discuz模板制作, WordPress企业项目, PHP+Mysql应用开发及相关外包服务.
 * 我们致力于为每一位用户, 打造独立个性的产品, 提升用户体验, 让你的网站更加的接近 Web 2.0 标准.
 *
 * 手机: 182-3270-3356  150-7679-6160
 * QQ:  32-77558-32  2-292-191-585
 * Email: 327755832@qq.com
 * Http://www.v2my.com
 *
 * 工作时间：周一至周六上午8:30-11:30、下午1:00-5:00、晚上8:00-10:00(业务咨询时间)，周日需提前预约
 * 网站管理在线时间：每工作日上午9:30-10:00、下午2:00-3:00、晚上9:00-10:00 
 *
 * 做 discuz , wordpress 模板到威兔网 www.v2my.com 
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