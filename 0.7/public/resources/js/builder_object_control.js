
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
			
			//check size
			oBody = oPcgInstance.html.find(".pcgObjectBody");
			allLi = 30;
			oPcgInstance.html.find("li").each(function(i){
				if($(this).height() > 0){
					allLi =  allLi + $(this).height() + 3;
				}
			});
				
			if(oBody.height() < allLi){
				oPcgInstance.html.find(".pcgObject").height(allLi+10);
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
};

