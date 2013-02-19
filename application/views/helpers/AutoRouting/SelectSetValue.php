<?php
class Zend_View_Helper_SelectSetValue extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){$this->_view = $view;}

	function selectSetValue($id, $cre){
		$form = new Form_Admin_DefineMembers();
		$ccmodel = new Model_AutoRouting_CrecomConfig();
		$scmodel = new Model_AutoRouting_SubCrecomConfig();		

		if($cre == "crecom"){
			$croles = new Zend_Form_Element_Select("croles[".$id."]");
			$croles->removeDecorator('label')
				   ->removeDecorator('HtmlTag')
				   ->setattrib('id','croles')
				   ->setAttrib('width','15');
			$croles->addMultiOption("","");	 	
			
			$cctable = $ccmodel->getAll();			
			foreach($cctable as $ccrow){			
				$croles->addMultiOption($ccrow->role, $ccrow->role);
				if($id == $ccrow->id) $croles->setValue($ccrow->role, $ccrow->role);			
			}	   		
			return $croles;
		}else if($cre == "subcrecom"){
			$sroles = new Zend_Form_Element_Select("sroles[".$id."]");
			$sroles->removeDecorator('label')
				   ->removeDecorator('HtmlTag')
				   ->setattrib('id','sroles')
				   ->setAttrib('width','15');
			$sroles->addMultiOption("","");	 				
			
			$sctable = $scmodel->getAll();
			foreach($sctable as $scrow){
				$sroles->addMultiOption($scrow->role, $scrow->role);
				if($id == $scrow->id) $sroles->setValue($scrow->role, $scrow->role);			
			}	   		
			return $sroles;		
		}
	}	

	
/*		
	function selectSetValue2($id, $cre){
		$form = new Form_Admin_DefineMembers();
		$ccmodel = new Model_AutoRouting_CrecomConfig();
		$scmodel = new Model_AutoRouting_SubCrecomConfig();		

		if($cre == "crecom"){
			$str = "<select name='croles[".$id."]' id='croles' width='15'>";
			$str = $str."<option value='' label=''></option>";
			$cctable = $ccmodel->getAll();			
			foreach($cctable as $ccrow){	
				$str = $str."<option value='".$id."' label='".$ccrow->role."' ";		
				if($id == $ccrow->id) $str = $str."selected";	
				$str = $str." >".$ccrow->role."</option>";		
			}	 		
			return $str."</select>";
		}else if($cre == "subcrecom"){
			$str = "<select name='sroles[".$id."]' id='sroles' width='15'>";
			$str = $str."<option value='' label=''></option>";
			$sctable = $scmodel->getAll();			
			foreach($sctable as $scrow){	
				$str = $str."<option value='".$id."' label='".$scrow->role."' ";		
				if($id == $scrow->id) $str = $str."selected";	
				$str = $str." >".$scrow->role."</option>";		
			}	   		
			return $str."</select>";	
		}
	}

	function selectSetValue3($id, $cre){
		$form = new Form_Admin_DefineMembers();
		$ccmodel = new Model_AutoRouting_CrecomConfig();
		$scmodel = new Model_AutoRouting_SubCrecomConfig();

		if($cre == "crecom"){
			$cctable = $ccmodel->getAll();
			foreach($cctable as $ccrow){
				$form->croles->addMultiOption($ccrow->id, $ccrow->role);
				if($id == $ccrow->id) $form->croles->setValue($ccrow->id, $ccrow->role);			
			}	   		
			return $form->croles;
		}else if($cre == "subcrecom"){
			$sctable = $scmodel->getAll();
			foreach($sctable as $scrow){
				$form->sroles->addMultiOption($scrow->id, $scrow->role);
				if($id == $scrow->id) $form->sroles->setValue($scrow->id, $scrow->role);			
			}	   		
			return $form->sroles;		
		}
	}

*/		
	
}






?>