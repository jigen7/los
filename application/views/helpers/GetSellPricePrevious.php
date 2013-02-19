<?php
class Zend_View_Helper_GetSellPricePrevious extends Zend_Controller_Action_Helper_Abstract
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
	function getSellPricePrevious($targetMonth,$targetYear,$process,$id){

	$table = new Model_ChainVehicle();
	$select = $table->select();
	$select->where('id ='.$id);	
	$detail = $table->fetchRow($select);


	
	if($process == 'previous'){
	$select = $table->select();
	if($detail->dealer){
	$select->where('dealer like ?',$detail->dealer);
	}
	$select->where('brand like ?',$detail->brand);
	$select->where('unit like ?',$detail->unit);
	$select->where('year like ?',$targetYear.'');
	$select->where('month like ?',$targetMonth.'');	
	$select->where('status like ?','approved');	
	$detail2 = $table->fetchRow($select);
	return $detail2->selling_price;
	}
	/******************************************/
	else if($process == 'colorMain'){
		$select = $table->select();
		if($detail->dealer){
		$select->where('dealer like ?',$detail->dealer);
		}
		$targetYear = returnYearx($detail->month,$detail->year);
		$targetMonth = returnMonthx($detail->month);
		
		$select->where('brand like ?',$detail->brand);
		$select->where('unit like ?',$detail->unit);
		$select->where('year like ?',$targetYear.'');
		$select->where('month like ?',$targetMonth.'');	
		$select->where('status like ?','approved');	
		$detail2 = $table->fetchRow($select);
	
	if($detail2){
		if($detail->selling_price != 0){
			if($detail->selling_price < $detail2->selling_price){
				return '#CC0000'; /** Decrease **/ }
			else if($detail->selling_price > $detail2->selling_price){
				return '#0000FF'; /** Increase **/ }
			else if($detail->selling_price == $detail2->selling_price){
				return '#FFFF00'; /** No Change **/ }			
		}// end of seeling price !=0
		else {
			return '#999966'; /** Drop Out **/ }
		}	
	else{ return '#66CC00'; /** Not Found New **/ }


	} // end of colorMain
/******************************************/
	else if($process == 'colorSub'){
		$select = $table->select();
		if($detail->dealer){
		$select->where('dealer like ?',$detail->dealer);
		}
		$select->where('brand like ?',$detail->brand);
		$select->where('unit like ?',$detail->unit);
		$select->where('month like ?',$targetMonth.'');
		$select->where('year like ?',$targetYear.'');
		$select->where('status like ?','approved');			
		$detailCurr = $table->fetchRow($select);

		$select2 = $table->select();
		if($detail->dealer){
		$select2->where('dealer like ?',$detail->dealer);
		}
		$targetYearZ = returnYearx($targetMonth,$targetYear);
		$targetMonthZ = returnMonthx($targetMonth);

		$select2->where('brand like ?',$detail->brand);
		$select2->where('unit like ?',$detail->unit);
		$select2->where('month like ?',$targetMonthZ.'');
		$select2->where('year like ?',$targetYearZ.'');
		$select2->where('status like ?','approved');			
		$detailPrev = $table->fetchRow($select2);
		

		if($detailPrev){
			if($detailCurr->selling_price != 0){
				if($detailCurr->selling_price < $detailPrev->selling_price){
					return '#CC0000'; /** Decrease **/ }
				else if($detailCurr->selling_price > $detailPrev->selling_price){
					return '#0000FF'; /** Increase **/ }
				else if($detailCurr->selling_price == $detailPrev->selling_price){
					return '#FFFF00'; /** No Change **/ }			
			}// end of seeling price !=0
			else {
				return '#999966'; /** Drop Out **/ }
			}	
		else{ return '#66CC00'; /** Not Found New **/ }
	

	}// end of if process color 	
	
	

/******************************************/
	
	 	
	}
	
	
}// end of function

function returnMonthx($int){
	
	if ($int == 1){
	return 12;	
	}
	else {		
	return $int - 1;	
	} 
}

function returnYearx($month,$year){
	
	if($month == 1){
		
		return $year-1;
	}
	else{
		
		return $year;
	} 
	
}

?>