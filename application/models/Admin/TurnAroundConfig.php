<?php

class Model_Admin_TurnAroundConfig extends Zend_Db_Table_Abstract{
	
	protected $_name = 'admin.turn_around_config';
	
	public function getWorkingTime($c){
		$select = $this->select();
		$select->WHERE("name = ?",$c);
		return $this->fetchRow($select);
	}
	
	
}
