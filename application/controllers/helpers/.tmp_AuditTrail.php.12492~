<?php

class Zend_Controller_Action_Helper_AuditTrail extends
                Zend_Controller_Action_Helper_Abstract
{
    function direct($tab1,$tab2,$capno)
    {
	//$tab2 = $tab2->toArray();
    $result = array();
    $audit = new Model_AuditTrail();
	foreach($tab1 as $column => $values) {
	    foreach($tab2 as $column2 => $values2) {

	//if(! in_array($values, $tab2)) $result[] = $values;
    	if($column == $column2){
    		if($values != $values2){
    		$result[] = $column.' '.$values.' => '.$values2.' = Changes Made';	
			
			$data = array(
			'capno' => $capno,
			'values' => $column,
			'from'=> $values,
			'to'=> $values2,
			'change_by'=>login_user(),
			'date_change'=>date("r"),
			);
			$audit->insert($data);
			}
			
			else{
			$result[] = $column.' '.$values.' => '.$values2.' = No Changes Made';	
			}
			
    	}
	}
	}
	return $result;

	}
	
	function add($tab1,$capno){

	    $audit = new Model_AuditTrail();

		foreach($tab1 as $key => $values) {
			$data = array(
			'capno' => $capno,
			'values' => $key,
			'from'=> 'Added',
			'to'=> $values,
			'change_by'=>login_user(),
			'date_change'=>date("r"),
			);
			$audit->insert($data);
			}	

		}
		
	function addComaker($tab1,$capno){

	    $audit = new Model_AuditTrail();

		foreach($tab1 as $key => $values) {
			$data = array(
			'capno' => $capno,
			'values' => $key,
			'from'=> 'Added Co-Maker',
			'to'=> $values,
			'change_by'=>login_user(),
			'date_change'=>date("r"),
			);
			$audit->insert($data);
			}	
	}
		
		
	
	
	function delete($tab1,$capno){

		$audit = new Model_AuditTrail();

		foreach($tab1 as $key => $values) {
			$data = array(
			'capno' => $capno,
			'values' => $key,
			'from'=> $values,
			'to'=> 'Deleted',
			'change_by'=>login_user(),
			'date_change'=>date("r"),
			);
			$audit->insert($data);
			}		

	}
	

}

	