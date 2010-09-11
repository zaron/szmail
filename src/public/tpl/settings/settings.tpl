<div>

	<h2>Settings</h2>

	<fieldset style="border:1px solid black;">
		<legend>Servers</legend>
		<div style="margin:10px;width:49%;float:left;"> 
			<select style="width:100%;" size="6">
				<option>gmail</option>
				<option>gmx</option>
				<option>yahoo</option>
			</select>
			
			<span>add</span>
			<span>delete</span>
		</div>
		
		<div style="width:49%;float:left;">
			
			<div class="row">
				<div>
					<label for="server"></label>
					<input name="server" type="text" value="gmail"/>
				</div>
			</div>
			
			<div class="row">
				<div style="float:left;width:60%">
					<label for="host">Host:</label>
					<input name="host" type="text" value="imap.googlemail.com"/>
				</div>
				<div style="float:left;width:35%;margin-left:10px;">
					<label for="port">Port:</label>
					<input name="port" type="text" value="993"/>
				</div>
			</div>
			
			<div class="row">
				<div style="float:left;width:60%">
					<label for="protocol">Protocol:</label>
					<input name="protocol" type="text" value="IMAP"/>
				</div>
				<div style="float:left;width:35%;margin-left:10px;">
					<label for="security">Security:</label>
					<input name="security" type="text" value="SSL"/>
				</div>
			</div>
			
			<div class="row">
				<label for="description">Description:</label>
				<input name="description" type="text" value="" height="150px;"/>
			</div>
			
			<div>add/edit</div>
			
		</div>
		<div style="clear:both;"></div>
	</fieldset>
	
</div>