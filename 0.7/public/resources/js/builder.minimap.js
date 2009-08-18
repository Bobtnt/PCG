


minimap = function() {
	
	this.pos = {
			top: 10,
			left: 760	
	};
	this.size = {
			height: 400,
			width: 300
	};
	
	this.mapMaxSize = {
			height: 2000,
			width: 1500
	};
	
	this.iObjects = 0;
	this.aObjects = new Array();
	this.aMiniObjects = new Array();
	
	this.html = '<div class="minimap"><div class="redSquare"></div></div>';
	
	this.show = function(){
		var space = $(document).width();
		this.pos.left = space - this.size.width - 17;
		this.html.css('top', this.pos.top + 'px');
		this.html.css('left', this.pos.left + 'px');
		this.html.css('height', this.size.height + 'px');
		this.html.css('width', this.size.width + 'px');
		$("body").append(this.html);
		this.reloadUI();
	};
	
	this.reloadUI = function(){
		this.html.find('.redSquare').height(window.innerHeight / this.mapMaxSize.height * this.size.height);
		this.html.find('.redSquare').width(window.innerWidth / this.mapMaxSize.width * this.size.width);
		this.html.find('.redSquare').draggable({ 
			containment: 'parent',
			stop: this.helpers.moveScreen
			});
		$('.canvas').height(this.mapMaxSize.height);
		$('.canvas').width(this.mapMaxSize.width);
	};
	/**
	 * 
	 * @param {pcgObject} oPcg
	 */
	this.addObject = function(oPcg){
		
		this.aObjects[oPcg.id] = oPcg;
		
		var oPos = oPcg.html.find(".pcgObject").offset();
		var relatives = {};		
		relatives.top =  ((oPos.top / this.mapMaxSize.height ) * this.size.height);
		relatives.left = ((oPos.left / this.mapMaxSize.width) * this.size.width);
		
		relatives.height = oPcg.html.find(".pcgObject").height() / this.mapMaxSize.height * this.size.height;
		relatives.width = oPcg.html.find(".pcgObject").width() / this.mapMaxSize.width * this.size.width;
		
		this.aMiniObjects[oPcg.id] = {};
		this.aMiniObjects[oPcg.id].html = '<div class="miniObject"></div>';
		this.aMiniObjects[oPcg.id].html = $(this.aMiniObjects[oPcg.id].html);
		this.aMiniObjects[oPcg.id].html.css('top',relatives.top + 'px');
		this.aMiniObjects[oPcg.id].html.css('left',relatives.left + 'px');
		
		this.aMiniObjects[oPcg.id].html.width(relatives.width + 'px');
		this.aMiniObjects[oPcg.id].html.height(relatives.height + 'px');
		
		this.html.append(this.aMiniObjects[oPcg.id].html);
		
	};
	
	this.moveObject = function(oPcg){
				
		var oPos = oPcg.html.find(".pcgObject").offset();
		var relatives = {};		
		relatives.top =  ((oPos.top / this.mapMaxSize.height ) * this.size.height);
		relatives.left = ((oPos.left / this.mapMaxSize.width) * this.size.width);
		
		relatives.height = oPcg.html.find(".pcgObject").height() / this.mapMaxSize.height * this.size.height;
		relatives.width = oPcg.html.find(".pcgObject").width() / this.mapMaxSize.width * this.size.width;
		
		this.aMiniObjects[oPcg.id] = {};
		this.aMiniObjects[oPcg.id].html = '<div class="miniObject"></div>';
		this.aMiniObjects[oPcg.id].html = $(this.aMiniObjects[oPcg.id].html);
		this.aMiniObjects[oPcg.id].html.css('top',relatives.top + 'px');
		this.aMiniObjects[oPcg.id].html.css('left',relatives.left + 'px');
		this.aMiniObjects[oPcg.id].html.width(relatives.width + 'px');
		this.aMiniObjects[oPcg.id].html.height(relatives.height + 'px');
		
		console.log('top=' + relatives.top + 'px');
		console.log('left=' + relatives.left + 'px');
			
	};
	
	//--------------------
	//    helpers
	//--------------------
	
	
	this.helpers = {
			
		moveScreen: function(event, ui){
			var squarePos = map.html.find('.redSquare').offset();
			relatives = {};
			relatives.top = squarePos.top - map.pos.top - 1 - $(document).scrollTop(); 
			relatives.left = squarePos.left - map.pos.left - 1 - $(document).scrollLeft();
			relatives.top = relatives.top / map.size.height * map.mapMaxSize.height;
			relatives.left = relatives.left / map.size.width * map.mapMaxSize.width;
			window.scrollTo(relatives.left, relatives.top);
			return this;
		}
			
	};
	
	//--------------------
	//    constructor
	//--------------------
	
	this.html = $(this.html);
	
};