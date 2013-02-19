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

function displaySubs(the_sub){
	// Selection for Civil Status
	if(the_sub == "Single"){
	document.getElementById('civilstatusdiv').style.display = "none";
	}
	if(the_sub == "Married"){
	document.getElementById('civilstatusdiv').style.display = "";
	}
	if(the_sub == "Separated"){
	document.getElementById('civilstatusdiv').style.display = "";
	}
	if(the_sub == "Widowed"){
	document.getElementById('civilstatusdiv').style.display = "";
	}
	// show promo row
	if(the_sub == "Yes"){
	document.getElementById('promodiv').style.display = "";
	}
	if(the_sub == "No"){
	document.getElementById('promodiv').style.display = "none";
	}
	//show date resgined
	if(the_sub == "Previous"){
	document.getElementById('dateresigned').style.display = "";
	}
	if(the_sub == "Current"){
	document.getElementById('dateresigned').style.display = "none";
	document.getElementById('dater').value = '';
	}

}
