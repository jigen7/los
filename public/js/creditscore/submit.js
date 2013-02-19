
	function submitModel(){
		answer = confirm("Submit this model for approval?"); 
		if (answer) document.forms['product'].submit();
	}
	
	function saveOutput(){
		output = prompt("Output File Name");
	}
	
	function submitRemarks(){
		document.forms['product'].submit();
		window.close();
	}
	
	checked=false;
	function checkedAll () {
		var aa= document.getElementById('fields');
		checked = (checked == false)? true : false;
		for (var i =0; i < aa.elements.length; i++){
			aa.elements[i].checked = checked;
		}
    }