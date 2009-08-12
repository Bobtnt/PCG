/**
 * $Id $
 */
//--------------------
//    contructor
//--------------------
pcgObject = function(){
	this.html = $(this.html);
	this.show();
	//this.addNewProp('id', 'PRIMARY');
}
//--------------------
//    properties
//--------------------
pcgObject.prototype.id = 0;
pcgObject.prototype.name = 'New object';
pcgObject.prototype.html = '<div><div class="pcgObject" pcgId="0">'
			+  '<div class="pcgObjectHeader">'
			+  '<span value="New object">New object</span>'
			+  '</div>'
			+  '<div class="pcgObjectBody">'
			+  '<table class="propertiesBlock">'
			+  '<tr><td class="propertyLeft"><span class="property" value="id" propid="1" type="PRIMARY">id</span></td><td class="propertyRight">PRIMARY</td></tr>'
			+  '<tr><td colspan="2"><a href="javascript:void(0);" class="addProperty">Add</a></td></tr>'
			+  '</table>'
			+  '</div>'
			+  '</div></div>';
pcgObject.prototype.properties = new Array;
pcgObject.prototype.length = 0;
pcgObject.prototype.internalFunctions = new Array;
pcgObject.prototype.internalFunctionsArguments = new Array;
pcgObject.prototype.internalFunctionsCounter = 0;
//--------------------
//    methods
//--------------------

/**
 * Setter fo id
 */
pcgObject.prototype.setId = function(iId){
	this.id = iId;
	this.html.find(".pcgObject").attr("pcgId", iId);
}
/**
 * return property by id
 */
pcgObject.prototype.getProperty = function (id){
	if(this.properties[id]){
		return this.properties[id];
	}
	else{
		return false;
	}
}
/**
 * alias of getProperty
 */
pcgObject.prototype.getProp = function (id){
	return this.getProperty(id);
}
/**
 * display object in canvas
 */
pcgObject.prototype.show = function(){
	$(".canvas").append(this.html);
	this.reloadUI();
}
/**
 * reload UI object event  
 */
pcgObject.prototype.reloadUI = function(){	
	this.html.find(".pcgObject").resizable();
	this.html.find(".pcgObject").draggable({ handle: '.pcgObjectHeader' });
	this.html.find(".pcgObject").bind('drag', this.executeBinderUI);
	this.html.find(".propertiesBlock").sortable({
		revert : true, 
		items: 'tr:not(td a .addProperty)', 
		receive: this.receiveProp });
	this.html.find(".propertiesBlock").sortable('option', 'connectWith', '.propertiesBlock');
	this.html.find(".propertiesBlock").disableSelection();
}
pcgObject.prototype.executeBinderUI = function(event, ui){
	pcgInstance = getPcgInstance(this.firstChild);
	for(var a in pcgInstance.internalFunctions){
		var bindedFunction = pcgInstance.internalFunctions[a];
		var arguments = pcgInstance.internalFunctionsArguments[a];
		bindedFunction(arguments);
		
	}
}
/**
 * 
 */
pcgObject.prototype.attachDrawUI = function(oPcg1, iProp1, oPcg2, iProp2,  oGrapher){
	this.internalFunctionsCounter = this.internalFunctionsCounter + 1;
	this.internalFunctionsArguments[this.internalFunctionsCounter] = {o1 : oPcg1, p1: iProp1, o2: oPcg2, p2: iProp2, grapher: oGrapher};
	this.internalFunctions[this.internalFunctionsCounter] = function(arguments){
		arguments.grapher.clear();
		
		var pos1 = arguments.o1.getProp(arguments.p1).html.offset();
		var pos2 = arguments.o2.getProp(arguments.p2).html.offset();
	
		if(pos1.left > pos2.left){
			var coord = {x1: pos1.left, y1: pos1.top + 10, x2: pos2.left + oProp2.html.width() ,y2: pos2.top + 10 }
		}
		else{
			var coord = {x1: pos1.left + oProp1.html.width() , y1: pos1.top + 10, x2: pos2.left, y2: pos2.top + 10 }
		}
		arguments.grapher.drawLine(coord.x1, coord.y1, coord.x2, coord.y2);
		arguments.grapher.paint();
		
	}
}

/**
 * adding new property
 */
pcgObject.prototype.addNewProp = function (name, type){
	var iPcgId = this.id; 
	var thisInstance = aOpcgContainer[iPcgId]; 
	var oProp = new property(name, type, 'none', thisInstance);
	thisInstance.length = thisInstance.length + 1;
	thisInstance.properties[thisInstance.length] = oProp;
	thisInstance.properties[thisInstance.length].id = thisInstance.length;
	var sName = thisInstance.properties[thisInstance.length].name;
	var sType = thisInstance.properties[thisInstance.length].type;
	thisInstance.properties[thisInstance.length].html = '<tr><td class="propertyLeft"><span class="property" propId="'+ thisInstance.length +'" value="'+ sName +'" type="'+ sType +'">'+ sName +'</span> <span class="propertyType">'+ sType +'</span></td><td class="propertyRight">'
	+ '<a href="javascript:void(0);" class="changeProp">Chg</a> <a href="javascript:void(0);" class="deleteProp">Del</a> <a href="javascript:void(0);" class="renameProp">Ren</a>'
	+ '</td></tr>';
	thisInstance.properties[thisInstance.length].html = $(oProp.html); 
	return thisInstance.properties[thisInstance.length];
}

//--------------------
//    HELPERS
//--------------------

/**
 * Load rename object
 */
pcgObject.prototype.rename = function(){
	convertIntoInput(this.firstChild);
}
/**
 * Add property
 */
pcgObject.prototype.addProperty = function(){
	oPcg = getPcgInstance(this);
	if(oPcg){
		oProp = oPcg.addNewProp('New prop', 'auto');
		$(this).parent().parent().before(oProp.html);
		reloadEventTriggers();
	}
}
/**
 * Load rename property
 */
pcgObject.prototype.renameProperty = function(){
	var oDom = this.parentNode.previousSibling.firstChild
	convertIntoInput(oDom);
}
/**
 * receive property helper
 */
pcgObject.prototype.receiveProp = function (event, ui){
	
	oPcgReceiver = getPcgInstance(ui.item.find(".property"));
	iPropId = ui.item.find(".property").attr('propid');
	oPcgSender = getPcgInstance($(ui.sender));
	oProp = oPcgSender.getProp(iPropId);
	$(ui.sender).sortable('cancel');
	if(oProp.type != 'PRIMARY'){
		return this;
	}
	$('#dialog #dialogType').val('relation')
	$('#dialog #propType').hide();
	$('#dialog #relationType').show();
	$('#dialog #pcgSenderId').val(oPcgSender.id);
	$('#dialog #pcgReceiverId').val(oPcgReceiver.id);
	$('#dialog #propId').val(oProp.id);
	$('#dialog').attr('title', 'Select Relation type');
	$("#dialog span[name='dialogMessage']").html('Select property type for ' + oProp.name);
	$("#dialog span[name='dialogMessage']").html(' Link '+ oPcgSender.name + ' with ' + oPcgReceiver.name );
	$('#dialog').dialog('open');
	
}
/**
 * Delete prop
 */
pcgObject.prototype.deleteProperty = function (){
	oPcg = getPcgInstance(this);
	iPropId = $(this).parent().parent().find(".property").attr('propid');
	oProp = oPcg.properties[iPropId];
	oProp.remove();
	$(this).parent().parent().remove();
}
/**
 * open change type dialog
 */
pcgObject.prototype.openChangeTypeDialog = function (){
	oPcg = getPcgInstance(this);
	iPropId = $(this).parent().parent().find(".property").attr('propid');
	oProp = oPcg.properties[iPropId];
	$('#dialog #dialogType').val('changeType');
	$('#dialog #propType').show();
	$('#dialog #relationType').hide();
	$("#dialog #pcgObjectId").val(oPcg.id);
	$("#dialog #pcgPropId").val(oProp.id);
	$('#dialog').attr('title', 'Select type');
	$("#dialog span[name='dialogMessage']").html('Select property type for ' + oPcg.name + '->' +  oProp.name)
	$("#dialog").dialog('open');
}


//--------------------
//    property object
//--------------------

property = function(sName, sType, sRelated, oParent){
	this.name = sName;
	this.type = sType;
	this.related = sRelated;
	this.parent = oParent;
	return this;
}
property.prototype.name = '';
property.prototype.type = '';
property.prototype.id = 0;
property.prototype.related = '';
property.prototype.parent = void(0);
property.prototype.html = '';
/**
 * remove prop in parent
 */
property.prototype.remove = function (){
	delete this.parent.properties[this.id];
}
/**
 * change prop type
 */
property.prototype.changePropType = function (newType){
	this.html.find('.property').attr('type', newType);
	this.html.find('.propertyType').html(newType);
	this.type = newType;
}


function object_dump(obj) {
	var returned = '';
	for (var prop in obj) {
		returned += "O." + prop + " = " + obj[prop] + "\n";
	}
	return returned;
}


	
	
