<?php
include 'config.php';

if (@$_REQUEST['ajax']) {
    // connect to local database 'test' on localhost
    
	$dealer = $_REQUEST['dealer_pricelist'];
	//pass value
	$month = $_REQUEST['month'];
	$year = $_REQUEST['year'];
	
	$sql = "Select distinct(year) FROM select_chain_vehicle_new where dealer='$dealer' ";
	$results = pg_query($sql);
        $json = array();
	
	$json[] = '"' . $year . '"';
        while (is_resource($results) && $row = pg_fetch_object($results)) {
            $json[] = '"' . $row->year . '"';
        }

        echo '[' . implode(',', $json) . ']';
        die(); // filthy exit, but does fine for our example.

}
?>