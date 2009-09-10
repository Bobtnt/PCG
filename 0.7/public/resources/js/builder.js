
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
		var oProp = aOpcgContainer[iPcgId].addNewProp('id', 'PRIMARY');
		aOpcgContainer[iPcgId].html.find('ul').prepend(oProp.html);
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
			aOpcgContainer[a].html.find(".deleteObject").click(aOpcgContainer[a].helpers.remove);
			aOpcgContainer[a].html.find(".reduceObject").toggle(aOpcgContainer[a].helpers.colapse, aOpcgContainer[a].helpers.expand);
			
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
			
			if(checkUniqueRelation(oSender, oReceiver, sType)){
				return false;
			}
			
			var color = '#4FB47D';
			oProp1 = oSender.addNewProp(oReceiver.name + '_collection', 'collection');
			oSender.html.find('ul').prepend(oProp1.html);
			oProp2 = oReceiver.addNewProp(oSender.name + '_collection', 'collection');
			oReceiver.html.find('ul').prepend(oProp2.html);
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
		else if(sType == '1:n'){
			var color = '#DF5BA7';
			oProp1 = oReceiver.addNewProp(oSender.name+'_2rename', 'object');
			oReceiver.html.find('ul').prepend(oProp1.html);
			oProp2 = oSender.getProp(1); // get primary key
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
			oSender.attachDrawUI(oReceiver, oProp1.id, oSender, oProp2.id, aGrapherContainer[iGrapherCounter]);
			oReceiver.attachDrawUI(oReceiver, oProp1.id, oSender, oProp2.id, aGrapherContainer[iGrapherCounter]);
			oSender.executeBinderUI();
		}
		else if('1:1'){
			var color = '#F7DD27';
			oProp1 = oReceiver.addNewProp(oSender.name, 'object');
			oReceiver.html.find('ul').prepend(oProp1.html);
			oProp2 = oSender.getProp(1); // get primary key
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
			oSender.attachDrawUI(oReceiver, oProp1.id, oSender, oProp2.id, aGrapherContainer[iGrapherCounter]);
			oReceiver.attachDrawUI(oReceiver, oProp1.id, oSender, oProp2.id, aGrapherContainer[iGrapherCounter]);
			oReceiver.executeBinderUI();
		}
		
		if(typeof oProp1.related == 'string'){
			oProp1.related = new Array;
		}
		oProp1.related.push({to:  oProp2, type: sType });
		if(typeof oProp2.related == 'string'){
			oProp2.related = new Array;
		}
		oProp2.related.push({to:  oProp1, type: sType });
		
	}

	
	function checkUniqueRelation(oPcg1, oPcg2, sType){
		
		//they can be only one n:m relationship between two objects
		if(sType == 'n:m'){
			for(var a in oPcg1.properties){
				for(var b in oPcg1.properties[a].related){
					for(var c in oPcg2.properties){
						if(oPcg1.properties[a].related[b].to == oPcg2.properties[c]){
							return true;
						}
					}
				}
			}
		}
		// no restriction on 1:n relation
		else if(sType == '1:n'){
		
		}
		// no restriction on 1:1 relation
		else if(sType == '1:1'){
		
		}
		return false;
	}
