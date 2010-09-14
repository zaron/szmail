<fieldset id="settings_server_in">
	<legend>Eingangs-Server</legend>
	
	<div>
		<label for="protocol">Server-Typ</label>
		<select name="protocol">
			<option value="IMAP">IMAP</option>
			<option value="POP">POP</option>
		</select>
	</div>	
	
	<h4>Server-Konfiguration</h4>	
	
	<div class="row">
		<div>
			<label for="host">Host</label>
			<input name="host" type="text" value="imap.googlemail.com"/>
		</div>
		<div>
			<label for="port">Port</label>
			<input name="port" type="text" value="993"/>
		</div>
	</div>

	<div>
		<label for="username">Benutzername</label>
		<div class="input"><input name="username" type="text" value="enrico.grot"/></div>
	</div>
	
	<h4>Sicherheits-Konfiguration</h4>
	
	<div>
		<label for="security">Nutze Sichere Verbindung</label>
		<select name="security">
			<option value="none">Keine Verschl&uuml;sselung</option>
			<option value="ssl">SSL Verschl&uuml;sselung</option>
			<option value="tls">TLS Verschl&uuml;sselung</option>
		</select>
	</div>
	
	<div class="clear"></div>
	
	<div>
		<label for="authentication">Authentifikationsmethode</label>
		<select name="authentication">
			<option value="password">Passwort</option>
		</select>
	</div>
	
	<div class="clear"></div>
		
	<div>
		<label for="password">Passwort</label>
		<div class="input"><input name="password" type="password" value="enrico.grot"/></div>
	</div>
	
	<hr />
	
	<div class="right">
		<div class="button withText cancel"><a><img src="/images/placeholder.png" alt=""/>Abbrechen</a></div>
		<div class="button withText save"><a><img src="/images/placeholder.png" alt=""/>Speichern</a></div>
	</div>
</fieldset>