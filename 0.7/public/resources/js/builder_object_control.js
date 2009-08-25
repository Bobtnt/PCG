
pcgObjectControl = function(){
	var sReturned = '';
	var aNameList = new Array;
	
	
	//Iter all pcg objects
	for(var a in aOpcgContainer){ 
		var aPropsList = new Array;
		
		//object name
		var iPcgId = aOpcgContainer[a].id;
		/**
		 * @type {pcgObject} oPcgInstance
		 */
		var oPcgInstance = aOpcgContainer[a];
		
		if(oPcgInstance.controled){
			var objectName = oPcgInstance.html.find(".pcgObjectHeader .pcgObjectName").attr('value');
			sReturned += objectName + "<br>";
			oPcgInstance.name = objectName;
			aNameList.push(objectName);		
			
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
			
	//		for(var b in aOpcgContainer[a].properties){
	//			var checkedProp = aOpcgContainer[a].properties[b];
	//			if(iPcgId != checkedProp.parent.id){
	//				checkedProp.parent = aOpcgContainer[a];
	//			}
	//		}
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
};

