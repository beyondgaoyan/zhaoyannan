<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_aljbd extends discuz_table{
	public function __construct() {

			$this->_table = 'aljbd';
			$this->_pk    = 'id';

			parent::__construct();
	}
	public function count_by_status($status,$uid,$type,$subtype,$region,$subregion,$search,$renzheng){
		$con[]=$this->_table;
		$where='where 1 ';
		//增加认证 by gaoyan
		if($renzheng){
			$con[]=$renzheng;
			$where.=' and renzheng=%d ';
		}
		if($uid){
			$con[]=$uid;
			$where.='and uid=%d';
		}
		if($status||$status=='0'){
			$con[]=$status;
			$where.=' and status=%d';
		}
		if($type){
			$con[]=$type;
			$where.=' and type=%d';
		}
		if($subtype){
			$con[]=$subtype;
			$where.=' and subtype=%d';
		}
		if($region){
			$con[]=$region;
			$where.=' and region=%d';
		}
		if($subregion){
			$con[]=$subregion;
			$where.=' and subregion=%d';
		}
		
		if($search){
			$con[]='%'.addcslashes($search, '%_').'%';
			
			$where.=" and name like %s";
		}//debug('select count(*) from %t '.$where);
		return DB::result_first('select count(*) from %t '.$where,$con);
	}
	public function fetch_all_by_recommend($recommend,$start,$perpage){
		return DB::fetch_all('select * from %t where recommend=%d ORDER BY RAND() limit %d,%d',array($this->_table,$recommend,$start,$perpage));
	}
	public function fetch_all_by_renzheng($id){
		return DB::fetch_first('select renzheng from %t where id=%d ',array($this->_table,$id));
	}
	public function fetch_all_by_ren(){
		return DB::fetch_all('select * from %t where renzheng=1 order by dateline desc',array($this->_table));
	}
	public function fetch_all_by_ren_zheng($uid,$limit){
		return DB::fetch_all('select * from %t where renzheng=1 and uid=%d order by dateline desc limit 0,%d',array($this->_table,$uid,$limit));
	}
	public function fetch_all_by_ren_shout($uid,$limit){
		return DB::fetch_all('select * from %t where shout=1 and type=%d order by dateline desc limit 0,%d',array($this->_table,$uid,$limit));
	}
	public function fetch_all_by_no_ren(){
		return DB::fetch_all('select * from %t where renzheng!=1',array($this->_table));
	}
	public function fetch_all_by_status($status,$start,$perpage,$uid,$type,$subtype,$region,$subregion,$order,$search,$renzheng){
		$con[]=$this->_table;
		$where='where 1 ';
		//增加认证 by gaoyan
		if($renzheng){
			$con[]=$renzheng;
			$where.=' and renzheng=%d ';
		}
		if($status){
			$con[]=$status;
			$where.='and status=%d';
		}
		if($uid){
			$con[]=$uid;
			$where.=' and uid=%d';
		}
		if($type){
			$con[]=$type;
			$where.=' and type=%d';
		}
		if($subtype){
			$con[]=$subtype;
			$where.=' and subtype=%d';
		}
		if($region){
			$con[]=$region;
			$where.=' and region=%d';
		}
		if($subregion){
			$con[]=$subregion;
			$where.=' and subregion=%d';
		}
		if($search){
			$con[]='%'.addcslashes($search, '%_').'%';
			
			$where.=" and name like %s";
		}
		if($order){
			$where.=' order by '.addslashes($order).' desc';
		}else{
			$where.=' order by displayorder asc,dateline desc,comment desc,view desc';
		}
		if(!empty($perpage)){
			$con[]=$start;
			$con[]=$perpage;
			$where.=' limit %d,%d';
		}

		return DB::fetch_all('select * from %t '.$where,$con);
	}
	public function update_status_by_id($id,$status){
		return DB::query('update %t set status=%d where id=%d',array($this->_table,$status,$id));
	}
	public function update_shout_time($time,$bid){
		return DB::query('update %t set dateline=%d where id=%d',array($this->_table,$time,$bid));
	}
	
	public function update_displayorder_by_id($id,$displayorder){
		return DB::query('update %t set displayorder=%d where id=%d',array($this->_table,$displayorder,$id));
	}
	public function update_recommend_by_id($id,$recommend){
		return DB::query('update %t set recommend=%d where id=%d',array($this->_table,$recommend,$id));
	}
	public function update_renzheng_by_id($id,$renzheng){
		return DB::query('update %t set renzheng=%d where id=%d',array($this->_table,$renzheng,$id));
	}
	public function update_shout_by_id($id,$shout){
		return DB::query('update %t set shout=%d where id=%d and renzheng=1',array($this->_table,$shout,$id));
	}
	public function update_yshout_by_id($id,$yshout){
		return DB::query('update %t set yshout=%d where id=%d',array($this->_table,$yshout,$id));
	}
	public function fetch_by_uid($uid){
		return DB::fetch_first('select * from %t where uid=%d',array($this->_table,$uid));
	}
	public function fetch_by_bid($id,$uid){
		return DB::fetch_first('select * from %t where id=%d and type=%d limit 0,2 ',array($this->_table,$id,$uid));
	}
	public function fetch_by_id($id,$limit){
		return DB::fetch_all('select * from %t where id=%d limit 0,%d ',array($this->_table,$id,$limit));
	}	
	public function update_view_by_bid($bid){
		return DB::query('update %t set view=view+1 where id=%d',array($this->_table,$bid));
	}
	public function update_comment_by_bid($bid){
		return DB::query('update %t set comment=comment+1 where id=%d',array($this->_table,$bid));
	}
	public function count_by_type($renzheng){
		$con[]=$this->_table;
		$where='where 1 ';
		//增加认证 by gaoyan
		if(isset($renzheng)){
			$con[]=$renzheng;
			$where.=' and renzheng=%d ';
		}
		$where.=' and status=1 group by type';
		return DB::fetch_all('select type,count(*) num from %t '.$where,$con);
	}
	//获取子类商家数 by gaoyan
	public function count_by_subtype_gy($renzheng){
	$con[]=$this->_table;
		$where='where 1 ';
		//增加认证 by gaoyan
		if(isset($renzheng)){
			$con[]=$renzheng;
			$where.=' and renzheng=%d ';
		}
		$where.=' and status=1 group by subtype';
		return DB::fetch_all('select subtype,count(*) num from %t '.$where,$con);
	}
	public function count_by_subtype($type){
		return DB::fetch_all('select count(*) num from %t where status=1 and subtype=%d',array($this->_table,$type));
	}
	public function count_by_region(){
		return DB::fetch_all('select region,count(*) num from %t where status=1 group by region',array($this->_table));
	}
	public function search_by_subject($subject){
		return DB::fetch_all('select region,count(*) num from %t where status=1 group by region',array($this->_table));
	}
	public function fetch_thread_all_block($con,$sc,$items){
		return DB::fetch_all("select * from %t $con $sc limit 0,%d",array($this->_table,$items));
	}
	public function fetch_limit($lit1){
		return DB::fetch_all('select * from %t where type=%d and yshout=1 ORDER BY dateline desc limit 0,9',array($this->_table,$lit1));
	}
	public function find_by_like($like){
	if($like!=''){
		$query="select * from pre_aljbd where name like '%".$like."%'";
		return DB::fetch_all($query);
		}
	}
}




?>