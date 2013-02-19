<?php
class Zend_View_Helper_ViewBusCount extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewBusCount($capno){

	$bus= new Model_BorrowerBusiness();
	$select = $bus->select();
	$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
	//$select->where('capno like ?',$capno);
	$buscount = $bus->fetchAll($select)->count();

	return $buscount;
	
   }
  }


?>