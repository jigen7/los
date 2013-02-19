<?php
include 'config.php';

class pricelistchainunit
{

	var $queryVars;
	function pricelistchainunit($queryVars)
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
	$dealer = $this->queryVars['dealer'];
	$year = $this->queryVars['year'];
	$month = $this->queryVars['month'];
	$brand = $this->queryVars['brand'];

	//$dbconn = pg_connect('dbname=LoanSystem user=postgres password=123456');
	//$sql = "SELECT bus_income FROM borrower_business WHERE capno::text LIKE '$capnos'" ;
	
	$sql ="Select distinct(unit) FROM select_chain_vehicle_new where month = '$month' AND year = '$year' AND dealer = '$dealer' AND brand = '$brand' AND status = 'approved'";
	$res = pg_query($sql);  
	
	
	$return = array();
	
        while (is_resource($res) && $row = pg_fetch_object($res)) {
	$return[] = $row->unit;
	}

	
	
	return $return;
	}
	
	function is_authorized()
	{
		return true;
	}
}



?>