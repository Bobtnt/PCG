
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
			var objectName = oPcgInstance.html.find(".pcgObjectHeader span").attr('value');
			oPcgInstance.name = objectName;
			aNameList.push(objectName);
			
			sReturned += objectName + "<br>";
							
			
			//check size
			oBody = oPcgInstance.html.find(".pcgObjectBody");
			allLi = 30;
			oPcgInstance.html.find("li").each(function(i){
				
				//add height of each LI
				if($(this).height() > 0){
					allLi =  allLi + $(this).height() + 3;
				}
				
				//control LI name  vs prop name
				if($(this).find('.property').val() != oPcgInstance.getProp($(this).find('.property').attr('propid')).name){
					oPcgInstance.getProp($(this).find('.property').attr('propid')).name = $(this).find('.property').val();
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

