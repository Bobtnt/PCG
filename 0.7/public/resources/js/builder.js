
	/**
	 * create new pcg object
	 * 
	 * @type pcgObject
	 * @return new PcgObject
	 */
	function newPcgObject(){
		iPcgContainerGlobalCounter = iPcgContainerGlobalCounter + 1;
		iPcgId = iPcgContainerGlobalCounter;
		aOpcgContainer[iPcgId] = new pcgObject();
		aOpcgContainer[iPcgId].setId(iPcgId);
		aOpcgContainer[iPcgId].addNewProp('id', 'PRIMARY');
		aOpcgContainer[iPcgId].placeOnScreen();
		reloadEventTriggers();
		
		map.addObject(aOpcgContainer[iPcgId]);
		return aOpcgContainer[iPcgId];
	}
	
	
	
	
	reposMessagebox = function(){
		if($(".messageBox").css('top') != window.innerHeight - 45 + $(document).scrollTop() ){
			$(".messageBox").css('top', window.innerHeight - 45 + $(document).scrollTop());
		}
		if($(".messageBox").css('left') != $(document).scrollLeft() + 5){
			$(".messageBox").css('left', $(document).scrollLeft() + 5);
		}
	};
	
	function reloadEventTriggers(){
		for(var a in aOpcgContainer){ 
			aOpcgContainer[a].html.find(".pcgObjectHeader").dblclick(aOpcgContainer[a].helpers.rename);
			aOpcgContainer[a].html.find(".addProperty").click(aOpcgContainer[a].helpers.addProperty);
			aOpcgContainer[a].html.find(".renameProp").click(aOpcgContainer[a].helpers.renameProperty);
			aOpcgContainer[a].html.find(".deleteProp").click(aOpcgContainer[a].helpers.deleteProperty);
			aOpcgContainer[a].html.find(".changeProp").click(aOpcgContainer[a].helpers.openChangeTypeDialog);
		}
	}
	
	
	function convertIntoInput(oDOM){	
		var mValue = $(oDOM).attr('value');
		var html = '<input id="edited" type="text" value="'+ mValue +'" />';
		var storedDOM = new Object;	
		storedDOM.content = $(oDOM);
		storedDOM.parent = $(oDOM).parent();
		
		var oHtml = $(html);
		//Set Event (blur and return)
		oHtml.blur(returnInputToInitialState);
		oHtml.keydown(function(e){if(e.keyCode == 13) {returnInputToInitialState();}});
		
		storedDOM.input = oHtml;
		$(oDOM).replaceWith(storedDOM.input);	
			
		aModifiedElements.push(storedDOM);	
	}
	returnInputToInitialState = function(){
		for(var a in aModifiedElements){
			var sVal = aModifiedElements[a].input.val();
			var oHtml = $(aModifiedElements[a].content);
			oHtml.attr('value', sVal);
			oHtml.html(sVal);
			var oParent = aModifiedElements[a].parent;
			oParent.find("#edited").replaceWith(oHtml);
			delete aModifiedElements[a];
		}
	};
	/**
	 * 
	 * @param {pcgObject} oDom
	 * @return
	 */
	function getPcgInstance(oDom){
		if(typeof oDom == 'number' || typeof oDom == 'string'){
			var iPcgInstanceId = oDom;
		}
		else if (typeof oDom == 'object' ){
			var rx = new RegExp('pcgObject($| )', 'g');
			if(oDom instanceof pcgObject){
				iPcgInstanceId = oDom.id;
			}			
			else if(rx.test($(oDom).get(0).className)){
				var iPcgInstanceId = $(oDom).attr('pcgid');
			}
			else{
				var iPcgInstanceId = $(oDom).parents('div .pcgObject').attr('pcgid');
			}					
			
		}
		if(iPcgInstanceId == 'undefined'){
			return false;
		}		
		return aOpcgContainer[iPcgInstanceId];
	}
	
	function newRelation(oSender, oReceiver, oProp, sType){
		if(sType == 'n:m'){
			var color = '#4FB47D';
		}
		else if(sType == '1:n'){
			var color = '#DF5BA7';
		}
		else if('1:1'){
			var color = '#F7DD27';
		}
		
		oProp1 = oSender.addNewProp(oReceiver.name + '_collection', 'collection');
		oSender.html.find('table').prepend(oProp1.html);
		oProp2 = oReceiver.addNewProp(oSender.name + '_collection', 'collection');
		oReceiver.html.find('table').prepend(oProp2.html);
		reloadEventTriggers();
		
		iGrapherCounter = iGrapherCounter + 1;
		grapherName = 'grapher'+iGrapherCounter;
		aGrapherDivContainer[iGrapherCounter] = '<div id="'+grapherName+'"></div>';
		aGrapherDivContainer[iGrapherCounter] = $(aGrapherDivContainer[iGrapherCounter]);
		aGrapherDivContainer[iGrapherCounter].css('z-index','-1');
		$('#svgcontainer').append(aGrapherDivContainer[iGrapherCounter]);
		aGrapherContainer[iGrapherCounter] = new jsGraphics(document.getElementById(grapherName));
		aGrapherContainer[iGrapherCounter].setColor(color);
		aGrapherContainer[iGrapherCounter].setStroke(2);
					
		oSender.attachDrawUI(oSender, oProp1.id, oReceiver, oProp2.id, aGrapherContainer[iGrapherCounter]);
		oReceiver.attachDrawUI(oSender, oProp1.id, oReceiver, oProp2.id, aGrapherContainer[iGrapherCounter]);
		
		oReceiver.executeBinderUI();
		
	}

