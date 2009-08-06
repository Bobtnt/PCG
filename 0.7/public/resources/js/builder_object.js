/**
 * $Id $
 */
//--------------------
//    contructor
//--------------------
pcgObject = function(){
	this.html = $(this.html);
	this.properties.push({name: 'id', related: 'none'});
}
//--------------------
//    properties
//--------------------
pcgObject.prototype.name = 'New object';
pcgObject.prototype.html = '<div><div class="pcgObject">'
			+  '<div class="pcgObjectHeader">'
			+  '<span value="New object">New object</span>'
			+  '</div>'
			+  '<div class="pcgObjectBody">'
			+  '<table class="propertiesBlock">'
			+  '<tr><td class="propertyLeft"><span class="property" value="id">id</span></td><td class="propertyRight"><a href="javascript:void(0);" class="deleteProp">Del</a> <a href="javascript:void(0);" class="renameProp">Ren</a></td></tr>'
			+  '<tr><td colspan="2"><a href="javascript:void(0);" class="addProperty">Add</a></td></tr>'
			+  '</table>'
			+  '</div>'
			+  '</div></div>';
pcgObject.prototype.properties = new Array;
//--------------------
//    methods
//--------------------
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
		receive: receiveProp });
	this.html.find(".propertiesBlock").sortable('option', 'connectWith', '.propertiesBlock');
	this.html.find(".propertiesBlock").disableSelection();	
}
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
	$(this).parent().parent().before('<tr><td class="propertyLeft"><span class="property" value="New prop">New prop</span></td><td class="propertyRight"><a href="javascript:void(0);" class="deleteProp">Del</a> <a href="javascript:void(0);" class="renameProp">Ren</a></td></tr>');
	reloadEventTriggers();
}
/**
 * Load rename property
 */
pcgObject.prototype.renameProperty = function(){
	var oDom = this.parentNode.previousSibling.firstChild
	convertIntoInput(oDom);
}
/**
 * Action when prop is received 
 */
pcgObject.prototype.receiveProp = function (event, ui){
	$('#dialog').dialog('open');
	$(ui.sender).sortable('cancel');
}
/**
 * Delete prop
 */
pcgObject.prototype.deleteProperty = function (){
	$(this).parent().parent().remove();
}









function object_dump(obj) {
	var returned = '';
	for (var prop in obj) {
		returned += "O." + prop + " = " + obj[prop] + "\n";
	}
	return returned;
}


	
	
