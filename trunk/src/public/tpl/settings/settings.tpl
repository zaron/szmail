<div class="content">

	<h2>Settings</h2>

<fieldset>
		<legend>Accounts</legend>
		<div> 
			<select style="width:100%;" size="6">
				<option>enrico.grot - gmail [imap]</option>
				<option>enrico.grot - gmail [smtp]</option>
				<option>yahoo</option>
			</select>
			
			<div class="button withText"><a><img src="/images/placeholder.png" alt=""/>Neues Konto</a></div>
		</div>
		
		<div>
			
			<div>
					<label for="protocol">Server:</label>
					<select name="protocol">
						<option value="gmail">gmail [imap]</option>
						<option value="gmail_smtp">gmail [smtp]</option>
						<option value="gmx">gmx</option>
						<option value="yahoo">yahoo</option>
					</select>
			</div>
			
			<div class="row">
				<div>
					<label for="password">Passwort</label>
					<input name="password" type="text" value="enrico.grot@gmail.com"/>
				</div>
			</div>
			
			<div class="row">
				<div>
					<label for="username">Name</label>
					<input name="username" type="text" value="enrico.grot@gmail.com"/>
				</div>
			</div>

			
			<div class="row">
				<label for="description">Description:</label>
				<input name="description" type="text" value="" height="150px;"/>
			</div>
			
			<div class="button withText"><a><img src="/images/placeholder.png" alt=""/>L&ouml;schen</a></div>
			<div class="button withText"><a><img src="/images/placeholder.png" alt=""/>Konto Testen</a></div>
			<div class="button withText"><a><img src="/images/placeholder.png" alt=""/>Speichern</a></div>
			
		</div>
	</fieldset>

	<fieldset>
		<legend>Servers</legend>
		<div> 
			<select style="width:100%;" size="6">
				<option>gmail [imap] - 993:imap.googlemail.com [ssl]</option>
				<option>gmx</option>
				<option>yahoo</option>
			</select>
			
			<div class="button withText"><a><img src="/images/placeholder.png" alt=""/>Neuer Server</a></div>
		</div>
		
		<div>
			
			<div class="row">
				<div>
					<label for="server">Name</label>
					<input name="server" type="text" value="gmail"/>
				</div>
			</div>
			
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
			
			<div class="row">
				<div>
					<label for="protocol">Protocol:</label>
					<select name="protocol">
						<option value="IMAP">IMAP</option>
						<option value="POP3">POP3</option>
						<option value="SMTP">SMTP</option>
					</select>
				</div>
				<div>
					<label for="security">Security:</label>
						<select name="security">
						<option value="none">-</option>
						<option value="ssl">SSL</option>
					</select>
				</div>
			</div>
			
			<div class="row">
				<label for="description">Description:</label>
				<input name="description" type="text" value="" height="150px;"/>
			</div>
			
			<div class="button withText"><a><img src="/images/placeholder.png" alt=""/>L&ouml;schen</a></div>
			<div class="button withText"><a><img src="/images/placeholder.png" alt=""/>Verbindung Testen</a></div>
			<div class="button withText"><a><img src="/images/placeholder.png" alt=""/>Speichern</a></div>
			
		</div>
	</fieldset>
	
</div>