<?php
class Zend_View_Helper_JsonConvert extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}

	function jsonConvert(){
		
		$table = new Model_ChainAddress();
		
		$select = $table->select();
		$detail = $table->fetchAll($select);
		$data=array();
		foreach($detail as $x){
		$data[] = $x->brgy;	
		}
		$json = Zend_Json::encode($data);	
		
		return $json;
		
	}
	
}






?>