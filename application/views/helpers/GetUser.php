<?php
class Zend_View_Helper_GetUser extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getUser($role){
		//*** return the specified user per role
		
		 	$table = new Model_Users();
			$select = $table->select();
			$select->where('role_type like ?',$role);
			$detail = $table->fetchRow($select);
			
			return $detail->username;
			/**
			**Pattern for random 
			$codetail = $co->fetchAll($select);
			$coArray = array();
			foreach($codetail as $detail) {
			array_push($coArray, $detail->username);
			}
			$coRand = array_rand($coArray);
			return $coArray[$coRand];
			 * */
			
	
	}
}
	

?>