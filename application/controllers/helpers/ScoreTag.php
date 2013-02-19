<?php

class Zend_Controller_Action_Helper_ScoreTag extends
                Zend_Controller_Action_Helper_Abstract
{
    function direct($score,$emptype,$capno)
    {
 		//Scoring Tag 	
		if ($emptype == "E"){
		if ($score >= 447){
			return "P2";
		}
		else if(($score>=390) && ($score <= 446)){
			return "P1"; 
		}
		else if(($score>=378) && ($score <=389)){
			return "F1";		
		}
		else if(($score>=372) && ($score <=377)){
			return "F2";		
		}
		else if(($score >= 1 ) && ($score<=371)){
			return "F3";		
		}
		else if ($score == 0){
			return "No Score";	
		}
		else if ($score < 0){
			return "Outside Credit Scoring Model Range for Manual Evaluation";	
		}
		
	}
	else if($emptype == "SE"){
		
		if ($score >= 489){
			return "P2";
		}
		else if(($score>=424) && ($score <= 488)){
			return "P1"; 
		}
		else if(($score>=412) && ($score <=423)){
			return "F1";		
		}
		else if(($score>=402) && ($score <=411)){
			return "F2";		
		}
		else if(($score >= 1) && ($score<=401)){
			return "F3";		
		}
		else if ($score == 0){
			return "No Score";	
		}
		else if ($score < 0){
			return "Outside Credit Scoring Model Range for Manual Evaluation";	
		}
		
	}


	}
}



