
pcgObjectControl = function(){
	var sReturned = '';
	var aNameList = new Array;
	for(var a in aOpcgContainer){ 
		var aPropsList = new Array;
		
		//object name
		var objectName = aOpcgContainer[a].html.find(".pcgObjectHeader span").attr('value');
		aOpcgContainer[a].name = objectName;
		aNameList.push(objectName);		
		sReturned += objectName + "<br>";		
		
		//object properties
		aOpcgContainer[a].html.find("td span").each(function(i){
			aPropsList.push($(this).attr('value'));
			sReturned += '- '+ $(this).attr('value') + '<br>';
		});
		aOpcgContainer[a].properties = new Array;
		for (var b in aPropsList){
			aOpcgContainer[a].properties.push({name : aPropsList[b], related : 'none'});
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
		$('.messageBox').html('<span style="color:red">Warning</span> Some PCG object have the same name');
	}
	else{
		$('.messageBox').html('');
	}
	
	$('#controlerDebug').html( sReturned );
	//return void(0);
}

