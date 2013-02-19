<?php
class Zend_View_Helper_ViewScore extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewScore($score,$capno,$mode){
		//$capno is the highest capno
		//$score score
		$user = Zend_Auth::getInstance()->getIdentity();
	    $userRoles = new Model_UsersRoles();
		$select = $userRoles->select()->where('roles like ?',$user->role_type);
	    $access = $userRoles->fetchRow($select);
		$action = $this->getRequest()->getActionName();
		
		if($action == 'mapage'){
		$accnt = new Model_BorrowerAccountMa();
		$principal = new Model_BorrowerAccountMa();			
		}
		else if($action == 'capage'){
		$accnt = new Model_BorrowerAccountCa();
		$principal = new Model_BorrowerAccountCa();			
		}
		else {
		$accnt = new Model_BorrowerAccount();
		$principal = new Model_BorrowerAccount();
		}




		
		//Basis Detail
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);
		
		$select2 = $principal->select();
		$select2->where('capno like ?',capnosep($capno).'0'.capnorecon($capno));
		//$select->where('relation like ?','Principal');
		$principaldetail = $principal->fetchRow($select2);
		$scoredefault = $principaldetail->score_tag;
		$scoredefault_orig = $principaldetail->score_tag_orig;
		/*$chk = Zend_Controller_Action_HelperBroker::getStaticHelper('chkDeviation');
		$wDeviation = $chk->direct($capno);
		$countDev = chkArray($wDeviation);
		if($countDev > 0){						
		}*/
		/*
		if($principaldetail->deviation){
			$ifDev = "D";		
		}*/
		/*
		$dev = Zend_Controller_Action_HelperBroker::getStaticHelper('ChkDeviation');		
		$wDeviation = $dev->direct($capno);
		$countdev = chkArray($wDeviation);
			if($countdev > 0){
			$ifDev = "D";			
		}
		*/
		if($principaldetail->deviation){
			$ifDev ='D';
		}
		
		
		//$scoreR = scoreModule($score, $accntdetail->empbus_status);
		// get the action helper in the helper broker
		$scoretag = Zend_Controller_Action_HelperBroker::getStaticHelper('ScoreTag');
		// To get if P1,P2,F1,F2,F3
		if(is_array($score)){
			$tempScore = $score;
			$score = $score[0];
			$scoreOCS = $tempScore[1];

		}
		$scoreR = $scoretag->direct($score, $accntdetail->empbus_status,$principaldetail->capno);
		//retain score variable
		
/****WITH ACCESS*********WITH ACCESS********WITH ACCESS*********/
		if($access->view_score){
		// Non Ma	
		/****IF SCORETAG FIELD IS NOT NULL*********/
			if($scoredefault){
			//new scoring
			/****IF MODE IS SCORE*****/

				if($mode == 'score'){
							// if mode is score rescore the application
							if($score == 0){
							return "No Score";	
							}
							else if($score > 0){
							
							return $accntdetail->empbus_status." ".$score." ".$scoreR. " ".$ifDev." ".$scoreOCS;
							
							}
							else if($score < 0){
							
							return "Outside Credit Scoring Model Range for Manual Evaluation";	
							}
				}
			/****IF MODE IS VIEW*****/
				if($mode == 'view'){
					//return the default scoredefault
					/****CONTROLLER ACTION IS MAPAGE*****/
					if($action == 'mapage'){
					
						if($score == 0){
						return "No Score";	
						}
						else if($score > 0){
						return $accntdetail->empbus_status." ".$score." ".$scoreR. " ".$ifDev." ".$scoreOCS;
						}
						else if($score < 0){
						return "Outside Credit Scoring Model Range for Manual Evaluation ".$scoredefault_orig;	
						}
					}
					/****CONTROLLER ACTION IS CRAWFORM*****/
					else if($action == 'crawform'){
						if(strpos($scoredefault,"Outside")=== false){
						$scorebreakdown = explode(' ', $scoredefault);
						return $scorebreakdown[0].' '.$scorebreakdown[2].' '.$scorebreakdown[3] ;
						}
						else{
						return $scoredefault;	
						}
					}
					/****CONTROLLER ACTION IS ANY*****/
					else {
						if(strpos($scoredefault,"Outside")=== false){
							//IF OCS 
							return $scoredefault;
						}else {
							//IF OCS
							return $scoredefault.' '.$scoredefault_orig;
						}
					}/***END OF ELSE **/
				}/****END OF VIEW MODE****/
			
			}/****END OF IF SCORETAG EXIST****/
			/****IF SCORETAG FIELD IS NULL OLD SCORING DYNAMIC*********/
			else {
			//old way of scoring
					if($score == 0){
					return "No Score";	
					}
					else if($score > 0){
					return $accntdetail->empbus_status." ".$score." ".$scoreR. " ".$ifDev;
					}
					else if($score < 0){
					return "Outside Credit Scoring Model Range for Manual Evaluation ".$scoredefault_orig;	
					}	
			}
		}
/****NO ACCESS*********NO ACCESS********NO ACCESS*********/		
		else{
		// MA no access rights	
			if($scoredefault){
				//new scoring
				if(strpos($scoredefault,"F3") === false){
				// if not F3 then display empbus
					if(strpos($scoredefault,"Outside")=== false){
						return $accntdetail->empbus_status."<img src=".$this->baseUrl()."/images/score-check.gif width=20px/>";	
					}
					else {
						return "Outside Credit Scoring Model Range for Manual Evaluation";	
					}
				}else{
					// If F3 is true then display the status and F3
				return $accntdetail->empbus_status." F3";
				}
			}
			
			else{
				//old scoring if no scoredefault
				
				if($score == 0){
				return "No Score";	
				}
				else if($score < 0){
				return "Outside Credit Scoring Model Range for Manual Evaluation";	
				}
				else if($score > 0){
					if (($scoreR == "F3")){
						return $accntdetail->empbus_status." ".$scoreR; }	
					else {
						return $accntdetail->empbus_status."<img src=".$this->baseUrl()."/images/score-check.gif width=20px/>";
					}	
				}
				
			}
			
		}
		/*
		if(($access->view_score) && ($score == 0)){
			return "Outside Credit Scoring Model Range for Manual Evaluation";
		}
		else if((!$access->view_score) && (!$score)){
			//For Marketing
			return "No Score";
		}
		else if (($access->view_score) && ($score)) {
			return $accntdetail->empbus_status." ".$score." ".$scoreR. " ".$ifDev;
		}
		else if ((!$access->view_score) && ($score)) {
			if($scoredefault){
				if(strpos($scoredefault,"F3") === false){
				return $scoredefault;
				}else{
				return $accntdetail->empbus_status."<img src=".$this->baseUrl()."/images/score-check.gif width=20px/>";
				}
			}
			else {	
			//old account without score tag
				if (($scoreR == "F3")){
				return $accntdetail->empbus_status." ".$scoreR; }	
				else {
				return $accntdetail->empbus_status."<img src=".$this->baseUrl()."/images/score-check.gif width=20px/>";
				}
			}
		}
		*/


	}
	
	
	function baseUrl() {
		$front = Zend_Controller_Front::getInstance();
		$url = $front->getBaseUrl();
		return $url;
	}
	

	
}

function scoreModule($score,$emptype){
	// Deprecated function see Action Helper March 03,2010
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
			return "Outside Credit Scoring Model Range for Manual Evaluation";
		}
		
	}
	
}




?>