
var activeView;

function log(msg)
{
	$('#debug').prepend(msg+'<br/>');
}

var Navigator = {
	getPath : function () {
		var hash = location.hash;
		if(!hash.match(/^#!\/.+\?/)) return; // TODO: error handling
		var path = new String((/^#!\/.+\?/).exec(hash));
		path = path.substr(2);
		return path.substr(0,path.length-2);
	}
};

var InboxNavigationClass = function () {
	var activeFolder = null;
	var map = {};
	
	/**
	 * 
	 */
	this.setFolders = function (folders) {
		log("setFolders called.");

		var specialFolders = {
			'INBOX'  : null,
			'DRAFTS' : null,
			'SENT'   : null,
			'SPAM'   : null,
			'TRASH'  : null
		};
		
		for (var id in folders) {
			var folder = folders[id];
		
			var el = $('<li><img src="/images/placeholder.png"' + ((folder.type) ? ' class="' + folder.type.toLowerCase() + '"':'') + ' alt="" /><a href="#!/' + folder.name + '/?">' + folder.name + ((folder.unread > 0) ? ' ('+ folder.unread + ')' : '') + '<span class="small">'+ folder.mails +'</span></a><span><a class="edit"></a><a class="delete"></a></span></li>');
			log('map['+'/'+folder.name+'] added');
			map['/'+folder.name] = el;

		
			// Click-function for folders
			el.click(function(event) {
				log("folder '"+folder.name+"' clicked.");
				setActiveView($('#view_folders'));
			});

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
	
	/**
	 * 
	 */
	this.update = function () {
		var path = Navigator.getPath();
		if (activeFolder)
			map[activeFolder].removeClass('active');
		activeFolder = path;
		map[activeFolder].addClass('active');
		log('InboxNavigation updated.');
	};
};

var InboxNavigation = new InboxNavigationClass();


function gui_showMail(mail)
{
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
	var el = $('#view_mail');
	var tpl = '<p class="received"><span class="header"><%= I18n.views.mail.sent %></span><span><%= mail.date %></span></p>'
	        + '<p class="from"><span class="header"><%= I18n.views.mail.from %></span><span class="user_male"><%= mail.from.name %></span></p>'
	        + '<p class="to"><span class="header"><%= I18n.views.mail.to %></span><% _.each(mail.to, function(person) { %><span class="user_male"><%= person.name %></span><% } %></p>'
	        + '<p class="cc"><span class="header"><%= I18n.views.mail.cc %></span><% _.each(mail.cc, function(person) { %><span class="user_male"><%= person.name %></span><% } %></p>'
	        + '<p class="bcc"><span class="header"><%= I18n.views.mail.bcc %></span><% _.each(mail.bcc, function(person) { %><span class="user_male"><%= person.name %></span><% } %></p>';
	_.template(tpl, {I18n : I18n, mail: mail});

}

function gui_addMail(mail)
{
	log("gui_addMail called.");
	var el = $('<tr><td class="checkbox"><div class="checkbox">'
			 + '<input type="checkbox" name="" /></div></td>'
			 + '<td class="attachment"><a class="'+((mail.attachments != null)?'':'no')+'attachment">'
			 + '</a></td><td class="subject"><b><a href="#!/?m='+ mail.id +'"><i>'
			 + mail.subject
			 + '</i></a></b></td><td class="from"><a class="">'
			 + mail.from
			 + '</a></td><td class="date">'
			 + mail.date
			 + '</td><td class="size">'
			 + mail.size
			 + '</td></tr>');
	el.click(function(event) {
		log("mail clicked.");
		event.preventDefault();
		setActiveView($('#view_mail'));
		
	});
	el.appendTo($('#mail_table tbody'));
}

function setActiveView(view) {
	if (activeView != view) {
		activeView.hide();
		activeView = view;
		activeView.show();
	}
}



function startFrontend() {
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