<?php
class Zend_View_Helper_GetMemberUsername extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){$this->_view = $view;}

	function getMemberUsername($group){
	
		//Crecom / SubCrecom
		$users = new Model_Users();
		
		if($group == 'crecom'){
			$table = new Model_AutoRouting_CrecomConfig();
			//return $table->getMembers();
			
			$arr = array();
			$counter = 0;
			foreach($table->getMembers() as $x){
			$arr[$counter]['role']= $x->role;
			$arr[$counter]['username']= $users->returnApproverbyRole($x->role);
			$counter++;
			}
			return $arr;
		}else if($group =='subcrecom'){
			$table = new Model_AutoRouting_SubCrecomConfig();
		}
	}


}
?>