//require.define({"mailbox": function(require, exports, module) {


var net = require('net');
var stub = require('stub');

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
	this.getFolders = function(events, constraints) {
		net.createHTTPRequest(function(request){
			request.headers["Content-Length"] = 1234;
			request.write("getFolders"); // 
			request.on("success", function(response) {
				// Do transformation to array of Folder objects.
				//response = stub.getFoldersResponse; // stub!
				var folders = [];
				for(var id in response.folders) {
					folder = response.folders[id];
					folders.push(new Folder({
						name   : folder.name, 
						id     : folder.id,
						type   : folder.type,
						unread : folder.unread,
						mails  : folder.mails
					}));
				}
				events.success(folders);
			});
			request.on("error", events.error || noop);
		}).send(options.endpoint + 'getfolders.json');
	};
	
	/**
	 * 
	 * 
	 * @param events
	 * @returns
	 */
	this.getMails = function(events, constraints) {
		net.createHTTPRequest(function(request){
			request.headers["Content-Length"] = 567;
			request.write('getMails');
			request.on("success", function(response) {
				response = stub.getMailsResponse;
				var mails = [];
				for(var id in response.mails) {
					var mail = response.mails[id];
					mails.push(new Mail({
						from : mail.from,
						id : mail.id,
						attachments : mail.attachments, 
						date : mail.date,
						subject : mail.subject
					}));
				}
				events.success(mails);
			});
			request.on("error", events.error || noop);
		}).send(options.endpoint);
	};
	
	
	this.getConfiguration = function(events) {
		net.createHTTPRequest(function(request){
			
		}).send(options.endpoint);
	};
	
};


exports.MailBox = MailBox;

//}},['net','stub']);