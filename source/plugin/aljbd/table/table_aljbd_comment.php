<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_aljbd_comment extends discuz_table{
	public function __construct() {

			$this->_table = 'aljbd_comment';
			$this->_pk    = 'id';

			parent::__construct();
	}
	public function fetch_by_bid($bid){
		return DB::fetch_first('select * from %t where bid=%d and upid=0 and uid!=0',array($this->_table,$bid));
	}
	public function count_by_bid_upid($bid,$upid,$ask){
		return DB::result_first('select count(*) from %t where bid=%d and upid=%d and ask=%d and uid!=0',array($this->_table,$bid,$upid,$ask));
	}
	public function fetch_all_by_bid_upid($bid,$upid,$ask){
		return DB::fetch_all('select * from %t where bid=%d and upid=%d and ask=%d and uid!=0 order by id desc',array($this->_table,$bid,$upid,$ask));
	}
	public function fetch_all_by_index($uid){
		return DB::fetch_all('select distinct bid,content,h from %t where uid!=0 group by bid order by dateline desc',array($this->_table,$uid));
	}
	public function fetch_all_by_upid($upid){
		return DB::fetch_all('select * from %t where upid=%d order by id desc',array($this->_table,$upid));
	}
	public function count_by_bid($bid){
		return DB::fetch_all('select avg(k) k,avg(h) h,avg(f) f from %t where bid=%d and ask=0',array($this->_table,$bid));
	}
	public function count_avg_by_bid($bid){
		return DB::result_first('select (avg(h)+avg(f))/2 from %t where bid=%d and ask=0 and uid!=0',array($this->_table,$bid));
	}
	public function count_by_bid_all($bid){
		return DB::result_first('select count(*) from %t where bid=%d',array($this->_table,$bid));
	}
	public function fetch_all_by_bid_page($bid,$start,$perpage){
		return DB::fetch_all('select * from %t where bid=%d order by id desc limit %d,%d ',array($this->_table,$bid,$start,$perpage));
	}
	public function count_shop_by_bid($bid){
		return DB::result_first('select count(id) from %t where bid=%d',array($this->_table,$bid));
	}	
	//调用指定数量的评论by gaoyan
	public function fetch_num_by_index($bid,$limit){
	return DB::fetch_all('SELECT distinct c.bid,c.content,c.h,a.id,a.name,c.uid FROM %t c LEFT JOIN %t a ON a.id=c.bid where c.uid!=0 and a.type=%b group by c.bid order by c.dateline desc limit 0,%d', array($this->_table, 'aljbd', $bid,$limit));
	}

}




?>