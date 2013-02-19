<?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */
class Model_BorrowerCrawFormApprovalsection extends Zend_Db_Table {
	protected $_name="borrower_crawform_approvalsection";
	
		public function chkifDecided($capno){
		//chl if he alredy decided if not show approval form else view form
		$user = Zend_Auth::getInstance()->getIdentity();
	    $username = $user->username;
	    $userRole = $user->role_type;
		$select = $this->select();
		$select->where('capno like ?',$capno);
		$select->where('approved_by like ?',$username);
		//$detail = $select->fetchAll($select);

			if($this->fetchAll($select)->count()== 0){
			return true;
			}else{
			return false;
			}
		
		}
		
		public function getApprovals($capno){
			$select = $this->select();
			$select->where('capno like ?',$capno);
			return $this->fetchAll($select);			
			
		}
    }
?>