<?php
class Zend_View_Helper_GetRoleUsername extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){$this->_view = $view;}

	function getRoleUsername($role){
	
		$table = new Model_Users();
		
		return $table->returnUsersbyRole($role);
	}

	





}
?>