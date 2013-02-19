<?php
class Zend_View_Helper_CountSpoCo extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function countSpoCo($capno){

		$tables = new Model_BorrowerAccount();
/*		
		$sql = $tables->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Coborrower')
		->order("capno ASC");
		
		$countx = $tables->fetchAll($sql)->count();
*/		
		$sql = $tables->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Spouse');
		$county = $tables->fetchAll($sql)->count();

		//return $countx+$county;
		return $county;
		
		
	
	}
}
	

?>