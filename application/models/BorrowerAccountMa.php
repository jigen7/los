<?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */
class Model_BorrowerAccountMa extends Zend_Db_Table {
	protected $_name="borrower_accounts_audit_ma";
	
	
	function isExist($capno){
	
	$select = $this->select();
	$select->where('capno like ?',$capno);
	$count = $this->fetchAll($select)->count();
	
	
	if($count == 0){
		return true;
	}	else {
		return false;
	}
		
	}
	
    }
	
	
?>