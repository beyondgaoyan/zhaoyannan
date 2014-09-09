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

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

// ======== 换配色修改此处代码 =========

$txtcolor = '#278D32'; //#f30
$bgimga = 'v2-hd.png'; //v2-hd1.png
$bgimgd = 'v2-mnm.png'; //v2-mnm1.png

// =====================================

function m_lang($f) {	
$m_lang = array(
	'qq' => 'QQ登录',
	'reply' => '回帖',
	'views' => '看贴',
	'mods' => '管理',
	'thread' => '社区资讯',
	'forum' => '版块列表',
	'nomessage' => '无内容',
	'mthread' => '帖子',
	'mforum' => '版块',
	'profile' => '个人资料',	
	'topnow' => '头条',
	'cldate' => '日期格式:2010-12-01 10:50',
	
	'type01' => '新闻',
	'type02' => '时尚',
	'type03' => '休闲',
	'type021' => '<a href="#">分类</a>',
	'type022' => '<a href="#">分类</a>',
	'type023' => '<a href="#">分类</a>',
	'type024' => '<a href="#">分类</a>',	
	'type031' => '<a href="#">分类</a>',
	'type032' => '<a href="#">分类</a>',
	'type033' => '<a href="#">分类</a>',
	'type034' => '<a href="#">分类</a>',	
	
	'type11' => '旅游',
	'type12' => '时尚',
	'type13' => '休闲',
	'type121' => '<a href="#">分类</a>',
	'type122' => '<a href="#">分类</a>',
	'type123' => '<a href="#">分类</a>',
	'type124' => '<a href="#">分类</a>',	
	'type131' => '<a href="#">分类</a>',
	'type132' => '<a href="#">分类</a>',
	'type133' => '<a href="#">分类</a>',
	'type134' => '<a href="#">分类</a>',	
	
	'type21' => '运动',
	'type22' => '时尚',
	'type23' => '休闲',
	'type221' => '<a href="#">分类</a>',
	'type222' => '<a href="#">分类</a>',
	'type223' => '<a href="#">分类</a>',
	'type224' => '<a href="#">分类</a>',	
	'type231' => '<a href="#">分类</a>',
	'type232' => '<a href="#">分类</a>',
	'type233' => '<a href="#">分类</a>',
	'type234' => '<a href="#">分类</a>',	
	
	'type31' => '生活',
	'type32' => '时尚',
	'type33' => '休闲',
	'type321' => '<a href="#">分类</a>',
	'type322' => '<a href="#">分类</a>',
	'type323' => '<a href="#">分类</a>',
	'type324' => '<a href="#">分类</a>',	
	'type331' => '<a href="#">分类</a>',
	'type332' => '<a href="#">分类</a>',
	'type333' => '<a href="#">分类</a>',
	'type334' => '<a href="#">分类</a>',	
	
	'type41' => '交友',
	'type42' => '时尚',
	'type43' => '休闲',
	'type421' => '<a href="#">分类</a>',
	'type422' => '<a href="#">分类</a>',
	'type423' => '<a href="#">分类</a>',
	'type424' => '<a href="#">分类</a>',	
	'type431' => '<a href="#">分类</a>',
	'type432' => '<a href="#">分类</a>',
	'type433' => '<a href="#">分类</a>',
	'type434' => '<a href="#">分类</a>',
			
    );
	if($m_lang[$f]) $f = $m_lang[$f]; 
	if(CHARSET =='gbk'){
		return $f;
	}else{
		return diconv($f,'GBK',CHARSET);
	}
}

?>