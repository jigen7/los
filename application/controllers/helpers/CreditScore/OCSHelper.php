<?php

class Zend_Controller_Action_Helper_OCSHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($formData, $namever, $rule)
	{	
		$otmodel = new Model_Creditscore_OCSTable();
		$ftmodel = new Model_Creditscore_FieldsTable();
	
		$hField1 = $formData['hiddenField1'];
		$hField2 = $formData['hiddenField2'];
		$hAtt = $formData['hiddenAtt'];
		$hR1 = $formData['hiddenR1'];
		$hR2 = $formData['hiddenR2'];
		$hC1 = $formData['hiddenC1'];
		$hC2 = $formData['hiddenC2'];
		$logic = $formData['hiddenL'];
		$del = $formData['hiddenD'];
//		echo "<br>(".$hField.")[".$hAtt."]{".$logic."}--".$hR1."--".$hR2."--".$hC1."--".$hC2."--".$logic."--";

		$ftrow = $ftmodel->getType($hField1);
		if($formData['button'] == 'Add'){				   
			if($ftrow->type == 'String'){ 
				if($otmodel->checkCategory($namever, $hField1, $hAtt, $rule))
					$otmodel->addCategory($namever, $hField1, $hAtt, $rule);									
			}else{
				if($hField1 != "" && $hC1 != "" && $hR1 != ""){
					if($otmodel->checkNumeric($namever, $hField1, $hField2, $hR1, $hR2, $hC1, $hC2, $logic, $rule)){
						if($hField2 == "" || $hC2 == "" || $hR2 == "" || $logic == ""){			
							$otmodel->addNumeric($namever, $hField1, "", $hR1, "", $hC1, "", "", $rule);
						}else{
							$otmodel->addNumeric($namever, $hField1, $hField2, $hR1, $hR2, $hC1, $hC2, $logic, $rule);
						}
					}
				}else{ return "Please check your input";}
			}
		}else if($formData['button'] == 'Delete'){
			$otmodel->delCatNum($del);												
		}
    }

}

