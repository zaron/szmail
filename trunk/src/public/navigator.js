var Navigator = new (function () {
	var params;
	var pages = {};
	
	var changeView = function (view) {
		
	};
	
	this.getPath = function () {
		var hash = location.hash;
		// Hash does not begin with #!/
		if (!hash.match(/^#!\/.+\?/)) return "INBOX"; // TODO: error handling
		var path = new String((/^#!\/.+\?/).exec(hash));
		path = path.substr(3);
		return path.substr(0,path.length-2);
	};
	
	this.getViewURL = function (view) {
		
	};
	
	this.registerPage = function (page) {
		pages[page.name] = page;
	};
	
	/**
	 * 
	 * @param parameter
	 */
	this.getParameter = function (parameter) {
		return params[parameter];
	};
	
	this.update = function () {
		var hash = location.hash;
		var regex = /^#!([a-z]+):\/(.*)\?(.*)/;
		var matches = hash.match(regex);
		if (!matches) return; // TODO: error handling!
		
		if (matches[1] == 'mailbox') {
			var selectedFolder = matches[2];
		}
		var ex = matches[3].split('&');
		params = {};
		for (var i in ex) {
			var kv = ex[i].split('=');
			params[kv[0]] = kv[1];
		}
		
		// Select page and show it. 
		var page = matches[1] + ((matches[2] != '') ? '/' + matches[2] : '');
		if(pages[page]) pages[page].show();

	};

})();