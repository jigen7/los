<?php
include 'config.php';

if (@$_REQUEST['ajax']) {
    // connect to local database 'test' on localhost
    
	$dealer = $_REQUEST['dealer'];
	//pass value
	$month = $_REQUEST['month'];
	$year = $_REQUEST['listyear_pricelist'];
	
	$sql = "Select distinct(month) FROM select_chain_vehicle_new where year='year' ";
	$results = pg_query($sql);
        $json = array();
	
	

	$json[] = '"' . $dealer. '"';


        while (is_resource($results) && $row = pg_fetch_object($results)) {
            $json[] = '"' . $row->month . '"';
        }

        echo '[' . implode(',', $json) . ']';
        die(); // filthy exit, but does fine for our example.

}
?>