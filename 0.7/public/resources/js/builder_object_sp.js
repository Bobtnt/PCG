/**
 * $Id$
 * 
 * 
 * base pcg object 
 * @class pcgObject
 * @constructor
 */
pcgObject = function(){
	
	//--------------------
	//    properties
	//--------------------   
	this.id = 0;
	this.name = 'New object';
	this.html = '<div><div class="pcgObject ui-widget ui-corner-all" pcgId="0">'
				+  '<div class="pcgObjectHeader ui-widget-header">'
				+  '<span style="width:100%;position:absolute">'
				+  '<span style="position:absolute;z-index:100;" class="pcgObjectName" value="New object">New object</span>'
				+  '<span style="position:absolute;margin-left: 100%;left:-6.0em;"><a href="javascript:void(0);" class="addProperty" alt="Add property" title="Add property"><span class="ui-icon ui-icon-circle-plus"></span></a></span>'
				+  '<span style="position:absolute;margin-left: 100%;left:-5.0em;"><a href="javascript:void(0);" class="renameObject" alt="Rename Object" title="Rename Object"><span class="ui-icon ui-icon-transferthick-e-w"></span></a></span>'
				+  '<span style="position:absolute;margin-left: 100%;left:-3.0em;"><a href="javascript:void(0);" class="reduceObject" alt="Reduce Object" title="Reduce Object"><span class="ui-icon ui-icon-circle-minus"></span></a></span>'
				+  '<span style="position:absolute;margin-left: 100%;left:-2.0em;"><a href="javascript:void(0);" class="deleteObject" alt="Delete Object" title="Delete Object"><span class="ui-icon ui-icon-circle-close"></span></a></span>'
				+  '</span>'
				+  '</div>'
				+  '<div class="pcgObjectBody ui-widget-content">'
				+  '<ul class="propertiesBlock">'
				//+  '<li class="propertyBlock"><span class="propertyLeft ui-state-default"><span class="ui-icon ui-icon-arrow-2-n-s span-li-icon"></span><span class="property" value="id" propid="1" type="PRIMARY">id</span></span><span class="propertyRight ui-state-default ui-state-disable">Primary key</span></li>'
				+  '</ul>'
				+  '</div>'
				+  '</div></div>';
	this.properties = new Array;
	this.length = 0;
	this.internalFunctions = new Array;
	this.internalFunctionsArguments = new Array;
	this.internalFunctionsCounter = 0;
	this.controled = true;
	
	//--------------------
	//    methods
	//--------------------
	
	/**
	 * Setter fo id
	 */
	this.setId = function(iId){
		this.id = iId;
		this.html.find(".pcgObject").attr("pcgId", iId);
	};
	
	/**
	 * return property by id
	 */
	this.getProperty = function (id){
		if(this.properties[id]){
			return this.properties[id];
		}
		else{
			return false;
		}
	};
	/**
	 * alias of getProperty
	 */
	this.getProp = function (id){
		return this.getProperty(id);
	};
	/**
	 * display object in canvas
	 */
	this.show = function(){
		$(".canvas").append(this.html);
		this.reloadUI();
	};
	/**
	 * reload UI object event  
	 */
	this.reloadUI = function(){	
		this.html.find(".pcgObject").resizable({
			stop: this.helpers.objectResizeStop
		});
		this.html.find(".pcgObject").draggable({ 
			handle: '.pcgObjectHeader',
			containment: '.canvas',
			stack: { group: 'pcg', min: 50 }, 
			start: this.helpers.objectDragStart,
			drag: this.helpers.objectDragging,
			stop: this.helpers.objectDragStop			
		});
		//this.html.find(".pcgObject").bind('drag', this.executeBinderUI);
		
		this.html.find(".propertiesBlock").sortable({
			revert : true, 
			items: 'li:has(span.propertyLeft)', 
			receive: this.helpers.receiveProp,
			//change: this.executeBinderUI,
			update: this.executeBinderUI,
			//over: this.executeBinderUI,
			placeholder: 'ui-state-highlight',
			forcePlaceholderSize: true
			});
		this.html.find(".propertiesBlock").sortable('option', 'connectWith', '.propertiesBlock');
		this.html.find(".propertiesBlock").disableSelection();
	};
	/**
	 * execute functions attached by attachDrawUI
	 * 
	 * @param event
	 * @param ui
	 * @return void
	 */
	this.executeBinderUI = function(event, ui){
		var pcgInstance = getPcgInstance(this);
		for(var a in pcgInstance.internalFunctions){
			var bindedFunction = pcgInstance.internalFunctions[a];
			var arguments = pcgInstance.internalFunctionsArguments[a];
			bindedFunction(arguments);
		}
	};
	/**
	 * visual attach properties
	 */
	this.attachDrawUI = function(oPcg1, iProp1, oPcg2, iProp2,  oGrapher){
		this.internalFunctionsCounter = this.internalFunctionsCounter + 1;
		this.internalFunctionsArguments[this.internalFunctionsCounter] = {o1 : oPcg1, p1: iProp1, o2: oPcg2, p2: iProp2, grapher: oGrapher};
		/**
		 * @member pcgObject
		 */
		this.internalFunctions[this.internalFunctionsCounter] = function(arguments){
			arguments.grapher.clear();
			
			var pos1 = arguments.o1.getProp(arguments.p1).html.offset();
			var pos2 = arguments.o2.getProp(arguments.p2).html.offset();
			
			var prop1H = arguments.o1.getProp(arguments.p1).html.height();
			var prop2H = arguments.o2.getProp(arguments.p2).html.height();
			
			var margin = 3;
							
			if(pos1.left < pos2.left){
				
				var startPoint = pos1.left + arguments.o1.getProp(arguments.p1).html.width();
				var endPoint =  pos2.left;
				var middlePoint = ((endPoint - startPoint) /2) + startPoint; 
								
				var aXcoord = [ startPoint + margin,
				                middlePoint,
				                middlePoint,
				                endPoint - (margin*2) ];
				var aYcoord = [ pos1.top + (prop1H /2),
				                pos1.top + (prop1H /2),
				                pos2.top + (prop2H /2),
				                pos2.top + (prop2H /2)];
				arguments.grapher.fillArc( startPoint - (15/2) + margin, pos1.top + (prop1H/5), 15, 15 , 270, 90);
				arguments.grapher.fillArc( endPoint - (15/2) - margin, pos2.top + (prop2H/5), 15, 15 , 90, 270);
				
			}
			else{
				var startPoint = pos2.left + arguments.o2.getProp(arguments.p2).html.width();
				var endPoint =  pos1.left;
				var middlePoint = ((endPoint - startPoint) /2) + startPoint; 
								
				var aXcoord = [ startPoint + margin,
				                middlePoint,
				                middlePoint,
				                endPoint - (margin*2) ];
				var aYcoord = [ pos2.top + (prop2H /2),
				                pos2.top + (prop2H /2),
				                pos1.top + (prop1H /2),
				                pos1.top + (prop1H /2)];
				arguments.grapher.fillArc( startPoint - (15/2) + margin, pos2.top + (prop2H/5), 15, 15 , 270, 90);
				arguments.grapher.fillArc( endPoint - (15/2) - margin, pos1.top + (prop1H/5), 15, 15 , 90, 270);
			}
			
			arguments.grapher.drawPolyline(aXcoord, aYcoord);
			arguments.grapher.paint();
			
		};
	};
	
	/**
	 * adding new property
	 */
	this.addNewProp = function (name, type){
		var iPcgId = this.id; 
		var thisInstance = aOpcgContainer[iPcgId]; 
		var oProp = new property(name, type, 'none', thisInstance);
		thisInstance.length = thisInstance.length + 1;
		thisInstance.properties[thisInstance.length] = oProp;
		thisInstance.properties[thisInstance.length].id = thisInstance.length;
		var sName = thisInstance.properties[thisInstance.length].name;
		var sType = thisInstance.properties[thisInstance.length].type;
		if(type == 'PRIMARY'){
			thisInstance.properties[thisInstance.length].html =  '<li class="propertyBlock"><span class="propertyLeft ui-state-default"><span class="ui-icon ui-icon-arrow-2-n-s span-li-icon"></span><span class="property" value="id" propid="1" type="PRIMARY">id</span></span><span class="propertyRight ui-state-default ui-state-disable">Primary key</span></li>';
		}
		else{
			thisInstance.properties[thisInstance.length].html = '<li class="propertyBlock">'
			+ '<span class="propertyLeft ui-state-default"><span class="ui-icon ui-icon-arrow-2-n-s span-li-icon"></span><span class="property" propId="'+ thisInstance.length +'" value="'+ sName +'" type="'+ sType +'">'+ sName +'</span> <span class="propertyType">'+ sType +'</span></span>'
			+ '<span class="propertyRight">'
			+ '<a href="javascript:void(0);" class="changeProp ui-state-default ui-state-disable" style="float:left"><span class="ui-icon ui-icon-wrench" alt="Change Property" title="Change Property"></a> '
			+ '<a href="javascript:void(0);" class="renameProp ui-state-default ui-state-disable" style="float:left"><span class="ui-icon ui-icon-transferthick-e-w" alt="Rename Property" title="Rename Property"></a> '
			+ '<a href="javascript:void(0);" class="deleteProp ui-state-default ui-state-disable" style="float:left"><span class="ui-icon ui-icon-close" alt="Delete Property" title="Delete Property"></span></a> ' 
			+ '</span></li>';
		}
		thisInstance.properties[thisInstance.length].html = $(oProp.html); 
		return thisInstance.properties[thisInstance.length];
	};
	
	/**
	 * 
	 * @param {string} newName
	 * @return this
	 */
	this.rename = function(newName){
		this.controled = false;
		this.html.find(".pcgObjectName").attr('value', newName);
		this.html.find(".pcgObjectName").html(newName);
		this.name = newName;
		this.controled = true;
		return this;
	};
	/**
	 * first placing of this object
	 * @return
	 */
	this.placeOnScreen = function(){
		this.html.css('position', 'absolute');
		this.html.css('height', '1px');
		this.html.css('width', '1px');
		if(this.id != 1 && this.id != 0 && aOpcgContainer[ this.id - 1 ]){
			var previousObject = aOpcgContainer[ this.id - 1 ]; 
			var pos = previousObject.html.offset();
			this.html.css('top', pos.top + 30);
			this.html.css('left', pos.left + 30);
		}
		else {			
			this.html.css('top', 70);
			this.html.css('left', 30);			
		}
	};
	
	this.prepareSaving = function(){
		var oStringified = {};
		oStringified.name = this.name;
		oStringified.id = this.id;
		oStringified.properties = [];
		for( var a in this.properties){
			var property = this.properties[a];
			var prop = {};
			prop.name = property.name;
			prop.id = property.id;
			prop.type = property.type;
			if(property.related && property.related != "none"){
				prop.related = [];
				for( var b in property.related){
					prop.related.push({pcgId: property.related[b].to.parent.id , propId: property.related[b].to.id, type: property.related[b].type });  
				}
			}
			oStringified.properties.push(prop);
		}
		return oStringified;
	};
	
	//--------------------
	//    HELPERS
	//--------------------
	
	this.helpers = 
	{
		
		/**
		 * Load rename object
		 */
		rename: function(){
			convertIntoInput(this.firstChild.firstChild);
		},
		
		/**
		 * Load delete object
		 */
		remove: function(){
			var confrm = confirm('Are you sur?');
			if(confrm){
				oPcg = getPcgInstance(this);
				for( var a in oPcg.properties){
					oPcg.properties[a].remove();
				}
				
				map.remove(oPcg);
				
				oId = oPcg.id;
				delete aOpcgContainer[oId];
				$(this).parent().parent().parent().parent().parent().remove();
				reloadEventTriggers();
			}
		},
		/**
		 * Add property
		 */
		addProperty: function(){
			var oPcg = getPcgInstance(this);
			if(oPcg){
				oProp = oPcg.addNewProp('New prop', 'auto');
				$(this).parent().parent().parent().parent().find('ul').append(oProp.html);
				reloadEventTriggers();
			}
		},
		/**
		 * Load rename property
		 */
		renameProperty: function(){
			var oDom = this.parentNode.previousSibling.firstChild.nextSibling;
			convertIntoInput(oDom);
		},
		/**
		 * receive property helper
		 */
		receiveProp: function (event, ui){
			oPcgReceiver = getPcgInstance(ui.item.find(".property"));
			iPropId = ui.item.find(".property").attr('propid');
			oPcgSender = getPcgInstance($(ui.sender));
			oProp = oPcgSender.getProp(iPropId);
			$(ui.sender).sortable('cancel');
			if(oProp.type != 'PRIMARY'){
				return this;
			}
			$('#dialog #dialogType').val('relation');
			$('#dialog #propType').hide();
			$('#dialog #relationType').show();
			$('#dialog #pcgSenderId').val(oPcgSender.id);
			$('#dialog #pcgReceiverId').val(oPcgReceiver.id);
			$('#dialog #propId').val(oProp.id);
			$('#dialog').attr('title', 'Select Relation type');
			$("#dialog span[name='dialogMessage']").html('Select property type for ' + oProp.name);
			$("#dialog span[name='dialogMessage']").html(' Link '+ oPcgSender.name + ' with ' + oPcgReceiver.name );
			$('#dialog').dialog('open');
		},
		/**
		 * Delete prop
		 */
		deleteProperty: function (){
			oPcg = getPcgInstance(this);
			iPropId = $(this).parent().parent().find(".property").attr('propid');
			oProp = oPcg.properties[iPropId];
			oProp.remove();
			$(this).parent().parent().remove();
		},
		/**
		 * open change type dialog
		 */
		openChangeTypeDialog: function (){
			oPcg = getPcgInstance(this);
			iPropId = $(this).parent().parent().find(".property").attr('propid');
			oProp = oPcg.properties[iPropId];
			$('#dialog #dialogType').val('changeType');
			$('#dialog #propType').show();
			$('#dialog #relationType').hide();
			$("#dialog #pcgObjectId").val(oPcg.id);
			$("#dialog #pcgPropId").val(oProp.id);
			$('#dialog').attr('title', 'Select type');
			$("#dialog span[name='dialogMessage']").html('Select property type for ' + oPcg.name + '->' +  oProp.name);
			$("#dialog").dialog('open');
		},
		
		setStackUp: function(oPcg){			
			oPcg.html.css('z-index', '100');
		},
		setStackDown: function(oPcg){
			oPcg.html.css('z-index', '0');
		},
		objectDragStart: function(event, ui){
			//var oPcg = getPcgInstance(this);
			//oPcg.helpers.setStackUp(oPcg);
		},
		objectDragStop: function(event, ui){
			var oPcg = getPcgInstance(this);
			oPcg.helpers.setStackDown(oPcg);
			oPcg.executeBinderUI();
			map.moveObject(oPcg);			
		},
		objectDragging: function(event, ui){
			//Nice but consume most of cpu 
			//var oPcg = getPcgInstance(this);
			//map.moveObject(oPcg);	
		},
		objectResizeStop: function(event, ui){
			var oPcg = getPcgInstance(this);
			oPcg.helpers.setStackDown(oPcg);
			oPcg.executeBinderUI();
			map.moveObject(oPcg);	
		},
		colapse: function(event, ui){
			$(this).parent().parent().parent().parent().find(".ul").hide();
			$(this).parent().parent().parent().parent().find(".pcgObjectBody").hide();
			$(this).parent().parent().parent().parent().height(10);
		},
		expand:	function(){
			$(this).parent().parent().parent().parent().find(".ul").show();
			$(this).parent().parent().parent().parent().find(".pcgObjectBody").show();
		}
	};
	
	//--------------------
	//  contructor
	//--------------------
	this.html = $(this.html);
	this.show();

};
//--------------------
//    property object
//--------------------

/**
 * property object 
 * 
 * @class property
 * @author cjanssens
 * @constructor
 */
property = function(sName, sType, sRelated, oParent){

	this.name = '';
	this.type = '';
	this.id = 0;
	this.related = '';
	this.parent = void(0);
	this.html = '';
	/**
	 * remove prop in parent
	 */
	this.remove = function (){
		//Check for relation
		if(typeof this.related != 'string'){
			//delete relation and UI elements
			for(var a in this.related){
				var oPcg1 = this.parent;
				var oProp1 = this;
				var oPcg2 = this.related[a].to.parent;
				var oProp2 = this.related[a].to;
				//search for internalFunctionsArguments id in destination object
				for( var b in oPcg2.internalFunctionsArguments){
					var fargs = oPcg2.internalFunctionsArguments[b];
					if( (fargs.o1 == oPcg1 &&  fargs.p1 == oProp1.id && fargs.o2 == oPcg2 &&  fargs.p2 == oProp2.id ) ||
					(fargs.o1 == oPcg2 &&  fargs.p1 == oProp2.id && fargs.o2 == oPcg1 &&  fargs.p2 == oProp1.id )){
						var functionIdentifier = b;
						//now delete
						if(fargs.grapher){
							fargs.grapher.clear();
						}
						delete oPcg2.internalFunctions[functionIdentifier];
						delete oPcg2.internalFunctionsArguments[functionIdentifier];					
					}					
				}
				// delete related entry in destination property
				for( var b in oProp2.related ){
					if(oProp2.related[b].to == oProp1){
						delete oProp2.related[b];
					}
				}
				
				
				//search for internalFunctionsArguments id in source object
				for( var b in oPcg1.internalFunctionsArguments){
					var fargs = oPcg1.internalFunctionsArguments[b];
					if( (fargs.o1 == oPcg1 &&  fargs.p1 == oProp1.id && fargs.o2 == oPcg2 &&  fargs.p2 == oProp2.id ) ||
					(fargs.o1 == oPcg2 &&  fargs.p1 == oProp2.id && fargs.o2 == oPcg1 &&  fargs.p2 == oProp1.id )){
						var functionIdentifier = b;
						//now delete
						if(fargs.grapher){
							fargs.grapher.clear();
							delete fargs.grapher;
						}						
						delete oPcg1.internalFunctions[functionIdentifier];
						delete oPcg1.internalFunctionsArguments[functionIdentifier];					
					}
				}
			}
		}
		
		delete this.parent.properties[this.id];
	};
	/**
	 * change prop type
	 */
	this.changePropType = function (newType){
		this.html.find('.property').attr('type', newType);
		this.html.find('.propertyType').html(newType);
		this.type = newType;
	};
	
	this.setName = function(newName){
		this.html.find('.property').val(newName);
		this.html.find('.property').html(newName);
	};
	
	
	//--------------------
	//  contructor
	//--------------------
	this.name = sName;
	this.type = sType;
	this.related = sRelated;
	this.parent = oParent;

};

/*function object_dump(obj) {
	var returned = '';
	for (var prop in obj) {
		returned += "O." + prop + " = " + obj[prop] + "\n";
	}
	return returned;
}*/


	
	

