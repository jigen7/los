<?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */
class Model_AutoRouting_CreditMatrix extends Zend_Db_Table {
	protected $_name="autorouting.credit_approval_matrix";
	
	public function getRouteTag($amount){
		$select = $this->select();
		$select->where('min_loan < '.$amount)
			   ->where('max_loan >= '.$amount);
		return $this->fetchRow($select);	
	}
	
	public function getAbsentRouteTag($amount, $routetag, $col, $config){
		$select = $this->select();
		$table = $this->fetchAll($select);
		foreach($table as $row){
			if($row->$col == $routetag) $id = $row->id;		
		}
		
		$select->where("id = ".$id);
		$row = $this->fetchRow($select);
		$min = $row->min_loan * $config;
		$max = $row->max_loan * $config;
		echo "<br>".$min." < ".$amount." <= ".$max." - ".$col." - ".$config." - ".$routetag."";
		return (($min < $amount) && ($max >= $amount))? "-WITHIN LIMIT":"-NOT WITHIN LIMIT";
	}
	
	
}
?>