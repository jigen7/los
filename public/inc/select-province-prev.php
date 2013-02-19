<?php
include 'config.php';

if (@$_REQUEST['ajax']) {
    // connect to local database 'test' on localhost

		$prov = $_REQUEST['borrower_prev_address_province'];
		$city2 =  $_REQUEST['city2'];// passed
		//$conn = pg_pconnect("dbname=LoanSystem user=postgres password=123456");
		$sql = "Select distinct(city) FROM select_chain_address_new where province='$prov' and city='$city2' ";
		$results = pg_query($sql);
		
		$sql2 ="Select distinct(city) FROM select_chain_address_new where province='$prov'";
		$results2 = pg_query($sql2);
		
       
       
        $json = array();

		
        while (is_resource($results) && $row = pg_fetch_object($results)) {
            //$json[] = '{"id" : "' . $row->id . '", "label" : "' . $row->label . '"}';
            $json[] = '"' . $row->city . '"';
	    //$json[] = '{"id" : "' . $row->element . '", "label" : "' . $row->element . '"}';
        }
	
	while (is_resource($results2) && $row = pg_fetch_object($results2)) {
            //$json[] = '{"id" : "' . $row->id . '", "label" : "' . $row->label . '"}';
            $json[] = '"' . $row->city . '"';
	    //$json[] = '{"id" : "' . $row->element . '", "label" : "' . $row->element . '"}';
        }
        
        echo '[' . implode(',', $json) . ']';
        die(); // filthy exit, but does fine for our example.

}
?>