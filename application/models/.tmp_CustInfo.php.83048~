<?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */
class Model_CustInfo extends Zend_Db_Table {
	protected $_schema="public";
	protected $_name="customer_information";
	protected $_dependentTables = array('BorrowerInfo');
    }
	
	
class BorrowerInfo extends Zend_Db_Table_Abstract {
    protected $_schema="public";
	protected $_name = 'borrower_accounts';
    protected $_referenceMap = array(
    		       'Model_CustInfo' => array(
                   'columns' => array('id'),
                   'refTableClass' => 'Model_CustInfo',
                   'refColumns' => array('fid')
                                   )
                   );
}
	
	
?>