<form action="<?php echo esc_url(home_url('/')); ?>" id="elysium" method="post">
	<fieldset>
		<legend>Personnummer <span class="asterisk">*</span></legend>
		<input type="text" name="personnr" id="personnr">
		<label for="personnr">Ange ditt personnummer [ÅÅMMDD-1234]</label>
	</fieldset>
	<fieldset>
		<legend>Namn <span class="asterisk">*</span></legend>
		<input type="text" name="fornamn" id="fornamn">
		<label for="fornamn">Förnamn</label>
		<input type="text" name="efternamn" id="efternamn">
		<label for="fornamn">Efternamn</label>
	</fieldset>
	<fieldset>
		<legend>Adress <span class="asterisk">*</span></legend>
		<input type="text" name="gatuadress" id="gatuadress">
		<label for="gatuadress">Gatuadress</label>
		<input type="text" name="stad" id="stad">
		<label for="stad">Stad</label>
		<input type="text" name="postnr" id="postnr">
		<label for="postnr">Postnr</label>
	</fieldset>
	<fieldset>
		<legend>Kontakt</legend>
		<input type="text" name="mobiltelefon" id="mobiltelefon">
		<label for="mobiltelefon">Mobiltelefon</label>
		<input type="email" name="hemtelefon" id="hemtelefon">
		<label for="hemtelefon">Hemtelefon</label>
		<input type="email" name="epost" id="epost">
		<label for="epost">E-post</label>
		<input type="email" name="epost2" id="epost2">
		<label for="epost2">Bekräfta E-post</label>
	</fieldset>
	<button type="submit">Bli medlem nu</button>
</form>
