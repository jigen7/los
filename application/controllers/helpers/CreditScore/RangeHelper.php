<?php

class Zend_Controller_Action_Helper_RangeHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($formData)
	{	
		$field = ""; $size = -1;
		$arrData = array();
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		foreach($formData['rfrow'] as $fkey => $fvalue){
			$mnrow = $mnmodel->getRow($fkey);
			$field = $mnrow->namefield;
			foreach($formData['rtrow'] as $tkey => $tvalue){
				if($fkey == $tkey) 
					$arrData[] = array('field' => $field,'rfrom' => $fvalue, 'rto' => $tvalue);
			}
			$size++;	
		}
		//print_r($arrData);
		if($size == -1){
			return true;	
		}else if($size == 0){
			return ($arrData[0]['rfrom'] < $arrData[0]['rto'])? true : false;
		}else{
			for($i = 0; $i<$size; $i++){
				if($arrData[$i]['rfrom'] > $arrData[$i]['rto']) return false;	
				if($arrData[$i]['rfrom'] == $arrData[$i]['rto']) return false;			
				$inc = $i + 1;
				if($arrData[$inc]['field'] == $arrData[$i]['field'] && 
				   $arrData[$inc]['rfrom'] != $arrData[$i]['rto']) return false;
			}
			return ($arrData[$size]['rfrom'] < $arrData[$size]['rto'])? true : false;			
		}
    }

}
