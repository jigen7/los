<?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS-CIN
 * August 24,2010
 */
class Model_Booking_BookingAuto extends Zend_Db_Table {
	protected $_name="booking.booking_auto_details";
	
	public function fetchRowModel($capno){

		$select = $this->select();
		$select->where('capno like ?',$capno);	
		$detail = $this->fetchRow($select);
		return $detail;
		
	}
	
	public function getPN($capno){
	//get pn_number if it exist
	
		$select = $this->select();
		$select->where('capno like ?',$capno);	
		$detail = $this->fetchRow($select);
		
		if($detail->pn_number){
			return $detail->pn_number;
		}else {
			return "";
		}
	
	
	}
	
    }
?>

