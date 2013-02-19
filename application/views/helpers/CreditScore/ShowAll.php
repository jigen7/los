<?php
class Zend_View_Helper_CreditScore_ShowAll extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function showAll($namefield, $hiddens, $namever){
		$fsmodel = new Model_Creditscore_Fieldsselected();
		$afields = $fsmodel->viewModelSpecific($namever);
		
		$arrFields = explode(",",$hiddens);
		$count = count($arrFields) - 1;
		for($i=0; $i!=$count; $i++){
			foreach($afields as $row){
				if($arrFields[$i] == $namefield){
					if($row->namefield == $namefield){
						echo "<tr><td>".$row->namefield."</td>";
						echo "<td>".$row->attribute."</td>";
						echo "<td align=\"center\">".$row->wto."</td>";
						echo "<td></td></tr>";
					}
				}
			}
		}
	}
	
}
?>