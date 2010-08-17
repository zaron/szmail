var net = require('net');

/**
 * 
 * @param options
 * @returns
 */
var Folder = function(options) {
	
	/**
	 * 
	 */
	this.id = options.id || -1;
	
	/**
	 * 
	 */
	this.type = options.type || '';
	
	/**
	 * 
	 */
	this.name = options.name || '';
	
	/**
	 * 
	 */
	this.mails = options.mails || 0;
	
	/**
	 * 
	 */
	this.unread = options.unread || 0;
	
	/**
	 * 
	 */
	this.size = options.size || 0;
};

/**
 * 
 * @param options
 * @returns
 */
var Mail = function(options) {
	/**
	 * 
	 */
	this.id = options.id || -1;
	
	/**
	 * 
	 */
	this.from = options.from || null;
	
	/**
	 * 
	 */
	this.to = options.to || null;
	
	/**
	 * 
	 */
	this.cc = options.cc || null;
	
	/**
	 * 
	 */
	this.bcc = options.bcc || null;
	
	/**
	 * 
	 */
	this.subject = options.subject || null;
	
	/**
	 * 
	 */
	this.date = options.date || '';
	
	/**
	 * 
	 */
	this.attachments = options.attachments || null;
	
	/**
	 * 
	 */
	this.size = options.size || 0;
};

/**
 * 
 * @param options
 * @returns
 */
var MailBox = MailBox || function(options) {
	
	log("New MailBox object created. ('"+options.endpoint+"')");
	/**
	 * 
	 */
	this.options = options || {
		"updateInterval" : 30000,
		"timeout" : 5000,
		"endpoint" : undefined
	};

	/**
	 * Empty function.
	 */
	var noop = function(){};
	
	/**
	 * Retrieves all folders from the current mailbox. 
	 * 
	 * @param events
	 * @returns
	 */
	this.getFolders = function(args, events) {
		net.createJSONRPCRequest(function(request){
			request.setMethod("getFolders");
			request.write(args);
			request.on("success", function(response) {
				events.success(response.result);
			} || noop);
			request.on("error", events.error || noop);
		}).send(options.endpoint, {method : net.HTTP_METHOD_POST});
	};
	
	/**
	 * 
	 * 
	 * @param events
	 * @returns
	 */
	this.getMails = function(args, events) {
		net.createJSONRPCRequest(function(request){
			request.setMethod("getMails");
			request.write(args);
			request.on("success", function(response) {
				events.success(response.result);
			});
			request.on("error", events.error || noop);
		}).send(options.endpoint, {method : net.HTTP_METHOD_POST});
	};
	
	
	this.getConfiguration = function(events) {
		net.createHTTPRequest(function(request){
			
		}).send(options.endpoint, {method : net.HTTP_METHOD_POST});
	};
	
};


exports.MailBox = MailBox;