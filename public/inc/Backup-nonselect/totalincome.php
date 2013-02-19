<?php
class totalincome 
{

	var $queryVars;
	function totalincome($queryVars)
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
	$capnosep = capnosep($capno);
	$recon = capnorecon($capno);
	$capnos = $capnosep.'_'.$recon;
	$dbconn = pg_connect('dbname=LoanSystem user=postgres password=123456');
	$sql = "SELECT bus_income FROM borrower_business WHERE capno::text LIKE '$capnos'" ;
	$res = pg_query($sql);  

	while ($data = pg_fetch_object($res)) {
	$sum += $data->bus_income;
	} 
	
	$sql2 = "SELECT emp_income FROM borrower_employment WHERE capno::text LIKE '$capnos'" ;
	$res2 = pg_query($sql2);
	
	while ($data2 = pg_fetch_object($res2)) {
	$sum += $data2->emp_income;
	} 

	return $sum;
	}
	
	function is_authorized()
	{
		return true;
	}
}

function moneyformat($amount){
	
	$amount = number_format($amount,2);
	return $amount;
	
}

function capnosep($capno){
	
	$capnosep = substr($capno,0,-2);
	
	return $capnosep;
	
}

function capnocurr($capno){
	//use in determining the spouse or coborrower!
	$capnocurr = substr($capno,12,1);
	
	return $capnocurr;
	
}

function capnorecon($capno){
	//use in determining the spouse or coborrower!
	$capnorecon = substr($capno,-1);
	
	return $capnorecon;
	
}

?>