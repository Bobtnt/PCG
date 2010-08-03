/**
 * $Id $
 * 
 * Daemonizer plugin by cyril janssens
 * 
 * <code>
 * 	getNow = function(){
 * 		var now = new Date();
 *		return now.toLocaleString();
 * 	}
 * 
 * 	$("#somedivelement").daemonize(getNow, 1 * 1000);
 * </code>
 * 
 */
/*

(function($) {
	
	$.fn.daemonizedFunction;
	$.fn.daemonizedRecipient;
	
	$.fn.daemonize = function(callback, interval_ms) {
		$.fn.daemonizedRecipient = $(this);
		$.fn.daemonizedFunction = callback;
		setInterval($.fn.internalCallback, interval_ms);
	};
	
	$.fn.internalCallback = function(){
		$.fn.daemonizedRecipient.html($.fn.daemonizedFunction());
	};
	
})(jQuery);
*/
var globalTmpId;

(function($) {
	
	$.fn.helper = [];
	
	$.fn.daemonize = function(callback, interval_ms) {
		
		var opts = {
				id : 0,
				daemonizedRecipient : $(this), 
				daemonizedFunction : callback,
				daemonInternalCallback:  function(e){
					$.fn.helper[e].daemonizedRecipient.html($.fn.helper[e].daemonizedFunction()); 
				},
				timer : interval_ms,
				intervalPointer : void(0)
		}
		createNewHelper(opts);
		return $(this);
	};
	
	function createNewHelper(opts){
		$.fn.helper.push(opts);
		var id = $.fn.helper.length -1;
		$.fn.helper[id].id = id;
		globalTmpId = id
		toEval = '$.fn.helper[globalTmpId].daemonInternalCallback(globalTmpId)';
		$.fn.helper[id].intervalPointer = setInterval( toEval, $.fn.helper[id].timer);
	}
	
	
})(jQuery);

