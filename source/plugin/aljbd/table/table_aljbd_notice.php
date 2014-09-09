<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_aljbd_notice extends discuz_table{
	public function __construct() {

			$this->_table = 'aljbd_notice';
			$this->_pk    = 'id';

			parent::__construct();
	}
	public function count_by_uid_bid($uid,$bid){
		$conn=' where 1';
		$where[]=$this->_table;
		if($uid){
			$where[]=$uid;
			$conn.=' and uid=%d';
		}
		if($bid){
			$where[]=$bid;
			$conn.=' and bid=%d';
		}
		return DB::result_first('select count(*) from %t '.$conn,$where);
	}
	public function fetch_all_by_uid_bid($uid,$bid,$start,$perpage){
		$conn=' where 1';
		$where[]=$this->_table;
		if($uid){
			$where[]=$uid;
			$conn.=' and uid=%d';
		}
		if($bid){
			$where[]=$bid;
			$conn.=' and bid=%d';
		}
		$conn.=' order by id desc';
		if(isset($start)&&isset($perpage)){
			$where[]=$start;
			$where[]=$perpage;
			$conn.=' limit %d,%d';
		}
		return DB::fetch_all('select * from %t '.$conn,$where);
	}
	public function fetch_all_by_uid_bid_view($uid,$bid,$start,$perpage){
		$conn=' where 1';
		$where[]=$this->_table;
		if($uid){
			$where[]=$uid;
			$conn.=' and uid=%d';
		}
		if($bid){
			$where[]=$bid;
			$conn.=' and bid=%d';
		}
		$conn.=' order by view desc';
		if(isset($start)&&isset($perpage)){
			$where[]=$start;
			$where[]=$perpage;
			$conn.=' limit %d,%d';
		}
		return DB::fetch_all('select * from %t '.$conn,$where);
	}
	public function get_order_view(){
		return DB::fetch_all("select * from %t group by view desc",array($this->_table));
	}
	public function update_view_by_gid($gid){
		return DB::query('update %t set view=view+1 where id=%d',array($this->_table,$gid));
	}
	public function fetch_thread_all_block($con,$sc,$items){
		return DB::fetch_all("select * from %t $con $sc limit 0,%d",array($this->_table,$items));
	}
	public function fetch_all(){
		return DB::fetch_all("select * from %t order by dateline desc",array($this->_table));
	}	
	public function fetch_limit($limit){
		return DB::fetch_all("select * from %t limit 0,%d" ,array($this->_table,$limit));
	}
	public function count_by_type(){
		return DB::fetch_all('select type,count(*) num from %t group by type',array($this->_table));
	}
	public function counts_by_type($type){
		return DB::fetch_all('select * from %t where type=%d',array($this->_table,$type));
	}
	public function find_by_order($or){
		return DB::fetch_all('select * from %t where dateline>=%d',array($this->_table,$or));
	}
	public function find_by_like($like){
	if($like!=''){
		$query="select * from pre_aljbd_notice where subject like '%".$like."%'";
		return DB::fetch_all($query);
		}
	}
}




?>