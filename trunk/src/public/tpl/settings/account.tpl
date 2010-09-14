<fieldset id="settings_account">
	<legend>Konto-Einstellungen</legend>

	<div>
		<div class="description">Geben Sie einen passenden Namen f&uuml;r dieses Konto an. <br />Zum Beispiel "Arbeit" oder "Pers&ouml;nlich".</div>
		<label for="account_name">Konto Name</label>
		<div class="input"><input name="account_name" id="account_name" type="text" value="GMail"/></div>
	</div>
	

	<h4>Ben&ouml;tigte Informationen</h4>
	
	<div>
		<label for="account_username">Vollst&auml;ndiger Name</label>
		<div class="input"><input name="account_username" id="account_username" type="text" value="Enrico Grot"/></div>
	</div>
	
	<div>
		<label for="account_email">eMail Adresse</label>
		<div class="input"><input name="account_email" id="account_email" type="text" value="Enrico.Grot@gmail.com"/></div>
	</div>


	<h4>Optionale Informationen</h4>

	<div>
		<input name="account_default" id="account_default" type="checkbox"" checked="checked"/>
		<label class="right" for="account_default">Dieses Konto als Standard-Konto nutzen</label>
	</div>

	<div>
		<label for="account_replyTo">Antworten an</label>
		<div class="input"><input name="account_replyTo" id="account_replyTo" type="text" value=""/></div>
	</div>
	
	<hr />
	
	<div class="right">
		<div class="button withText cancel"><a><img src="/images/placeholder.png" alt=""/>Abbrechen</a></div>
		<div class="button withText save"><a><img src="/images/placeholder.png" alt=""/>Speichern</a></div>
	</div>
</fieldset>