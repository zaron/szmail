


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
		if(!hash.match(/^#!\/.+\?/)) return; // TODO: error handling
		var path = new String((/^#!\/.+\?/).exec(hash));
		path = path.substr(2);
		return path.substr(0,path.length-2);
	};
	
	this.getParam = function(param) {
		var hash = location.hash;
		if(hash.indexOf('?') == hash.length) return; // TODO: error handling
		var path = this.getPath();
		
	};
	
	this.update = function () {
		var path = this.getPath();
		if(lastPath == path) return;
		lastPath = path;
		
		FolderList.update(path);
	};
	
	var waiting = false;
	var waitingIntervalId = null;
	var offset = 0;
	this.toggleWaiting = function() {
		var el = $('#searchbox');
		if(!waiting) {
			el.css("background","url(/images/indeterminate.png)");
			waiting = true;
			waitingIntervalId = setInterval(function(){
				el.css("background-position","0px " + offset + "px");
				offset = (offset + 4) % 178; 
			},100);
			return;
		}
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
	}

	
})();

var MailboxCache = new (function () {
	this.hasMail = function (folder,uid) {
		
	};
})();

var FolderList = new (function () {
	var activeFolder = null;
	var map = {};
	
	/**
	 * 
	 */
	this.setFolders = function (folders) {
		log("setFolders called.");
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
			log('map['+folder.global+'] added');
			map["/" + folder.global] = el;
		
			// Click-function for folders
			(function (folder){
				el.click(function (event) {
					log("folder '"+folder.global+"' clicked.");
					Navigator.toggleWaiting();
					MailBox.getMails( [folder.global], {
						'success' : function(mails) {
							Navigator.toggleWaiting();
							InboxView.setMails(mails);
						},
						'error' : function(error) {}
					});
				});
			})(folder);
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

	this.update = function (path) {
		if (activeFolder && activeFolder != path) {
			map[activeFolder].removeClass('active');
			Navigator.setActiveView($('#view_folders'));
		}
		activeFolder = path;
		map[activeFolder].addClass('active');
		log('InboxNavigation updated.');
	};
	
})();

var InboxView = new (function () {
	var map = [];
	this.setMails = function (mails) {
		$('#mail_table tbody').html('');
		for(var id in mails) {
			var mail = mails[id];
			
			//var el = document.createElement('tr');
			
			var el = $('<tr><td class="checkbox"><div class="checkbox">'
					 + '<input type="checkbox" name="" /></div></td>'
					 + '<td class="attachment"><a class="'+((mail.attachments != null)?'':'no')+'attachment">'
					 + '</a></td><td class="subject"><b><a href="#!'+Navigator.getPath()+'?m='+ mail.id +'"><i>'
					 + mail.subject
					 + '</i></a></b></td><td class="from"><a class="">'
					 + mail.from
					 + '</a></td><td class="date">'
					 + mail.date
					 + '</td><td class="size">'
					 + mail.size
					 + '</td></tr>');
			map[mail.uid] = el;
			el.click(function(event) {
				log("mail clicked.");
				Navigator.setActiveView($('#view_mail'));				
			});
			el.appendTo($('#mail_table tbody'));
		}
	};
})();

var MailView = new (function () {
	var I18n = {
		'views' : {
			'mail' : {
				'sent' : 'Sent:',
				'from' : 'From:',
				'to' : 'To:',
				'cc' : 'CC:',
				'bcc' : 'BCC:'
			}
		}
	};
	
	var el = $('#view_mail_information');
	var tpl = '<p class="received"><span class="header"><%= I18n.views.mail.sent %></span><span><%= mail.date %></span></p>'
	        + '<p class="from"><span class="header"><%= I18n.views.mail.from %></span><span class="user_male"><%= mail.from.name %></span></p>'
	        + '<p class="to"><span class="header"><%= I18n.views.mail.to %></span><% _.each(mail.to, function(person) { %><span class="user_male"><%= person.name %></span><% } %></p>'
	        + '<p class="cc"><span class="header"><%= I18n.views.mail.cc %></span><% _.each(mail.cc, function(person) { %><span class="user_male"><%= person.name %></span><% } %></p>'
	        + '<p class="bcc"><span class="header"><%= I18n.views.mail.bcc %></span><% _.each(mail.bcc, function(person) { %><span class="user_male"><%= person.name %></span><% } %></p>';
	
	this.update = function (mail) {
		el.html(_.template(tpl, {I18n : I18n, mail: mail}));
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

			Navigator.toggleWaiting();
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
