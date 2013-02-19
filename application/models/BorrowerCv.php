<?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */
class Model_BorrowerCv extends Zend_Db_Table {
	protected $_name="borrower_credit_verification";
	
	function ifCV($capno){
		
		
		$select = $this->select();
		$select->where('capno like ?',$capno);
		$detail = $this->fetchRow($select);
		
		if($detail->backgrd){
			return true;
		}else {
			return false;
		}
		
	}
	
    }
?>