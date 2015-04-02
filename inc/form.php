<?php
	$hasError = false;
	$nameError = '';

	if ( isset( $_POST['submitted'] ) ) {
		if ( trim( $_POST['fornamn'] ) === '' ) {
			$nameError = 'Vänligen fyll i ditt förnamn';
			$hasError = true;
		}
		if ( trim( $_POST['efternamn'] ) === '' ) {
			$nameError = 'Vänligen fyll i ditt efternamn';
			$hasError = true;
		}

		$name = $_POST['fornamn'] . ' ' . $_POST['efternamn'];
		$post_type = elysium()->tag;

		$new_member = array(
			'post_title' => wp_strip_all_tags( $name ),
			'post_status' => 'private',
			'post_type' => $post_type
		);

		$mid = wp_insert_post( $new_member );
	}
?>

<form action="" id="elysium" method="POST">
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
		<?php if ( $nameError != ''): ?>
			<span class="error"><?php echo $nameError; ?></span>
		<?php endif; ?>
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
