<?php
include 'config.php';

class autosave
{

	var $queryVars;
	function autosave($queryVars)
	{
		$this->queryVars = $queryVars;
	}
	/**
	 * Method to return the status of the AJAX transaction
	 *
	 * @return  string A string of raw HTML fetched from the Server
	 */
	function return_response()
	{

	$remarks = $this->queryVars['remarks'];
	$capno = $this->queryVars['capno'];
	$by = strtolower($this->queryVars['by']);
	$date = date("r");
	//$dbconn = pg_connect('dbname=LoanSystem user=postgres password=123456');
	//$sql = "SELECT bus_income FROM borrower_business WHERE capno::text LIKE '$capnos'" ;
	
	//$sql ="Select distinct(brand) FROM 	select_chain_vehicle_new where month = '$month' AND year = '$year' AND dealer = '$dealer'";
	//$res = pg_query($sql);  
	$sql = "INSERT INTO user_autosave (capno,by,datetime,remarks) VALUES ('$capno','$by','$date','$remarks')";
	//$sql = "INSERT INTO user_autosave (remarks) VALUES ('$remarks')";

	$res = pg_query($sql);  
	//$return = array();
	
      //  while (is_resource($res) && $row = pg_fetch_object($res)) {
	//$return[] = $row->brand;
	//}

	
	
	return "Draft Save: ".$date;
	}
	
	function is_authorized()
	{
		return true;
	}
}



?>