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
	if(the_sub == 0){
		document.getElementById('alhm').style.display = "none";
		document.getElementById('dh').style.display = "none";
		document.getElementById('ah').style.display = "none";
		document.getElementById('mo').style.display = "none";
		document.getElementById('csh').style.display = "none";
		document.getElementById('co').style.display = "none";
		document.getElementById('lo').style.display = "none";
		document.getElementById('crah').style.display = "none";

	}
	else if(the_sub == "MA"){
		document.getElementById('alhm').style.display = "";
		document.getElementById('dh').style.display = "";
		document.getElementById('ah').style.display = "";
		document.getElementById('mo').style.display = "";
		document.getElementById('csh').style.display = "none";
		document.getElementById('co').style.display = "none";
		document.getElementById('lo').style.display = "none";
		document.getElementById('crah').style.display = "none";

	}
	else if(the_sub == "MO"){
		document.getElementById('alhm').style.display = "";
		document.getElementById('dh').style.display = "";
		document.getElementById('ah').style.display = "";
		document.getElementById('mo').style.display = "none";
		document.getElementById('csh').style.display = "none";
		document.getElementById('co').style.display = "none";
		document.getElementById('lo').style.display = "none";
		document.getElementById('crah').style.display = "none";

	}
	else if(the_sub == "AH"){
		document.getElementById('alhm').style.display = "";
		document.getElementById('dh').style.display = "";
		document.getElementById('ah').style.display = "none";
		document.getElementById('mo').style.display = "none";
		document.getElementById('csh').style.display = "none";
		document.getElementById('co').style.display = "none";
		document.getElementById('lo').style.display = "none";
		document.getElementById('crah').style.display = "none";

	}
	else if(the_sub == "DH"){
		document.getElementById('alhm').style.display = "";
		document.getElementById('dh').style.display = "none";
		document.getElementById('ah').style.display = "none";
		document.getElementById('mo').style.display = "none";
		document.getElementById('csh').style.display = "none";
		document.getElementById('co').style.display = "none";
		document.getElementById('lo').style.display = "none";
		document.getElementById('crah').style.display = "none";

	}
	else if(the_sub == "ALMH"){
		document.getElementById('alhm').style.display = "none";
		document.getElementById('dh').style.display = "none";
		document.getElementById('ah').style.display = "none";
		document.getElementById('mo').style.display = "none";
		document.getElementById('csh').style.display = "none";
		document.getElementById('co').style.display = "none";
		document.getElementById('lo').style.display = "none";
		document.getElementById('crah').style.display = "none";

	}
	else if(the_sub == "CA"){
		document.getElementById('alhm').style.display = "none";
		document.getElementById('dh').style.display = "none";
		document.getElementById('ah').style.display = "none";
		document.getElementById('mo').style.display = "none";
		document.getElementById('csh').style.display = "";
		document.getElementById('co').style.display = "";
		document.getElementById('lo').style.display = "none";
		document.getElementById('crah').style.display = "none";

	}
	else if(the_sub == "AP"){
		document.getElementById('alhm').style.display = "none";
		document.getElementById('dh').style.display = "none";
		document.getElementById('ah').style.display = "none";
		document.getElementById('mo').style.display = "none";
		document.getElementById('csh').style.display = "";
		document.getElementById('co').style.display = "";
		document.getElementById('lo').style.display = "none";
		document.getElementById('crah').style.display = "none";

	}
	else if(the_sub == "CI"){
		document.getElementById('alhm').style.display = "none";
		document.getElementById('dh').style.display = "none";
		document.getElementById('ah').style.display = "none";
		document.getElementById('mo').style.display = "none";
		document.getElementById('csh').style.display = "";
		document.getElementById('co').style.display = "";
		document.getElementById('lo').style.display = "none";
		document.getElementById('crah').style.display = "none";

	}
	else if(the_sub == "CO"){
		document.getElementById('alhm').style.display = "none";
		document.getElementById('dh').style.display = "none";
		document.getElementById('ah').style.display = "none";
		document.getElementById('mo').style.display = "none";
		document.getElementById('csh').style.display = "";
		document.getElementById('co').style.display = "none";
		document.getElementById('lo').style.display = "none";
		document.getElementById('crah').style.display = "none";

	}
	else if(the_sub == "CSH"){
		document.getElementById('alhm').style.display = "none";
		document.getElementById('dh').style.display = "none";
		document.getElementById('ah').style.display = "none";
		document.getElementById('mo').style.display = "none";
		document.getElementById('csh').style.display = "none";
		document.getElementById('co').style.display = "none";
		document.getElementById('lo').style.display = "none";
		document.getElementById('crah').style.display = "none";

	}
	
	else if(the_sub == "LA"){
		document.getElementById('alhm').style.display = "none";
		document.getElementById('dh').style.display = "none";
		document.getElementById('ah').style.display = "none";
		document.getElementById('mo').style.display = "none";
		document.getElementById('csh').style.display = "none";
		document.getElementById('co').style.display = "none";
		document.getElementById('lo').style.display = "";
		document.getElementById('crah').style.display = "none";

	}
	else if(the_sub == "CRAA"){
		document.getElementById('alhm').style.display = "none";
		document.getElementById('dh').style.display = "none";
		document.getElementById('ah').style.display = "none";
		document.getElementById('mo').style.display = "none";
		document.getElementById('csh').style.display = "none";
		document.getElementById('co').style.display = "none";
		document.getElementById('lo').style.display = "none";
		document.getElementById('crah').style.display = "";

	}
	else {
		document.getElementById('alhm').style.display = "none";
		document.getElementById('dh').style.display = "none";
		document.getElementById('ah').style.display = "none";
		document.getElementById('mo').style.display = "none";
		document.getElementById('csh').style.display = "none";
		document.getElementById('co').style.display = "none";
		document.getElementById('lo').style.display = "none";
		document.getElementById('crah').style.display = "none";


	}


	



}
