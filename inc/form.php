<form action="" id="elysium" method="post">
	<fieldset>
		<legend><?php _e("Personnummer", "elysium"); ?> <span class="asterisk">*</span></legend>
		<input type="text" name="personnr" id="personnr" class="required">
		<label for="personnr"><?php _e("Ange ditt personnummer [ÅÅMMDD-1234]", "elysium"); ?></label>
	</fieldset>
	<fieldset>
		<legend><?php _e("Namn", "elysium"); ?> <span class="asterisk">*</span></legend>
		<input type="text" name="fornamn" id="fornamn" class="required">
		<label for="fornamn"><?php _e("Förnamn", "elysium"); ?></label>
		<input type="text" name="efternamn" id="efternamn" class="required">
		<label for="fornamn"><?php _e("Efternamn", "elysium"); ?></label>
	</fieldset>
	<fieldset>
		<legend><?php _e("Adress", "elysium"); ?> <span class="asterisk">*</span></legend>
		<input type="text" name="gatuadress" id="gatuadress" class="required">
		<label for="gatuadress"><?php _e("Gatuadress", "elysium"); ?></label>
		<input type="text" name="stad" id="stad" class="required">
		<label for="stad"><?php _e("Stad", "elysium"); ?></label>
		<input type="text" name="postnr" id="postnr" class="required">
		<label for="postnr"><?php _e("Postnr", "elysium"); ?></label>
	</fieldset>
	<fieldset>
		<legend><?php _e("Kontakt", "elysium"); ?></legend>
		<input type="text" name="mobiltelefon" id="mobiltelefon">
		<label for="mobiltelefon"><?php _e("Mobiltelefon", "elysium"); ?></label>
		<input type="email" name="hemtelefon" id="hemtelefon">
		<label for="hemtelefon"><?php _e("Hemtelefon", "elysium"); ?></label>
		<input type="email" name="epost" id="epost">
		<label for="epost"><?php _e("E-post", "elysium"); ?></label>
		<input type="email" name="epost2" id="epost2">
		<label for="epost2"><?php _e("Bekräfta E-post", "elysium"); ?></label>
	</fieldset>
	<fieldset>
		<input type="hidden" name="submitted" id="submitted" value="true" />
		<button type="submit"><?php _e("Bli medlem nu", "elysium"); ?></button>
	</fieldset>
</form>
