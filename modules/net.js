//require.define({"net": function(require, exports, module) {

// Empty function.
var noop = function() {};

function createXMLHttpRequest() {
	try {
		return new XMLHttpRequest();
	} catch (e) {
	}
	try {
		return new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
	}
	alert("XMLHttpRequest not supported");
	return null;
}

/**
 * 
 * @param url
 * @param options
 * @returns
 */
var HTTPRequestHandler = function(url, options) {

	options = options || {};
    options.method = options.method || "GET";
    options.headers = options.headers || {};

	
    var _this = this;
    
	var writeBuffer = [];
    
    var events = {
    	success : noop,
    	error   : noop
    };
    
    /**
     * Registers event callbacks for this request handler.
     * 
     * @param eventName
     * @param callback
     */
    this.on = function(eventName, callback) {
    	events[eventName] = callback;
	};
	
	/**
	 * Writes a String to the HTTP stream.
	 * @param s
	 */
	this.write = function(s) {
		writeBuffer.push(s);
	};

    /**
     * Interface exposed to the public. 
     */
    this.publicInterface = {
    	//! 
		on : _this.on,
		//! 
		write : _this.write,
		//! 
		headers : options.headers
    };
	
    /**
     * 
     */
	this.send = function() {
		// TODO: Use an engine to write to a HTTP stream (XHR in browsers)
		log('send called.');
		var xhr = createXMLHttpRequest();
		log('xhr created ('+ options.method +','+ url +')');
		xhr.open(options.method, url, true);
		log('xhr open.');
		xhr.onreadystatechange = function() {
			if(xhr.readyState != 4) { return; } // TODO: Error handling
			var response = eval('('+xhr.responseText+')');
			events["success"](response);
		};
		xhr.send(null);
	};
};

/**
 * Creates a new HTTP request.
 * 
 * @param process
 */
exports.createHTTPRequest = function(process) {
	return {
		"send" : function(url, options) {
			var handler = new HTTPRequestHandler(url, options);
			process(handler.publicInterface);
			handler.send();
		}
	};
};
//}});