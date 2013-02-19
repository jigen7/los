<?php
include 'config.php';


if (@$_REQUEST['ajax']) {
    // connect to local database 'test' on localhost
    
    		$city = $_REQUEST['borrower_pres_address_city'];

		$brgy = $_REQUEST['borrower_pres_address_brgy'];
		//$conn = pg_pconnect("dbname=LoanSystem user=postgres password=123456");
		//$sql = "Select * FROM test where category=".$_REQUEST['category']."";
		//$sql = "Select * FROM list_city where city='$city' UNION ALL Select * FROM list_city where province='$name'";

		
		$sql2 ="Select distinct(zipcode) FROM select_chain_address_new where brgy = '$brgy'";
		$results2 = pg_query($sql2);
		
       
       
        $json = array();

	$json[] = '"' . $city . '"';
	
	while (is_resource($results2) && $row = pg_fetch_object($results2)) {
            //$json[] = '{"id" : "' . $row->id . '", "label" : "' . $row->label . '"}';

            $json[] = '"' . $row->zipcode . '"';
	    //$json[] = '{"id" : "' . $row->element . '", "label" : "' . $row->element . '"}';
        }
        
        echo '[' . implode(',', $json) . ']';
        die(); // filthy exit, but does fine for our example.

}
?>