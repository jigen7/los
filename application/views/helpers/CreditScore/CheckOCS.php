<?php
class Zend_View_Helper_CreditScore_CheckOCS extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;	
	function setView($view){$this->_view = $view;}
		
	function checkOCS($id, $modelver, $capno, $sesid){

		$scoreAtt = new Model_Creditscore_ScoreAttributes();
		$select = $scoreAtt->select();
		$select->where("id = ?",$id);
		$scoreDetail = $scoreAtt->fetchRow($select)->toArray();
		parse_str($scoreDetail[attributes],$outputAttr);	
		
		$creditscore = Zend_Controller_Action_HelperBroker::getStaticHelper('CreditScore');
		$outputAttr2 = $creditscore->renameField($outputAttr);
		$creditscore->checkOCS($modelver, $outputAttr2, $id, $capno, $sesid);
		
//		$outputAttr2 = $this->_helper->CreditScore->renameField($outputAttr);		
//		$counter = $this->_helper->CreditScore->checkOCS($modelver, $outputAttr2);
	}
	
}
?>