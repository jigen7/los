<?php
include 'config.php';

if (@$_REQUEST['ajax']) {
    // connect to local database 'test' on localhost

		$name = $_REQUEST['borrower_prev_address_province'];
		//$conn = pg_pconnect("dbname=LoanSystem user=postgres password=123456");
		//$sql = "Select * FROM test where category=".$_REQUEST['category']."";
		$sql = "Select * FROM list_city where province='$name'";
		$results = pg_query($sql);
		
       
        $json = array();
        
        while (is_resource($results) && $row = pg_fetch_object($results)) {
            //$json[] = '{"id" : "' . $row->id . '", "label" : "' . $row->label . '"}';
            $json[] = '"' . $row->city . '"';
        }
        
        echo '[' . implode(',', $json) . ']';
        die(); // filthy exit, but does fine for our example.

}
?>