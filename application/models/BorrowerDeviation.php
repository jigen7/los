<?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */
class Model_BorrowerDeviation extends Zend_Db_Table {
	protected $_name="borrower_accounts_deviation";
	
	function fetchRowCapno($capno){
		
		$table = new Model_BorrowerDeviation();
		$select = $table->select();
		$select->where("capno like ?",$capno);
		$all = $table->fetchRow($select);
		return $all;
	}
    }
?>