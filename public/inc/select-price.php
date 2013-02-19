<?php
include 'config.php';

if (@$_REQUEST['ajax']) {
    // connect to local database 'test' on localhost


		$unit =  $_REQUEST['veh_unit'];
		//$conn = pg_pconnect("dbname=LoanSystem user=postgres password=123456");
		//$sql = "Select * FROM test where category=".$_REQUEST['category']."";
		//$sql2 = "Select * FROM select_chain_vehicle where element = '$unit2' ";

		$sql2="Select * FROM select_chain_vehicle_new where unit = '$unit'";
		//$sql2 ="Select * FROM select_chain_vehicle where category='$brand' OR category='$unit'";
		$results2 = pg_query($sql2);
       
        $json = array();
	while (is_resource($results2) && $row = pg_fetch_object($results2)) {
            //$json[] = '{"id" : "' . $row->id . '", "label" : "' . $row->label . '"}';
            $json[] = '"' . $row->selling_price . '"';
	    //$json[] = '{"id" : "' . $row->element . '", "label" : "' . $row->element . '"}';
        }
	//$json[] = '"' . $others . '"';
        
        echo '[' . implode(',', $json) . ']';
        die(); // filthy exit, but does fine for our example.

}
?>