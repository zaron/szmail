var Navigator = new (function () {
	var params;
	
	
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
	
	/**
	 * 
	 * @param parameter
	 */
	this.getParameter = function (parameter) {
		return params[parameter];
	};
	
	this.update = function () {
		var hash = location.hash;
		var regex = /^#!\/(.+)\?([a-zA-Z_][a-zA-Z_0-9]*\=[a-zA-Z0-9_]+)/;
		// Hash does not begin with #!(a-zA-Z0-9)://
		if (!hash.match(regex)) return;
		var ex = (regex).exec(hash);
		var folder = new String(ex[1]);
		params = {};
		ex = ex.slice(2, ex.length);
		for (var i in ex) {
			var kv = ex[i].split('=');
			params[kv[0]] = kv[1]; 
		}
		
		if (params['v'] == "settings_account") {
			$('#settings_account').show();
			$('#settings_server_in').hide();
			$('#settings_server_out').hide();
		} else if (params['v'] == "settings_server_in") {
			$('#settings_account').hide();
			$('#settings_server_in').show();
			$('#settings_server_out').hide();
		} else if (params['v'] == "settings_server_out") {
			$('#settings_account').hide();
			$('#settings_server_in').hide();
			$('#settings_server_out').show();
		}
	};

})();