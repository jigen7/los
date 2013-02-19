<?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */
class Model_AutoRouting_Config extends Zend_Db_Table {
	protected $_name="autorouting.config";
	
	public function getColConfig($name){
		$select = $this->select();
		$select->where('name LIKE ?', $name);
		$detail = $this->fetchRow($select);	
		return $detail->config;	
	}
	
	

}
?>