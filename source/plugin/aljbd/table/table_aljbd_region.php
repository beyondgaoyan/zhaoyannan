<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_aljbd_region extends discuz_table{
	public function __construct() {

			$this->_table = 'aljbd_region';
			$this->_pk    = 'catid';

			parent::__construct();
	}
	public function fetch_all_by_upid($start,$limit,$upid){
		$carray[]=$this->_table;
		if($upid){
			$carray[]=$upid;
			$conn=' where upid=%d';
		}else{
			$conn=' where upid=0';
		}
		if($start&&$limit){
			$carray[]=$start;
			$carray[]=$limit;
			$conn.='limit %d,%d';
		}
		return DB::fetch_all('select * from %t '.$conn,$carray,'catid');
	}

}




?>