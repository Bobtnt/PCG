/**
 * $Id $
 */
//--------------------
//    contructor
//--------------------
pcgObject = function(){
	this.html = $(this.html);
	this.addNewProp('id', 'PRIMARY');
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
	this.html.find(".propertiesBlock").sortable({
		revert : true, 
		items: 'tr:not(td a .addProperty)', 
		receive: this.receiveProp });
	this.html.find(".propertiesBlock").sortable('option', 'connectWith', '.propertiesBlock');
	this.html.find(".propertiesBlock").disableSelection();
}

pcgObject.prototype.addNewProp = function (name, type){
	oProp = new this.property(name, type, 'none', this);
	this.length = this.length + 1;
	this.properties[this.length] = oProp;
	this.properties[this.length].id = this.length;
	sName = this.properties[this.length].name;
	sType = this.properties[this.length].type;
	this.properties[this.length].html = '<tr><td class="propertyLeft"><span class="property" propId="'+ this.length +'" value="'+ sName +'" type="'+ sType +'">'+ sName +'</span> <span class="propertyType">'+ sType +'</span></td><td class="propertyRight">'
	+ '<a href="javascript:void(0);" class="changeProp">Chg</a> <a href="javascript:void(0);" class="deleteProp">Del</a> <a href="javascript:void(0);" class="renameProp">Ren</a>'
	+ '</td></tr>';
	this.properties[this.length].html = $(oProp.html); 
	return this.properties[this.length];
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
	//Case add prop in self
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
	
	$('#dialog #pcgSenderId').val(oPcgSender.id);
	$('#dialog #pcgReceiverId').val(oPcgReceiver.id);
	$('#dialog #propId').val(oProp.id);
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
	$("#dialogChangeType #pcgObjectId").val(oPcg.id);
	$("#dialogChangeType #pcgPropId").val(oProp.id);
	$("#dialogChangeType span[name='dialogMessage']").html('Select property type for ' + oPcg.name + '->' +  oProp.name)
	$("#dialogChangeType").dialog('open');
}


//--------------------
//    property object
//--------------------

pcgObject.prototype.property = function(sName, sType, sRelated, oParent){
	this.name = sName;
	this.type = sType;
	this.related = sRelated;
	this.parent = oParent;
}
pcgObject.prototype.property.prototype.name = '';
pcgObject.prototype.property.prototype.type = '';
pcgObject.prototype.property.prototype.id = 0;
pcgObject.prototype.property.prototype.related = '';
pcgObject.prototype.property.prototype.parent = void(0);
pcgObject.prototype.property.prototype.html = '';
/**
 * remove prop in parent
 */
pcgObject.prototype.property.prototype.remove = function (){
	delete this.parent.properties[this.id];
}
/**
 * change prop type
 */
pcgObject.prototype.property.prototype.changePropType = function (newType){
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


	
	
