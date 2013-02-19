<?php
include 'config.php';

if (@$_REQUEST['ajax']) {
    // connect to local database 'test' on localhost
    
    

		$brand = $_REQUEST['veh_brand'];
		$unit2 =  $_REQUEST['unit2'];// passed

		//$conn = pg_pconnect("dbname=LoanSystem user=postgres password=123456");
		//$sql = "Select * FROM test where category=".$_REQUEST['category']."";
		//$sql = "Select * FROM list_city where city='$city' UNION ALL Select * FROM list_city where province='$name'";
		$sql = "Select distinct(unit) FROM select_chain_vehicle_new where brand='$brand' AND unit='$unit2'";
		$results = pg_query($sql);
		
		$sql2 ="Select distinct(unit) FROM select_chain_vehicle_new where brand = '$brand'";
		$results2 = pg_query($sql2);
		
       
       
        $json = array();

	
        while (is_resource($results) && $row = pg_fetch_object($results)) {
            //$json[] = '{"id" : "' . $row->id . '", "label" : "' . $row->label . '"}';
            $json[] = '"' . $row->unit . '"';
	    //$json[] = '{"id" : "' . $row->element . '", "label" : "' . $row->element . '"}';
        }
	
	while (is_resource($results2) && $row = pg_fetch_object($results2)) {
            //$json[] = '{"id" : "' . $row->id . '", "label" : "' . $row->label . '"}';
            $json[] = '"' . $row->unit . '"';
	    //$json[] = '{"id" : "' . $row->element . '", "label" : "' . $row->element . '"}';
        }
        
        echo '[' . implode(',', $json) . ']';
        die(); // filthy exit, but does fine for our example.

}
?>