 <?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */

    class Model_Sms_SmsSending extends Zend_Db_Table

    {
	protected $_name="sms_sending";
		
	
	public function checktoMove(){
		
		$smsHistory = new Model_Sms_SmsHistory();
		
		$select = $this->select();
		$select->where("decided like ?","T");
		$details = $this->fetchAll($select);
		
		foreach($details as $x){
			
			$smsArray = $x->toArray();
			$smsArray['id'] = null;
			$smsHistory->insert($smsArray);
			$where = "id =".$x->id;
			$this->delete($where);
		}
		
		
	}

	
    }


 ?>