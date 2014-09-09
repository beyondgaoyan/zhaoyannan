<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_aljbd_type_quan extends discuz_table{
	public function __construct() {

			$this->_table = 'aljbd_type_quan';
			$this->_pk    = 'id';

			parent::__construct();
	}
	public function fetch_upid_by_id($id){
		return DB::result_first('SELECT upid FROM %t WHERE id=%d',array($this->_table,$id));
	}
	public function fetch_subid_by_id($id){
		return DB::result_first('SELECT subid FROM %t WHERE id=%d',array($this->_table,$id));
	}
	public function fetch_all_by_upid($upid){
		return DB::fetch_all('SELECT * FROM %t WHERE upid=%d ORDER BY displayorder ASC',array($this->_table,$upid),'id');
	}
	public function fetch_all_by_type($type){
		return DB::fetch_all('select * from %t where id=%d ORDER BY displayorder ASC',array($this->_table,$type));
	}
	public function fetch_type_name($type){
		return DB::result_first('SELECT subject FROM %t WHERE id=%d',array($this->_table,$type));	
	}
}




?>