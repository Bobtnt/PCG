
$(document).ready(function(){
	//Global var
	iPcgContainerGlobalCounter = 0;
	aOpcgContainer = new Array;
	aModifiedElements = new Array();
	
	iGrapherCounter = 0;
	aGrapherContainer = new Array();
	aGrapherDivContainer = new Array();
	
	//daemon
	setInterval('pcgObjectControl()', 0.4 * 1000);	
	setInterval('reposMessagebox()', 0.5 * 1000);
	
	
	//Tools bar buttons
	$("#buttonNewObject").click(newPcgObject);
	
	$("#buttonNewRelation").click(function(){
		$('#dialog').dialog('open');
	});
	$("#buttonDebug").click(function(){
		console.log(aOpcgContainer[1].html.find('.pcgObject').offset());
	});
	
	$("#buttonDisplayGrid").toggle(function(){
		$('#myCanvas').removeClass('canvas-whitout-grid', 1000);
		$("#buttonDisplayGrid span").html("Off");
	},function(){		
		$('#myCanvas').addClass('canvas-whitout-grid', 1000);
		$("#buttonDisplayGrid span").html("On");		
	});
	
	// Model: object and category 
	$("#buttonModel1").click(function(){
		var o1 = newPcgObject();
		var o2 = newPcgObject();
		
		o1.rename('object');
		
		o2.rename('category');
		
		var p1 = o2.addNewProp('name', 'varchar');
		o2.html.find('ul').append(p1.html);
		reloadEventTriggers();
		
		var newpos = o2.html.css('left');
		newpos = newpos.replace(/px/, '');
		newpos = parseInt(newpos);
		newpos = newpos + 400;
		
		o2.html.css('left', newpos );
		newRelation(o2, o1, o2.getProp(1), '1:1');
	});
	
	// Model: users and groups
	$("#buttonModel2").click(function(){
		var o1 = newPcgObject();
		var o2 = newPcgObject();
		
		o1.rename('user');
		o2.rename('group');
		var p1 = o1.addNewProp('name', 'varchar');
		var p2 = o2.addNewProp('name', 'varchar');
		
		o1.html.find('ul').append(p1.html);
		o2.html.find('ul').append(p2.html);
		reloadEventTriggers();
		
		var newpos = o2.html.css('left');
		newpos = newpos.replace(/px/, '');
		newpos = parseInt(newpos);
		newpos = newpos + 400;
		
		o2.html.css('left', newpos );
		newRelation(o2, o1, o2.getProp(1), 'n:m');
	});
	
	// Model: single linked 1:n
	$("#buttonModel3").click(function(){
		var o1 = newPcgObject();
		var o2 = newPcgObject();
		
		o1.rename('bug');
		o2.rename('user');
		var p2 = o2.addNewProp('name', 'varchar');
		
		o2.html.find('ul').append(p2.html);
		reloadEventTriggers();
		
		var newpos = o2.html.css('left');
		newpos = newpos.replace(/px/, '');
		newpos = parseInt(newpos);
		newpos = newpos + 400;
		
		o2.html.css('left', newpos );
		newRelation(o2, o1, o2.getProp(1), '1:n');
		o1.getProp(2).setName('causedBy');
	});
	
	// Model: multi linked 1:n
	$("#buttonModel4").click(function(){
		var o1 = newPcgObject();
		var o2 = newPcgObject();
		
		o1.rename('bug');
		o2.rename('user');
		var p2 = o2.addNewProp('name', 'varchar');
		
		o2.html.find('ul').append(p2.html);
		reloadEventTriggers();
		
		var newpos = o2.html.css('left');
		newpos = newpos.replace(/px/, '');
		newpos = parseInt(newpos);
		newpos = newpos + 400;
		
		o2.html.css('left', newpos );
		newRelation(o2, o1, o2.getProp(1), '1:n');
		o1.getProp(2).setName('reportedBy');
		
		newRelation(o2, o1, o2.getProp(1), '1:n');
		o1.getProp(3).setName('verifiedBy');
		
		newRelation(o2, o1, o2.getProp(1), '1:n');
		o1.getProp(4).setName('createdBy');
	});
	
	//Global events
	$(".messageBox").css('top', window.innerHeight - 30 );
	
	//minimap
	map = new minimap();
	$(".accordion div:first").append(map.html);
	map.show();
	
	// new relation dialog
	$("#dialog").dialog({
		bgiframe: false,
		autoOpen: false,
		height: 300,
		modal: true,
		buttons: {
			'Select': function() {
				dialogType = $('#dialog #dialogType').val();
				
				if(dialogType == 'relation'){
					var relationType = $("#dialog #relationType").val();
					iPcgSenderId = $("#dialog #pcgSenderId").val();
					iPcgReceiverId = $("#dialog #pcgReceiverId").val();
					iPropId = $("#dialog #propId").val();
					oPcgSender = getPcgInstance(iPcgSenderId);
					oPcgReceiver = getPcgInstance(iPcgReceiverId);
					oProp = oPcgSender.getProp(iPropId);
					newRelation(oPcgSender, oPcgReceiver, oProp, relationType);				
					$(this).dialog('close');
				}
				else if(dialogType == 'changeType'){
					var selectedValue = $("#dialog #propType").val();
					iPcgId = $("#dialog #pcgObjectId").val();
					iPropId = $("#dialog #pcgPropId").val();
					oPcg = getPcgInstance(iPcgId);
					oProp = oPcg.properties[iPropId];
					oProp.changePropType(selectedValue);
					$(this).dialog('close');
				}
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		},
		close: function() {
		}
	});
	
	$(".accordion").accordion({
		collapsible: false,
		animated: 'bounceslide'
	});
	$(".accordion-container").css('top', '40px');
	$(".accordion-container").css('left', window.innerWidth - $(".accordion-container").width() - 20 + 'px');

	
});

