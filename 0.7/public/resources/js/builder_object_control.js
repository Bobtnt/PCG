
pcgObjectControl = function(){
	var sReturned = '';
	var aNameList = new Array;
	
	
	//Iter all pcg objects
	for(var a in aOpcgContainer){ 
		var aPropsList = new Array;
		
		//object name
		var oPcgInstance = aOpcgContainer[a];
		var objectName = oPcgInstance.html.find(".pcgObjectHeader span").attr('value');
		
		oPcgInstance.name = objectName;
		aNameList.push(objectName);		
		sReturned += objectName + "<br>";

		//object properties
		oPcgInstance.html.find("td .property").each(function(i){
			sPropName = $(this).attr('value');
			sPropType = $(this).attr('type');
			iPropId = $(this).attr('propid');
			oProp = oPcgInstance.properties[iPropId];
			if(oProp.name != sPropName){
				oProp.name = sPropName
			}
			if(oProp.type != sPropType){
				oProp.type = sPropType
			}			
			sReturned += '- '+ sPropName + '<br>';
		});
		
	}	
	// Check for non unique object name
	bDoubleName = false;
	for ( var a = 0; a < aNameList.length; a++) {
		var sName = aNameList[a];
		for ( var b = 0; b < aNameList.length; b++) {
			if(a != b && sName ==  aNameList[b]){
				bDoubleName = true;
			}
		}		
	}
	
	if(bDoubleName){
		$('.messageBox').html('<span style="color:red">Warning</span> Some PCG objects have the same name');
	}
	else{
		$('.messageBox').html('Messages:');
	}
	
	$('#controlerDebug').html( sReturned );
	//return void(0);
}

