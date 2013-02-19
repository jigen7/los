<?php
include 'config.php';

class spousechk 
{

	var $queryVars;
	function spousechk($queryVars)
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
	$capno = $this->queryVars['cap'];
	$capnosep = substr($capno,0,-2);	
	$capnorecon = substr($capno,-1);
	$capnocombine = $capnosep.'_'.$capnorecon;
	//$dbconn = pg_connect('dbname=LoanSystem user=postgres password=123456');
	// If Comaker
	//SELECT monthly_amortization,relation FROM borrower_ob_existloan where relation::text NOT LIKE '%Co-Maker%' AND relation::text IS NULL
	$sql = "SELECT borrower_lname FROM borrower_accounts WHERE capno::text LIKE '$capnocombine' AND relation::text LIKE 'Spouse'";
	$res = pg_query($sql);  
	
	$sum=0;
	while ($data = pg_fetch_object($res)) {
	$sum++;
	} 
	
	return $sum;
	}
	
	
	function is_authorized()
	{
		return true;
	}
}




?>