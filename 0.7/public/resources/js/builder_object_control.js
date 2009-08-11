
pcgObjectControl = function(){
	var sReturned = '';
	var aNameList = new Array;
	
	
	//Iter all pcg objects
	for(var a in aOpcgContainer){ 
		var aPropsList = new Array;
		
		//object name
		console.log(a);
		var iPcgId = aOpcgContainer[a].id;
		var oPcgInstance = aOpcgContainer[a];
		var objectName = oPcgInstance.html.find(".pcgObjectHeader span").attr('value');
		
		oPcgInstance.name = objectName;
		aNameList.push(objectName);		
		sReturned += objectName + "<br>";

		//object properties
//		oPcgInstance.html.find("td .property").each(function(i){
//			var sPropName = $(this).attr('value');
//			var sPropType = $(this).attr('type');
//			var iPropId = $(this).attr('propid');
//			var oProp = oPcgInstance.properties[iPropId];
//			if(oProp.name != sPropName){
//				oProp.name = sPropName
//			}
//			if(oProp.type != sPropType){
//				oProp.type = sPropType
//			}
//			sReturned += '- '+ sPropName + '<br>';
//		});
		
		for(var b in aOpcgContainer[a].properties){
			var checkedProp = aOpcgContainer[a].properties[b];
			console.log(iPcgId + ' = ' + checkedProp.parent.id);
			if(iPcgId != checkedProp.parent.id){
				checkedProp.parent = aOpcgContainer[a];
				console.log('retaked ' + iPcgId + ' = ' + checkedProp.parent.id);
			}
		}
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

