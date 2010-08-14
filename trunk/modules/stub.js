//require.define({'stub': function(require, exports, module) {

exports.getFoldersResponse = {
	"folders" : [ {
		"id" : "0",
		"name" : "Posteingang",
		"type" : "INBOX",
		"mails" : 12543,
		"unread" : 3,
		"folders" : []
	}, {
		"id" : "1",
		"name" : "Ent&uuml;rfe",
		"type" : "DRAFTS",
		"mails" : 756,
		"unread" : 0,
		"folders" : []
	}, {
		"id" : "2",
		"name" : "Gesendet",
		"type" : "SENT",
		"mails" : 4612,
		"unread" : 0,
		"folders" : []
	}, {
		"id" : "3",
		"name" : "Spam",
		"type" : "SPAM",
		"mails" : 1152,
		"unread" : 7,
		"folders" : []
	}, {
		"id" : "4",
		"name" : "Papierkorb",
		"type" : "TRASH",
		"mails" : 53,
		"unread" : 0,
		"folders" : []
	}, {
		"id" : "5",
		"name" : "Foldername",
		"mails" : 11,
		"unread" : 0,
		"folders" : []
	}, {
		"id" : "6",
		"name" : "Foldername",
		"mails" : 24,
		"unread" : 0,
		"folders" : []
	}, {
		"id" : "7",
		"name" : "Foldername",
		"mails" : 45,
		"unread" : 0,
		"folders" : []
	}, {
		"id" : "8",
		"name" : "Foldername",
		"mails" : 7,
		"unread" : 0,
		"folders" : []
	} ]
}

exports.getMailResponse = {
	"id" : "0", // internal unique identifier, MUST NOT refer to the uuid in IMAP  
	"subject" : "Subject Text", // MUST be UTF-8 encoded and MUST be escaped.
	"date" : "UTC Time", // MUST be formatted according to RFCxyz... 
	"from" : {
		"id" : "0", // internal contact id (focus on later implementation?)
		"name" : "",
		"address" : "foo@bar.tld",
		"icon" : ""
	},
	"to" : [ {
		"id" : "5",
		"name" : "",
		"address" : "foo@bar.tld",
		"icon" : ""
	}, {
		"id" : "2",
		"name" : "",
		"address" : "foo@bar.tld",
		"icon" : ""
	} ],
	"cc" : [ {
		"id" : "3",
		"name" : "",
		"address" : "foo@bar.tld",
		"icon" : ""
	}, {
		"id" : "7",
		"name" : "",
		"address" : "foo@bar.tld",
		"icon" : ""
	} ],
	// Only in sent mails.
	"bcc" : [ {
		"id" : "1",
		"name" : "",
		"address" : "foo@bar.tld",
		"icon" : ""
	}, {
		"id" : "6",
		"name" : "",
		"address" : "foo@bar.tld",
		"icon" : ""
	} ],
	"answered" : true,
	"size" : "23", // size with or without attachments?
	"attachments" : [ {
		"id" : "22",
		"name" : "foo.png",
		"type" : "image/png",
		"size" : "42"
	} ],
	"body" : "" // MUST be escaped and MUST be UTF-8 encoded.
};

//}},[]);