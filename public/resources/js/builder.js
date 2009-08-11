
	
	newPcgObject = function(){
		iPcgContainerGlobalCounter = iPcgContainerGlobalCounter + 1;
		iPcgId = iPcgContainerGlobalCounter;
		aOpcgContainer[iPcgId] = new pcgObject();
		aOpcgContainer[iPcgId].setId(iPcgId);
		aOpcgContainer[iPcgId].addNewProp('id', 'PRIMARY');
		reloadEventTriggers();
	}
	
	
	
	
	reposMessagebox = function(){
		if($(".messageBox").css('top') != window.innerHeight - 30){
			$(".messageBox").css('top', window.innerHeight - 30);
		}
	}
	
	function reloadEventTriggers(){
		for(var a in aOpcgContainer){ 
			aOpcgContainer[a].show();
			aOpcgContainer[a].html.find(".pcgObjectHeader").dblclick(aOpcgContainer[a].rename);
			aOpcgContainer[a].html.find(".addProperty").click(aOpcgContainer[a].addProperty);
			aOpcgContainer[a].html.find(".renameProp").click(aOpcgContainer[a].renameProperty);
			aOpcgContainer[a].html.find(".deleteProp").click(aOpcgContainer[a].deleteProperty);
			aOpcgContainer[a].html.find(".changeProp").click(aOpcgContainer[a].openChangeTypeDialog);
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
	}

	function getPcgInstance(oDom){
		if(typeof oDom == 'number' || typeof oDom == 'string'){
			iPcgInstanceId = oDom;
		}
		else if (typeof oDom == 'object' ){
			iPcgInstanceId = $(oDom).parents('div .pcgObject').attr('pcgid');
			if(iPcgInstanceId == 'undefined'){
				return false;
			}
		}
		return aOpcgContainer[iPcgInstanceId];
	}
	
	function newRelation(oSender, oReceiver, oProp, sType){
		if(sType == 'n:m'){
			oProp1 = oSender.addNewProp(oReceiver.name + '_collection', 'collection');
			oSender.html.find('table').prepend(oProp1.html);
			oProp2 = oReceiver.addNewProp(oSender.name + '_collection', 'collection');
			oReceiver.html.find('table').prepend(oProp2.html);
			reloadEventTriggers();
			
			var pos1 = oProp1.html.offset();
			var pos2 = oProp2.html.offset();
			
			if(pos1.left > pos2.left){
				var coord = {x1: pos1.left, y1: pos1.top + 10 , x2: pos2.left + oProp2.html.width() ,y2: pos2.top + 10 }
			}
			else{
				var coord = {x1: pos1.left + oProp1.html.width() , y1: pos1.top + 10, x2: pos2.left, y2: pos2.top + 10 }
			}
			
			iGrapherCounter = iGrapherCounter + 1;
			grapherName = 'grapher'+iGrapherCounter;
			aGrapherDivContainer[iGrapherCounter] = '<div id="'+grapherName+'"></div>';
			aGrapherDivContainer[iGrapherCounter] = $(aGrapherDivContainer[iGrapherCounter]);
			aGrapherDivContainer[iGrapherCounter].css('z-index','-1');
			$('#svgcontainer').append(aGrapherDivContainer[iGrapherCounter]);
			aGrapherContainer[iGrapherCounter] = new jsGraphics(document.getElementById(grapherName));
			aGrapherContainer[iGrapherCounter].setColor("#ddddff");
			aGrapherContainer[iGrapherCounter].setStroke(2);
			aGrapherContainer[iGrapherCounter].drawLine(coord.x1, coord.y1, coord.x2, coord.y2);
			
			oSender.attachDrawUI(oSender, oProp1.id, oReceiver, oProp2.id, aGrapherContainer[iGrapherCounter]);
			oReceiver.attachDrawUI(oSender, oProp1.id, oReceiver, oProp2.id, aGrapherContainer[iGrapherCounter]);
			
			aGrapherContainer[iGrapherCounter].paint();

		}
		
	}

