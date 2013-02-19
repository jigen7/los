 <?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */

    class Model_SmsApproval extends Zend_Db_Table

    {
	protected $_name="sms_craw_approval";
		
	function addtoDB($capno,$approver){
		
		$account = new Model_BorrowerAccount();
		$detail = $account->fetchRowModel($capno);
		
		
		$data = array(
		'capno'=>$detail->capno,
		'borrower'=>$detail->borrower_fname.' '.$detail->borrower_mname.' '.$detail->borrower_lname,
		'amount_finance'=>number_format($detail->amountloan,2),
		'down_payment'=>$detail->downpayment_percent.'%',
		'term'=>$detail->loanterm.'mos.',
		'gmi'=>$detail->gmi_ratio.'%',
		'approver'=>$approver,
		'cpno'=>'',
		'decided'=>'N',
		//'time_1st_sent'
		//'time_last_sent'
		);
		
		//print_r($data);
		$this->insert($data);
	}
	

	
    }


 ?>