<?php

class Zend_Controller_Action_Helper_EdocsHelper extends Zend_Controller_Action_Helper_Abstract
{
   public function direct()
   {
	
   }
   
   public function config($attr)
   {
   	switch($attr){
   		case 'host':
			return 'localhost';
		break;
		case 'dbname':
			return 'LoanSystem';
		break;
		case 'user':
			return 'postgres';
		break;
		case 'password':
			return 'riskPa$$word';
		break;
		
   	}
	
   }   
   
} 