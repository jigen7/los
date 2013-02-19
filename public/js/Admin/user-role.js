/**
 * @author Paolo Marco Manarang
 */



function displayOfficer(value){
	// Selection for Civil Status
	
	if(value == ""){
	document.getElementById('marketing-mh').style.display = "none";
	document.getElementById('marketing-dh').style.display = "none";
	document.getElementById('marketing-ah').style.display = "none";
	document.getElementById('marketing-mo').style.display = "none";
		
	document.getElementById('creditsrv-co').style.display = "none";
	document.getElementById('creditsrv-csh').style.display = "none";
		
	document.getElementById('creditmgt-crmh').style.display = "none";
	document.getElementById('creditmgt-cro').style.display = "none";

	document.getElementById('loans-ldh').style.display = "none";
	document.getElementById('loans-ldo').style.display = "none";
	}
	else if(value == "MA"){
	document.getElementById('marketing-mh').style.display = "";
	document.getElementById('marketing-dh').style.display = "";
	document.getElementById('marketing-ah').style.display = "";
	document.getElementById('marketing-mo').style.display = "";
		
	document.getElementById('creditsrv-co').style.display = "none";
	document.getElementById('creditsrv-csh').style.display = "none";
		
	document.getElementById('creditmgt-crmh').style.display = "none";
	document.getElementById('creditmgt-cro').style.display = "none";

	document.getElementById('loans-ldh').style.display = "none";
	document.getElementById('loans-ldo').style.display = "none";
		
	
	}
	
	else if(value == "MH"){ //Marketing Head
	document.getElementById('marketing-mh').style.display = "none";
	document.getElementById('marketing-dh').style.display = "none";
	document.getElementById('marketing-ah').style.display = "none";
	document.getElementById('marketing-mo').style.display = "none";
		
	document.getElementById('creditsrv-co').style.display = "none";
	document.getElementById('creditsrv-csh').style.display = "none";
		
	document.getElementById('creditmgt-crmh').style.display = "none";
	document.getElementById('creditmgt-cro').style.display = "none";

	document.getElementById('loans-ldh').style.display = "none";
	document.getElementById('loans-ldo').style.display = "none";
	}
		
	else if(value == "DH"){ // Diviusion Head
	document.getElementById('marketing-mh').style.display = "";
	document.getElementById('marketing-dh').style.display = "none";
	document.getElementById('marketing-ah').style.display = "none";
	document.getElementById('marketing-mo').style.display = "none";
		
	document.getElementById('creditsrv-co').style.display = "none";
	document.getElementById('creditsrv-csh').style.display = "none";
		
	document.getElementById('creditmgt-crmh').style.display = "none";
	document.getElementById('creditmgt-cro').style.display = "none";

	document.getElementById('loans-ldh').style.display = "none";
	document.getElementById('loans-ldo').style.display = "none";
	}
	
	else if(value == "AH"){ // Area Head
	document.getElementById('marketing-mh').style.display = "";
	document.getElementById('marketing-dh').style.display = "";
	document.getElementById('marketing-ah').style.display = "none";
	document.getElementById('marketing-mo').style.display = "none";
		
	document.getElementById('creditsrv-co').style.display = "none";
	document.getElementById('creditsrv-csh').style.display = "none";
		
	document.getElementById('creditmgt-crmh').style.display = "none";
	document.getElementById('creditmgt-cro').style.display = "none";

	document.getElementById('loans-ldh').style.display = "none";
	document.getElementById('loans-ldo').style.display = "none";
	}
	
	else if(value == "MO"){ // Marketing Head
	document.getElementById('marketing-mh').style.display = "";
	document.getElementById('marketing-dh').style.display = "";
	document.getElementById('marketing-ah').style.display = "";
	document.getElementById('marketing-mo').style.display = "none";
		
	document.getElementById('creditsrv-co').style.display = "none";
	document.getElementById('creditsrv-csh').style.display = "none";
		
	document.getElementById('creditmgt-crmh').style.display = "none";
	document.getElementById('creditmgt-cro').style.display = "none";

	document.getElementById('loans-ldh').style.display = "none";
	document.getElementById('loans-ldo').style.display = "none";
	}
	else if(value == "CA"){ // Credit Assistant
		
	document.getElementById('marketing-mh').style.display = "none";
	document.getElementById('marketing-dh').style.display = "none";
	document.getElementById('marketing-ah').style.display = "none";
	document.getElementById('marketing-mo').style.display = "none";
		
	document.getElementById('creditsrv-co').style.display = "";
	document.getElementById('creditsrv-csh').style.display = "";
		
	document.getElementById('creditmgt-crmh').style.display = "none";
	document.getElementById('creditmgt-cro').style.display = "none";

	document.getElementById('loans-ldh').style.display = "none";
	document.getElementById('loans-ldo').style.display = "none";

	}
	
	else if(value == "CI"){ // Credit Investigator
	
	document.getElementById('marketing-mh').style.display = "none";
	document.getElementById('marketing-dh').style.display = "none";
	document.getElementById('marketing-ah').style.display = "none";
	document.getElementById('marketing-mo').style.display = "none";
		
	document.getElementById('creditsrv-co').style.display = "";
	document.getElementById('creditsrv-csh').style.display = "";
		
	document.getElementById('creditmgt-crmh').style.display = "none";
	document.getElementById('creditmgt-cro').style.display = "none";

	document.getElementById('loans-ldh').style.display = "none";
	document.getElementById('loans-ldo').style.display = "none";

	}
	
	else if(value == "CO"){ // Credit Officer
		
	document.getElementById('marketing-mh').style.display = "none";
	document.getElementById('marketing-dh').style.display = "none";
	document.getElementById('marketing-ah').style.display = "none";
	document.getElementById('marketing-mo').style.display = "none";
		
	document.getElementById('creditsrv-co').style.display = "none";
	document.getElementById('creditsrv-csh').style.display = "";
		
	document.getElementById('creditmgt-crmh').style.display = "none";
	document.getElementById('creditmgt-cro').style.display = "none";

	document.getElementById('loans-ldh').style.display = "none";
	document.getElementById('loans-ldo').style.display = "none";

	}
	
	else if(value == "CSH"){ //Credit Service Head
		
	document.getElementById('marketing-mh').style.display = "none";
	document.getElementById('marketing-dh').style.display = "none";
	document.getElementById('marketing-ah').style.display = "none";
	document.getElementById('marketing-mo').style.display = "none";
		
	document.getElementById('creditsrv-co').style.display = "none";
	document.getElementById('creditsrv-csh').style.display = "none";
		
	document.getElementById('creditmgt-crmh').style.display = "none";
	document.getElementById('creditmgt-cro').style.display = "none";

	document.getElementById('loans-ldh').style.display = "none";
	document.getElementById('loans-ldo').style.display = "none";


	}	
	
	
	else if(value == "CRAA"){ //Credit Risk Analytics Associate
		
	document.getElementById('marketing-mh').style.display = "none";
	document.getElementById('marketing-dh').style.display = "none";
	document.getElementById('marketing-ah').style.display = "none";
	document.getElementById('marketing-mo').style.display = "none";
		
	document.getElementById('creditsrv-co').style.display = "";
	document.getElementById('creditsrv-csh').style.display = "";
		
	document.getElementById('creditmgt-crmh').style.display = "none";
	document.getElementById('creditmgt-cro').style.display = "none";

	document.getElementById('loans-ldh').style.display = "none";
	document.getElementById('loans-ldo').style.display = "none";

	}	
	else if(value == "CRAH"){ // Credit Risk Analytics Head
		
	document.getElementById('marketing-mh').style.display = "none";
	document.getElementById('marketing-dh').style.display = "none";
	document.getElementById('marketing-ah').style.display = "none";
	document.getElementById('marketing-mo').style.display = "none";
		
	document.getElementById('creditsrv-co').style.display = "none";
	document.getElementById('creditsrv-csh').style.display = "none";
		
	document.getElementById('creditmgt-crmh').style.display = "none";
	document.getElementById('creditmgt-cro').style.display = "none";

	document.getElementById('loans-ldh').style.display = "none";
	document.getElementById('loans-ldo').style.display = "none";

	}	

	else if(value == "LA"){ // Loans Assistant
		
	document.getElementById('marketing-mh').style.display = "none";
	document.getElementById('marketing-dh').style.display = "none";
	document.getElementById('marketing-ah').style.display = "none";
	document.getElementById('marketing-mo').style.display = "none";
		
	document.getElementById('creditsrv-co').style.display = "none";
	document.getElementById('creditsrv-csh').style.display = "none";
		
	document.getElementById('creditmgt-crmh').style.display = "none";
	document.getElementById('creditmgt-cro').style.display = "none";

	document.getElementById('loans-ldh').style.display = "";
	document.getElementById('loans-ldo').style.display = "";

	}	
	
	else if(value == "LO"){ // Loans Officer
		
	document.getElementById('marketing-mh').style.display = "none";
	document.getElementById('marketing-dh').style.display = "none";
	document.getElementById('marketing-ah').style.display = "none";
	document.getElementById('marketing-mo').style.display = "none";
		
	document.getElementById('creditsrv-co').style.display = "none";
	document.getElementById('creditsrv-csh').style.display = "none";
		
	document.getElementById('creditmgt-crmh').style.display = "none";
	document.getElementById('creditmgt-cro').style.display = "none";

	document.getElementById('loans-ldh').style.display = "none";
	document.getElementById('loans-ldo').style.display = "";

	}	
	else if(value == "LH"){ // L&D Head
		document.getElementById('marketing-mh').style.display = "none";
	document.getElementById('marketing-dh').style.display = "none";
	document.getElementById('marketing-ah').style.display = "none";
	document.getElementById('marketing-mo').style.display = "none";
		
	document.getElementById('creditsrv-co').style.display = "none";
	document.getElementById('creditsrv-csh').style.display = "none";
		
	document.getElementById('creditmgt-crmh').style.display = "none";
	document.getElementById('creditmgt-cro').style.display = "none";

	document.getElementById('loans-ldh').style.display = "none";
	document.getElementById('loans-ldo').style.display = "none";

	}	
	

}
