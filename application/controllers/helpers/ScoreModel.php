<?php

class Zend_Controller_Action_Helper_ScoreModel extends
                Zend_Controller_Action_Helper_Abstract
{
    function direct($capno)
    {
    	//March 12,2010 
		// Helper from choosing the Model to be use base on the Date Implemented
    $table = new Model_AccountHistory();

    // Date when the Model 2 is Implemented	
	$dateModel2 = new DateTime('2010-01-15'); 
	$dateSubmit = new DateTime ($table->getLastMAS($capno));
	//echo $table->getLastMAS($capno).' < 2010-03-11<br>';
	
	if ($dateSubmit <= $dateModel2){
		return 'ScoreModule';
		}
	else {
		return 'ScoreModule2';
	}
	
	}
}


