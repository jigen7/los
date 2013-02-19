<?php
if (@$_REQUEST['ajax']) {
    // connect to local database 'test' on localhost

		//$name = $_REQUEST['borrower_pres_address_province'];
		//$prov = $_REQUEST['borrower_prev_address_province'];
		$city =  $_REQUEST['borrower_prev_address_city'];
		$brgy2 =  $_REQUEST['brgy2'];
		//$brgy =  $_REQUEST['borrower_prev_address_brgy'];
		$conn = pg_pconnect("dbname=LoanSystem user=postgres password=123456");
		//$sql = "Select * FROM test where category=".$_REQUEST['category']."";
		//$sql = "Select * FROM list_city where city='$city' UNION ALL Select * FROM list_city where province='$name'";
		$sql = "Select distinct(category) FROM select_chain_address where category='$brgy2' ";
		$results = pg_query($conn,$sql);
		
		$sql2 ="Select * FROM select_chain_address where category='$prov' OR category='$city' OR category='$brgy'";
		$results2 = pg_query($conn,$sql2);
		
       

        $json = array();
	
	$num = pg_num_rows($results2);

	if($num == 0){
	$json[] = '" "';
	}else {
		if($brgy2){
	$json[] = '"' . $brgy2 . '"';
		}

	}
	while (is_resource($results2) && $row = pg_fetch_object($results2)) {
            //$json[] = '{"id" : "' . $row->id . '", "label" : "' . $row->label . '"}';
            $json[] = '"' . $row->element . '"';
	    //$json[] = '{"id" : "' . $row->element . '", "label" : "' . $row->element . '"}';
        }
	
	
        
        echo '[' . implode(',', $json) . ']';
        die(); // filthy exit, but does fine for our example.

}
?>