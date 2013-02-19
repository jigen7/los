<?php

class Zend_Controller_Action_Helper_UpdateStatusHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct()
	{	
		$csmodel = new Model_Creditscore_CSModel();
		$cstable = $csmodel->fetchAll();
		foreach($cstable as $csrow){
			$dateTo = $csrow->vpto;
			$dateFrom = $csrow->vpfrom;		
			if(strtotime($dateFrom) <= strtotime(date("Y-m-d"))){
				if($csrow->status == 'APPROVED'){
					$csmodel->setUpdateStatus($csrow->id, 'CURRENT');
				}
			}				
			if(strtotime($dateTo) < strtotime(date("Y-m-d"))){
				if($csrow->status == 'CURRENT' || $csrow->status == 'CURWUPD'){
					$csmodel->setUpdateStatus($csrow->id, 'EXPIRED');
				}
			}
			if(strtotime($dateFrom) <= strtotime(date("Y-m-d")) && strtotime($dateTo) >= strtotime(date("Y-m-d"))){
				if($csrow->status == 'APPROVED'){
					$csmodel->setUpdateStatus($csrow->id, 'CURRENT');
				}
			}			
		}	
    }

}

