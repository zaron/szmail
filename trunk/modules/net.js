//require.define({"net": function(require, exports, module) {

// Empty function.
var noop = function() {};
	
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
		var response = "";
		events["success"](response);
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