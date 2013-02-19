<?php
include 'config.php';

class getdata 
{

	var $queryVars;
	function getdata($queryVars)
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
	$username = $this->queryVars['username'];
	$username = strtolower($username);
	//$dbconn = pg_connect('dbname=LoanSystem user=postgres password=123456');
	
	$sql = "SELECT digital_password FROM users WHERE username::text LIKE '$username'" ;
	$res = pg_query($sql);  
	$data = pg_fetch_object($res);


	return $data->digital_password;

	//return $sum;
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