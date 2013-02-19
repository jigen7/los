<?php
include 'config.php';

if (@$_REQUEST['ajax']) {
    // connect to local database 'test' on localhost
    
    

		$city = $_REQUEST['borrower_prev_address_city'];
		$brgy2 =  $_REQUEST['brgy2'];// passed

		//$conn = pg_pconnect("dbname=LoanSystem user=postgres password=123456");
		//$sql = "Select * FROM test where category=".$_REQUEST['category']."";
		//$sql = "Select * FROM list_city where city='$city' UNION ALL Select * FROM list_city where province='$name'";
		$sql = "Select distinct(brgy) FROM select_chain_address_new where city='$city' AND brgy='$brgy2'";
		$results = pg_query($sql);
		
		$sql2 ="Select distinct(brgy) FROM select_chain_address_new where city = '$city'";
		$results2 = pg_query($sql2);
		
       
       
        $json = array();

	
        while (is_resource($results) && $row = pg_fetch_object($results)) {
            //$json[] = '{"id" : "' . $row->id . '", "label" : "' . $row->label . '"}';
            $json[] = '"' . $row->brgy . '"';
	    //$json[] = '{"id" : "' . $row->element . '", "label" : "' . $row->element . '"}';
        }
	
	while (is_resource($results2) && $row = pg_fetch_object($results2)) {
            //$json[] = '{"id" : "' . $row->id . '", "label" : "' . $row->label . '"}';
            $json[] = '"' . $row->brgy . '"';
	    //$json[] = '{"id" : "' . $row->element . '", "label" : "' . $row->element . '"}';
        }
        
        echo '[' . implode(',', $json) . ']';
        die(); // filthy exit, but does fine for our example.

}
?>