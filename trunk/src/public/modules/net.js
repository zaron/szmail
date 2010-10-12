var JSON = require('json');

//Constants.
exports.HTTP_METHOD_GET   = 'GET';
exports.HTTP_METHOD_POST  = 'POST';
exports.JSONRPC_VERSION_2 = '2.0';


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
    options.method = options.method || exports.HTTP_METHOD_GET;
    options.headers = options.headers || {};

	
    var _this = this;
    
	var writeBuffer = "";
    
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
		writeBuffer += s;
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
		// Create XHR object and open connection to given URL.
		var xhr = createXMLHttpRequest();
		xhr.open(options.method, url, true);
		
		// Set HTTP headers.
		for(var name in options.headers) {
			xhr.setRequestHeader(name, options.headers[name]);
		}
		
		// 
		xhr.onreadystatechange = function() {
			if(xhr.readyState != 4) { return; } // TODO: Error handling
			log(xhr.responseText,"RECEIVE");
			var response = '';
			if(url == '/'); // bad hack
			else response = JSON.parse(xhr.responseText);
			events["success"](response);
		};
		
		// Send request.
		if(options.method == exports.HTTP_METHOD_GET) {
			xhr.send(null);
			return;
		}
		log(writeBuffer,"TRANSMIT");
		xhr.send(writeBuffer);
	};
};

var JSONRPC_ID_COUNTER = 1;

/**
 * Creates a new JSONRPC request.
 * 
 * @param process
 */
exports.createJSONRPCRequest = function(process) {
	return {
		"send" : function(url, options) {
			// HTTP content-type must be 'application/json'.
			options.headers = options.headers || {};
			options.headers['Content-Type'] = 'application/json';
			
			var handler = new HTTPRequestHandler(url, options);
			var jsonrpcInterface = handler.publicInterface;
			var write = handler.publicInterface.write;
			var method = "";
			jsonrpcInterface.setMethod = function(m) {
				method = m;
			};
			jsonrpcInterface.write = function(params) {
				write(JSON.stringify({
					"jsonrpc" : exports.JSONRPC_VERSION_2,
					"method"  : method,
					"params"  : params,
					"id"      : "" + (JSONRPC_ID_COUNTER++)
				}));
			};
			process(jsonrpcInterface);
			handler.send();
		}
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