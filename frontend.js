var activeFolder;

var activeView;

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
	var el = $('<tr><td class="checkbox"><div class="checkbox">'
			 + '<input type="checkbox" name="" /></div></td>'
			 + '<td class="attachment"><a class="noattachment">'
			 + '</a></td><td class="subject"><b><a href=""><i>'
			 + mail.subject
			 + '</i></a></b></td><td class="from"><a class="">'
			 + mail.from
			 + '</a></td><td class="date">'
			 + mail.date
			 + '</td><td class="size">'
			 + mail.size
			 + '</td></tr>');
	el.click(function(event) {
		var view = $('#view_mail');
		if (activeView != view) {
			activeView.hide();
			activeView = view;
			activeView.show();
		}
	});
	el.appendTo($('#mail_table tbody'));
}


function gui_addFolder(folder, where) {
	if(folder.type != '') where = "#special_folders .n.separator"; 
	
	var el = $('<li><a' + ((folder.type != '') ? ' class="' + folder.type.toLowerCase() + '"':'') + ' href="#!/?ai=' + folder.id + '">' + folder.name + ' ('+ folder.unread +')</a><span><a class="edit"></a><a class="delete"></a></span></li>');
	el.insertBefore(where);
	
	// Click-function for Folders
	el.click(function(event) {
		var view = $('#view_folders');
		if (activeView != view) {
			activeView.hide();
			activeView = view;
			activeView.show();
		}
		if (activeFolder)
			activeFolder.removeClass('active');
		el.addClass('active');
		activeFolder = el;
	});
};

function startFrontend() {
	activeFolder = $('#navigation li.active');

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