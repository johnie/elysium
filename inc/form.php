<?php
	$hasError = false;
	$forNameError = '';
	$efterNameError = '';

	if ( isset( $_POST['submitted'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {
		if ( trim( $_POST['fornamn'] ) === '' ) {
			$forNameError = 'Vänligen fyll i ditt förnamn';
			$hasError = true;
		}
		if ( trim( $_POST['efternamn'] ) === '' ) {
			$efterNameError = 'Vänligen fyll i ditt efternamn';
			$hasError§ = true;
		}

		$name = $_POST['fornamn'] . ' ' . $_POST['efternamn'];
		$post_type = elysium()->tag;

		$new_member = array(
			'post_title' => wp_strip_all_tags( $name ),
			'post_status' => 'private',
			'post_type' => $post_type
		);

		$mid = wp_insert_post( $new_member );

		if( isset( $_POST['elysium_personnr'] ) ) {
      update_post_meta( $mid, '_elysium_personnr', esc_attr( $_POST['elysium_personnr'] ) );
    }
		if( isset( $_POST['elysium_gatuadress'] ) ) {
      update_post_meta( $mid, '_elysium_gatuadress', esc_attr( $_POST['elysium_gatuadress'] ) );
    }
		if( isset( $_POST['elysium_stad'] ) ) {
      update_post_meta( $mid, '_elysium_stad', esc_attr( $_POST['elysium_stad'] ) );
    }
		if( isset( $_POST['elysium_postnr'] ) ) {
      update_post_meta( $mid, '_elysium_postnr', esc_attr( $_POST['elysium_postnr'] ) );
    }
		if( isset( $_POST['elysium_mobiltelefon'] ) ) {
      update_post_meta( $mid, '_elysium_mobiltelefon', esc_attr( $_POST['elysium_mobiltelefon'] ) );
    }
		if( isset( $_POST['elysium_hemtelefon'] ) ) {
      update_post_meta( $mid, '_elysium_hemtelefon', esc_attr( $_POST['elysium_hemtelefon'] ) );
    }
		if( isset( $_POST['elysium_epost'] ) ) {
      update_post_meta( $mid, '_elysium_epost', esc_attr( $_POST['elysium_epost'] ) );
    }
	}
?>

<form action="" id="elysium" method="POST">
	<fieldset>
		<legend><?php _e("Personnummer", "elysium"); ?> <span class="asterisk">*</span></legend>
		<input type="text" name="elysium_personnr" id="personnr" class="required">
		<label for="personnr"><?php _e("Ange ditt personnummer [ÅÅMMDD-1234]", "elysium"); ?></label>
	</fieldset>
	<fieldset>
		<legend><?php _e("Namn", "elysium"); ?> <span class="asterisk">*</span></legend>
		<input type="text" name="fornamn" id="fornamn" class="required">
		<label for="fornamn"><?php _e("Förnamn", "elysium"); ?></label>
		<?php if ( $forNameError != ''): ?>
			<span class="error"><?php echo $forNameError; ?></span>
		<?php endif; ?>
		<input type="text" name="efternamn" id="efternamn" class="required">
		<label for="fornamn"><?php _e("Efternamn", "elysium"); ?></label>
		<?php if ( $efterNameError != ''): ?>
			<span class="error"><?php echo $efterNameError; ?></span>
		<?php endif; ?>
	</fieldset>
	<fieldset>
		<legend><?php _e("Adress", "elysium"); ?> <span class="asterisk">*</span></legend>
		<input type="text" name="elysium_gatuadress" id="gatuadress" class="required">
		<label for="gatuadress"><?php _e("Gatuadress", "elysium"); ?></label>
		<input type="text" name="elysium_stad" id="stad" class="required">
		<label for="stad"><?php _e("Stad", "elysium"); ?></label>
		<input type="text" name="elysium_postnr" id="postnr" class="required">
		<label for="postnr"><?php _e("Postnr", "elysium"); ?></label>
	</fieldset>
	<fieldset>
		<legend><?php _e("Kontakt", "elysium"); ?></legend>
		<input type="text" name="elysium_mobiltelefon" id="mobiltelefon">
		<label for="mobiltelefon"><?php _e("Mobiltelefon", "elysium"); ?></label>
		<input type="text" name="elysium_hemtelefon" id="hemtelefon">
		<label for="hemtelefon"><?php _e("Hemtelefon", "elysium"); ?></label>
		<input type="email" name="elysium_epost" id="epost">
		<label for="epost"><?php _e("E-post", "elysium"); ?></label>
		<input type="email" name="elysium_epost2" id="epost2">
		<label for="epost2"><?php _e("Bekräfta E-post", "elysium"); ?></label>
	</fieldset>
	<fieldset>
		<input type="hidden" name="submitted" id="submitted" value="true" />
		<button type="submit"><?php _e("Bli medlem nu", "elysium"); ?></button>
		<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
	</fieldset>
</form>
