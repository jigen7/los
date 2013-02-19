<?php
if (@$_REQUEST['ajax']) {
    // connect to local database 'test' on localhost

		$unit2 = $_REQUEST['unit2'];
		$brand = $_REQUEST['veh_brand'];
		$unit =  $_REQUEST['veh_unit'];
		$conn = pg_pconnect("dbname=LoanSystem user=postgres password=123456");
		//$sql = "Select * FROM test where category=".$_REQUEST['category']."";
		//$sql2 = "Select * FROM select_chain_vehicle where element = '$unit2' ";

		$sql2="Select * FROM select_chain_vehicle where element = '$unit2' UNION ALL Select * FROM select_chain_vehicle where category='$brand' OR category='$unit'";
		//$sql2 ="Select * FROM select_chain_vehicle where category='$brand' OR category='$unit'";
		$results2 = pg_query($conn,$sql2);
		
       
       
        $json = array();

	
	
	while (is_resource($results2) && $row = pg_fetch_object($results2)) {
            //$json[] = '{"id" : "' . $row->id . '", "label" : "' . $row->label . '"}';
            $json[] = '"' . $row->sell . '"';
	    //$json[] = '{"id" : "' . $row->element . '", "label" : "' . $row->element . '"}';
        }
	$others = 'Others';
	//$json[] = '"' . $others . '"';
        
        echo '[' . implode(',', $json) . ']';
        die(); // filthy exit, but does fine for our example.

}
?>