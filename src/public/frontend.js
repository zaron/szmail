


function doIframe() {
	o = document.getElementsByTagName('iframe');
	for (i = 0; i < o.length; i++) {
		setHeight(o[i]);
		addEvent(o[i], 'load', doIframe);
	}
}

function setHeight(e) {
	if (e.contentDocument) {
		e.height = e.contentDocument.body.offsetHeight + 35;
	} else {
		e.height = e.contentWindow.document.body.scrollHeight;
	}
}

function addEvent(obj, evType, fn) {
	if (obj.addEventListener) {
		obj.addEventListener(evType, fn, false);
		return true;
	} else if (obj.attachEvent) {
		var r = obj.attachEvent("on" + evType, fn);
		return r;
	} else {
		return false;
	}
}

function log(msg)
{
	$('#debug').prepend(msg+'<br/>');
}

var Navigator = new (function () {
	
	var activeView = $('#view_mail');
	activeView.show();
	
	var lastPath;
	
	this.getPath = function () {
		var hash = location.hash;
		// Hash does not begin with #!/
		if(!hash.match(/^#!\/.+\?/)) return "INBOX"; // TODO: error handling
		var path = new String((/^#!\/.+\?/).exec(hash));
		path = path.substr(3);
		return path.substr(0,path.length-2);
	};
	
	this.getActiveFolder = function () {
		// TODO: trim correct...
		return this.getPath();
	};
	
	this.getParam = function(param) {
		var hash = location.hash;
		var index = hash.indexOf('?');
		if(index == hash.length) return; // TODO: error handling
		var params = this.getPath().substr(index);
		
	};
	
	this.update = function () {
		var path = this.getPath();
		if (lastPath == undefined) {
			FolderList.update();
			return;
		}
		if(lastPath == path) return;
		lastPath = path;
		
		FolderList.update();
	};
	
	var waiting = false;
	var waitingIntervalId = null;
	var offset = 0;
	this.toggleWaiting = function(msg) {
		var el = $('#searchbox');
		if(!waiting) {
			$('#content_wrapper').hide();
			el.val(msg);
			el.css("background","url(/images/indeterminate.png)");
			waiting = true;
			waitingIntervalId = setInterval(function(){
				el.css("background-position","0px " + offset + "px");
				offset = (offset + 3) % 178; 
			},100);
			return;
		}
		$('#content_wrapper').show();
		el.val('');
		el.css("background","");
		clearInterval(waitingIntervalId);
		waiting = false;
	};
	
	this.setActiveView = function (view) {
		if (activeView != view) {
			activeView.hide();
			activeView = view;
			activeView.show();
		}
	};

	
})();


var FolderList = new (function () {
	var lastActiveFolder = 'INBOX';
	var map = {};
	
	/**
	 * 
	 */
	this.setFolders = function (folders) {
		log("FolderList.setFolders called.");
		var specialFolderNames = {
				'INBOX'  : "Posteingang",
				'DRAFTS' : "Entwürfe",
				'SENT'   : "Gesendet",
				'SPAM'   : "Spam",
				'TRASH'  : "Papierkorb"
		};
		
		var specialFolders = {
			'INBOX'  : null,
			'DRAFTS' : null,
			'SENT'   : null,
			'SPAM'   : null,
			'TRASH'  : null
		};
		
		for (var id in folders) {
			var folder = folders[id];
		
			var el = $('<li><img src="/images/placeholder.png"' + ((folder.type) ? ' class="' + folder.type.toLowerCase() + '"':'') + ' alt="" /><a href="#!/' + folder.global + '/?">' + ((folder.type) ? specialFolderNames[folder.type] : folder.name) + ((folder.unread > 0) ? ' ('+ folder.unread + ')' : '') + '<span class="small">'+ folder.mails +'</span></a><span><a class="edit"></a><a class="delete"></a></span></li>');
			log('  map['+folder.global+'] added');
			map[folder.global] = el;
		
			// Ordering of special folders.
			if (folder.type) {
				specialFolders[folder.type] = el;
				continue;
			}
			
			// 
			el.insertBefore('#folders .n.separator');
		}
		
		// Ordering of special folders.
		for (var id in specialFolders) {
			var el = specialFolders[id];
			el.insertBefore('#special_folders .n.separator');
		}
	};

	this.update = function () {
		var activeFolder = Navigator.getActiveFolder();
		if (map[activeFolder]) {
			map[lastActiveFolder].removeClass('active');
			lastActiveFolder = activeFolder;
			Navigator.setActiveView($('#view_folders'));
			Navigator.toggleWaiting('Fetching Mails...');
			MailBox.getMails( [activeFolder], {
				'success' : function(mails) {
					Navigator.toggleWaiting();
					InboxView.setMails(mails);
				},
				'error' : function(error) {}
			});
		}
		map[activeFolder].addClass('active');
		log('FolderList updated.');
	};
	
})();

var InboxView = new (function () {
	var map = [];
	this.setMails = function (mails) {
		$('#mail_table tbody').html('');
		for(var id in mails) {
			var mail = mails[id];
			(function (mail) {
			//var el = document.createElement('tr');
			
			var el = $('<tr><td class="checkbox"><div class="checkbox">'
					 + '<input type="checkbox" name="" /></div></td>'
					 + '<td class="attachment"><a class="'+((mail.attachments != null)?'':'no')+'attachment">'
					 + '</a></td><td class="subject"><b><a href="#!/'+Navigator.getPath()+'/?m='+ mail.uid +'"><i>'
					 + mail.subject
					 + '</i></a></b></td><td class="from"><a class="">'
					 + mail.from
					 + '</a></td><td class="date">'
					 + mail.date // TODO: SimpleDate
					 + '</td><td class="size">'
					 + mail.size
					 + '</td></tr>');
			
			map[mail.uid] = el;
			el.click(function(event) {
				Navigator.toggleWaiting('Fetching Mail...');
				MailBox.getMail( [mail.uid], {
					'success' : function (mail) {
						Navigator.toggleWaiting();
						Navigator.setActiveView($('#view_mail'));
						MailView.update(mail);
					},
					'error' : function (error) {}
				});
				
			});
			
			el.appendTo($('#mail_table tbody'));
			})(mail);
		}
	};
})();


var MailView = new (function () {
	var I18n = {
		'views' : {
			'mail' : {
				'sent' : 'Empf&auml;nger:',
				'from' : 'Absender:',
				'to' : 'To:',
				'cc' : 'CC:',
				'bcc' : 'BCC:'
			}
		}
	};
	
	
	this.update = function (mail) {
		var el = $('#view_mail_information');
		var tpl = $('<p class="received"><span class="header">' + I18n.views.mail.sent + '</span><span>' + mail.date + '</span></p>'
		        + '<p class="from"><span class="header">' + I18n.views.mail.from + '</span><span class="user_male">' + mail.from + '</span></p>'
		        + '<p class="to"><span class="header">' + I18n.views.mail.to + '</span>' + '</p>'
		        + '<p class="cc"><span class="header">' + I18n.views.mail.cc + '</span>' + '</p>'
		        + '<p class="bcc"><span class="header">' + I18n.views.mail.bcc + '</span>' + '</p>');
		
		//var tpl = "goo";
		
		el.html(tpl);
		var el = $('<iframe frameborder="0" width="100%" height="450" />');
		$('#view_mail .htmlview').html(el);
		setTimeout( function() {
			var doc = el[0].contentWindow.document;
			var body = $('body',doc);
			body.html(mail.html);
		}, 1 );
		$('#view_mail .textview').html("<pre>" + mail.text + "</pre>");

		body.html(mail.text);
	};
	
})();

var Frontend = new (function () {
	var started = false;

	this.start = function () {
		if(started) return; // TODO: Error handling?
		
		require.reset();
		require.setModuleRoot('modules');
		require.ensure( [ 'json', 'net', 'mailbox' ], function(require) {
			var mailbox = require('mailbox');
			MailBox = new mailbox.MailBox({"endpoint": '/rpc'});

			Navigator.toggleWaiting('Fetching Folders...');
			MailBox.getFolders( [], {
				'success' : function (folders) {
					FolderList.setFolders(folders);
					Navigator.toggleWaiting();
					$(window).trigger('hashchange'); // Update selected folder.
				},
				'error' : function (error) {}
			});
		});

		$(window).bind('hashchange', function(event) {
			log('hashchange event fired.');
			Navigator.update();
		});
		
		$('.content').hide();
		
		var mailhtmlview = false;
		$('a.view_html').click(function(){
			mailhtmlview = true;
			$('div.htmlview').show();
			$('div.textview').hide();
		});
		$('a.view_text').click(function(){
			mailhtmlview = false;
			$('div.htmlview').hide();
			$('div.textview').show();
		});

		
		// TODO: Make Universalbar object that manages this shit.
		var activeSuggestion = $('#suggest ul li:first');
		activeSuggestion.toggleClass('active');
		$('#searchbox').keydown(function(event) {
			if (event.keyCode == '13') {
				event.preventDefault();
			} else if (event.keyCode == '38') { // arrow up
				activeSuggestion.toggleClass('active');
			} else if (event.keyCode == '40') { // arrow down

			}

		});
		$('#searchbox').focus(function(event) {
			$('#suggest').slideDown('normal');
		});
		$('#searchbox').blur(function(event) {
			$('#suggest').slideUp('normal');
		});
	};
})();

var MailBox;
