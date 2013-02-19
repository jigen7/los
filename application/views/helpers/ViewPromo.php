<?php
class Zend_View_Helper_ViewPromo extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewPromo($seq){

	$table = new Model_Promo();
	$select = $table->select();
	$select->where('id = ?',$seq);
	$row = $table->fetchRow($select);
	return $row->promo_name;
	}
	
}
?>