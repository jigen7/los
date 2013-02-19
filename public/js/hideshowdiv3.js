/**
 * @author Paolo Marco Manarang
 */
/*
var subs_array = new Array("sub1","sub2","sub3","hide00","Married");// Put the id's of your hidden divs in this array

function displaySubs(the_sub){
	 if (document.getElementById(the_sub).style.display==""){
	   document.getElementById(the_sub).style.display = "none";return
  }
  for (i=0;i<subs_array.length;i++){
	   var my_sub = document.getElementById(subs_array[i]);
	  
	 }
  document.getElementById(the_sub).style.display = "";
}
*/

function displaySubs0(the_sub){
	//Brand
	if(the_sub == "Others"){
	document.getElementById('other_brand').style.display = "";
	document.getElementById('other_unit').style.display = "";
	document.getElementById('other_sell').style.display = "";
	document.getElementById('veh_unit').style.display = "none";
	document.getElementById('veh_sell').style.display = "none";
	
	
	
	}else {
	document.getElementById('other_brand').style.display = "none";
	document.getElementById('other_unit').style.display = "none";
	document.getElementById('other_sell').style.display = "none";
	document.getElementById('veh_unit').style.display = "";
	document.getElementById('veh_sell').style.display = "";

	}

}

function displaySubs1(the_sub){
	//Unit
	if(the_sub == "Others"){
	document.getElementById('other_unit').style.display = "";
	document.getElementById('other_sell').style.display = "";
	document.getElementById('veh_unit').style.display = "none";
	document.getElementById('veh_sell').style.display = "none";
	
	
	}else {
	document.getElementById('other_unit').style.display = "none";
	document.getElementById('other_sell').style.display = "none";
	document.getElementById('veh_unit').style.display = "";
	document.getElementById('veh_sell').style.display = "";

	}

}
	
function displaySubs2(the_sub){
	//Selling Price
	if(the_sub == "Others"){
	document.getElementById('other_sell').style.display = "";

	}else {
	document.getElementById('other_sell').style.display = "none";
	}
}


