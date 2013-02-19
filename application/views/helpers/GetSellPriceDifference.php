<?php
class Zend_View_Helper_GetSellPriceDifference extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
	/**
	 * Paolo Marco Manarang
	 * View Helper use in PriceList function 
	 * March 29 , 2010
	 * 
	 * @return 
	 */
	function getSellPriceDifference($id,$process){

	$table = new Model_ChainVehicle();
	$select = $table->select();
	$select->where('id ='.$id);	
	$detail = $table->fetchRow($select);

	$returnMonth = returnMonth($detail->month);
	$returnYear = returnYear($detail->month,$detail->year);
	$select = $table->select();
	if($detail->dealer){
	$select->where('dealer like ?',$detail->dealer);
	}
	$select->where('brand like ?',$detail->brand);
	$select->where('unit like ?',$detail->unit);
	$select->where('year like ?',$returnYear.'');
	$select->where('month like ?',$returnMonth.'');	
	$detail2 = $table->fetchRow($select);
	
	if($process == 'color'){
	if($detail2){
		if($detail->selling_price != $detail2->selling_price){
			return '#FF3300'; /** Not Equal **/ }
		else { return '#66FF33'; /**No Changes**/	}
	} // end of if exist 
	else{ return '#FFFF66'; /** Not Found New **/ }
	}// end of if process color 
		
	else if($process == 'history'){
	if($detail2){
		if($detail->selling_price != $detail2->selling_price){
			return 'P'.$detail2->selling_price; /** Not Equal **/ }
		else { return 'No Changes'; /**No Changes**/	}
		} // end of if exist 
	else{ return 'New Unit'; /** Not Found New **/ }
	}// end of history
	
	else if($process == 'previousPrice'){
	if($detail2){
		if($detail->selling_price != $detail2->selling_price){
			return 'P'.$detail2->selling_price; /** Not Equal **/ }
		else { return 'No Changes'; /**No Changes**/	}
		} // end of if exist 
	else{ return 'Not Found'; /** Not Found New **/ }
	}// end of history	
	
	
	
	
	
	
	
	else if($process == 'monthyear'){
		if($detail2){
		if($detail->selling_price != $detail2->selling_price){
			return $returnMonth.'-'.$returnYear; /** Not Equal **/ }
		else { return $returnMonth.'-'.$returnYear; /**No Changes**/	}
		} // end of if exist 
		else{ return 'New Unit'; /** Not Found New **/ }
	}// end of history
	
	} 
	
	
}// end of function

function returnMonth($int){
	
	if ($int == 1){
	return 12;	
	}
	else {		
	return $int - 1;	
	} 
}

function returnYear($month,$year){
	
	if($month == 1){
		
		return $year-1;
	}
	else{
		
		return $year;
	} 
	
}
	

?>