//Globals
aModifiedElements = new Array();


//Tools bar action
newPcgObject = function(){
	var html = '<div class="pcgObject">'
			+  '<div class="pcgObjectHeader">'
			+  '<span value="New object">New object</span>'
			+  '</div>'
			+  '<div class="pcgObjectBody">'
			+  '<table class="propertiesBlock">'
			+  '<tr><td class="propertyLeft"><span class="property" value="id">id</span></td><td class="propertyRight">Del <a href="javascript:void(0);" class="renameProp">Ren</a></td></tr>'
			+  '<tr><td colspan="2"><a href="javascript:void(0);" class="addProperty">Add</a></td></tr>'
			+  '</table>'
			+  '</div>'
			+  '</div>';
	var jhtml = $(html);
	$(".canvas").append($(jhtml));	
	reloadEventTriggers();
}

newRelationObject = function(type){
}

deleteObject = function(){
}

//Objects actions

renameObject = function(){
	convertIntoInput(this.firstChild);	
}
renameProperty = function(){
	var oDom = this.parentNode.previousSibling.firstChild
	convertIntoInput(oDom);
}
addProperty = function(){	
	$(this).parent().parent().before('<tr><td class="propertyLeft"><span class="property" value="New prop">New prop</span></td><td class="propertyRight">Del <a href="javascript:void(0);" class="renameProp">Ren</a></td></tr>');
	reloadEventTriggers();
}


function convertIntoInput(oDOM){	
	var mValue = $(oDOM).attr('value');
	var html = '<input type="text" value="'+ mValue +'" />';
	var storedDOM = new Object;	
	storedDOM.content = $(oDOM).parent().html();
	storedDOM.parent = $(oDOM).parent();
	
	var oHtml = $(html);
	//Set Event (blur and return)
	oHtml.blur(returnInputToInitialState);
	oHtml.keydown(function(e){if(e.keyCode == 13) {returnInputToInitialState();}});
	
	storedDOM.input = oHtml;
	$(oDOM).parent().html(storedDOM.input);	
		
	aModifiedElements.push(storedDOM);	
}

returnInputToInitialState = function(){
	for(var a in aModifiedElements){
		var sVal = aModifiedElements[a].input.val();
		var oHtml = $(aModifiedElements[a].content);
		oHtml.attr('value', sVal);
		oHtml.html(sVal);
		var oParent = aModifiedElements[a].parent;
		oParent.html(oHtml);
		delete aModifiedElements[a];
	}
	
}