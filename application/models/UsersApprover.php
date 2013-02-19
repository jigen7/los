 <?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */

    class Model_UsersApprover extends Zend_Db_Table

    {
	protected $_name="user_loan_approval";
		
	function returnStatusRole($status,$route){
		//use in autorouting sms
		$select = $this->select();
		
		$select->where('status like ?',$status);
		if($route){
		$select->where('route like ?',$route);
		}
		//echo $route.'fdfd';
		$detail = $this->fetchRow($select);
		return $detail->approver;
	}
	

	
    }


 ?>