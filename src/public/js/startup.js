
var MailBox;

function log (msg) {
	$('#debug').prepend(msg+'<br/>');
} 

function loadTemplate (id, url) {
	// TODO: store which templates are already loaded and compiled then decide about loading.
	var data = $.ajax({ type: "GET", url: url, async: false }).responseText;
	return $.template(id, data);
};

var templates = {};
function insertTemplateSet(set) {
	for (var id in set.templates) {
		if(templates[set.templates[id]]) continue;
		templates[set.templates[id]] = loadTemplate(set.templates[id],'tpl/' + set.templates[id] + '.tpl');
	};
	set.insert(templates[set.templates[set.base]]);
};

function View (options) {
	var loaded = false;
	var anchor = null;
	var placeholders = {};
	
	this.show = function () {
		if (loaded) {
			anchor.show();

		} else {
			insertTemplateSet({
				base      : options.base ? options.base : 0, 
				templates : options.templates,
				insert    : function (tmpl) {
					anchor = options.insert(tmpl);
					anchor.hide();
					anchor.show();
					loaded = true;
				}
			});
		}
		for (var id in placeholders) {
			placeholders[id].show();
		}
	};
	
	this.hide = function () {
		if (!loaded) {
			alert('View not loaded yet!?');
			return;
		}
		anchor.hide();
	};
	
	this.setPlaceholder = function (name, placeholder) {
		if(placeholders[name] && placeholders[name] != placeholder) placeholders[name].hide();
		placeholders[name] = placeholder;
	};
};

/* Perspectives */
var TwoColumnsPerspective = new View({
	templates : ['base/layout','base/header','base/aside','base/content','base/footer','base/universalbar','base/accounts'],
	insert    : function (tmpl) {
		var anchor = $.tmpl(tmpl).appendTo($('body'));
		$(".dropdown dt").click(function() {
			$(".dropdown dt").css("-moz-border-radius","3px 3px 0 0");
		    $(".dropdown dd ul").toggle();
		});
		return anchor;
	}
});

/* Views */
var SettingsSidebarView = new View({
	templates : ['settings/sidebar'],
	insert    : function (tmpl) {
		return $.tmpl(tmpl).appendTo($('#aside'));
	}
});

var MailboxSidebarView = new View({
	templates : ['mail/sidebar','mail/folder/_folderlist_item'],
	insert    : function (tmpl) {
		return $.tmpl(tmpl).appendTo($('#aside'));
	}
});

var MailboxView = new View({
	templates  : ['widgets/breadcrumbs','mail/folder/content'],
	base       : 1, 
	insert     : function (tmpl) {
		alert("about to insert");
		return $.tmpl(tmpl).appendTo($('#content'));
	}
});

MailboxView.update = function () {
	MailBox.getFolders( [], {
		'success' : function (folders) {
			MailboxView.setFolders(folders);
			//Navigator.toggleWaiting();
			//$(window).trigger('hashchange'); // Update selected folder.
		},
		'error' : function (error) {}
	});
};
MailboxView.setFolders = function (folders) {
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
		if(folder.type) {
			folder.name = specialFolderNames[folder.type];
			specialFolders[folder.type] = folder;
		}
	}
	
	for (var id in specialFolders) {
		var folder = specialFolders[id];
		$.tmpl('mail/folder/_folderlist_item',folder).appendTo($('#special_folders'));
	}
	
	for (var id in folders) {
		var folder = folders[id];
		if(folder.type) continue;
		$.tmpl('mail/folder/_folderlist_item',folder).prependTo($('#folders'));
	}
};

var SettingsAccountView = new View({
	templates : ['settings/account/content'],
	insert    : function (tmpl) {
		return $.tmpl(tmpl).appendTo($('#content'));
	}
});

var SettingsAccountIdentityView = new View({
	templates : ['settings/account/identity'],
	insert    : function (tmpl) {
		return $.tmpl(tmpl).appendTo($('#account div.content'));
	}
});

var SettingsAccountServersView = new View({
	templates : ['settings/account/servers'],
	insert    : function (tmpl) {
		return $.tmpl(tmpl).appendTo($('#account div.content'));
	}
});

var EmptyView = {
	show : function(){},
	hide : function(){}
};

/* Pages */
var SettingsPage = {
	name : 'settings',
	show : function() {
		TwoColumnsPerspective.setPlaceholder('aside',SettingsSidebarView);
		SettingsAccountView.setPlaceholder('content',EmptyView);
		TwoColumnsPerspective.setPlaceholder('content',SettingsAccountView);
		TwoColumnsPerspective.show();
		$('div.settings.nav').hide();
	}
};

var MailboxPage = {
	name : 'mailbox',
	show : function() {
		TwoColumnsPerspective.setPlaceholder('aside',MailboxSidebarView);
		MailboxView.setPlaceholder('content',EmptyView);
		TwoColumnsPerspective.setPlaceholder('content',MailboxView);
		TwoColumnsPerspective.show();
		MailboxView.update();
		$('div.settings.nav').hide();
	}
};

var SettingsAccountPage = {
	name : 'settings/account',
	show : function() {
		TwoColumnsPerspective.setPlaceholder('aside',SettingsSidebarView);
		SettingsAccountView.setPlaceholder('content',SettingsAccountIdentityView);	
		TwoColumnsPerspective.setPlaceholder('content',SettingsAccountView);
		TwoColumnsPerspective.show();
		$('div.settings.nav').show();
	}
};

var SettingsServerPage = {
	name : 'settings/server',
	show : function() {
		TwoColumnsPerspective.setPlaceholder('aside',SettingsSidebarView);
		SettingsAccountView.setPlaceholder('content',SettingsAccountIdentityView);	
		TwoColumnsPerspective.setPlaceholder('content',SettingsAccountView);
		TwoColumnsPerspective.show();
		$('div.settings.nav').show();
	}
};
	
function startup() {
	require.reset();
	require.setModuleRoot('modules');
	require.ensure( [ 'json', 'net', 'mailbox' ], function(require) {
		// create new MailBox object.
		MailBox = new (require('mailbox')).MailBox({"endpoint": '/rpc'});
		
		// register pages.
		Navigator.registerPage(SettingsPage);
		Navigator.registerPage(SettingsAccountPage);
		Navigator.registerPage(MailboxPage);
		
		// register onhashchange event handler.
		$(window).bind('hashchange', function(event) {
			Navigator.update();
		});
	
		// trigger onhashchange for the first time.
		$(window).trigger('hashchange');
	});
}