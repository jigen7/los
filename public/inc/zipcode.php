<?php
include 'config.php';

class zipcode
{

	var $queryVars;
	function zipcode($queryVars)
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
	$city = $this->queryVars['city'];
	$brgy = $this->queryVars['brgy'];

	//$dbconn = pg_connect('dbname=LoanSystem user=postgres password=123456');
	//$sql = "SELECT bus_income FROM borrower_business WHERE capno::text LIKE '$capnos'" ;
	$sql ="Select distinct(zipcode) FROM select_chain_address_new where brgy = '$brgy' AND city = '$city'";
	$res = pg_query($sql);  
	$data = pg_fetch_object($res);


	return $data->zipcode;
	}
	
	function is_authorized()
	{
		return true;
	}
}



?>