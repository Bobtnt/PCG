
	
	newPcgObject = function(){
		iPcgContainerGlobalCounter = iPcgContainerGlobalCounter + 1;
		iPcgId = iPcgContainerGlobalCounter;
		aOpcgContainer[iPcgId] = new pcgObject();
		
		aOpcgContainer[iPcgId].setId(iPcgId);
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

