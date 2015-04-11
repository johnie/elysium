<?php
	$hasError = false;
	$forNameError = '';
	$efterNameError = '';

	if (
		isset( $_POST['submitted'] ) &&
		isset( $_POST['post_nonce_field'] ) &&
		wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) &&
		elysium()->personnr_exist('elysium_personnr')
	) {
		if ( trim( $_POST['fornamn'] ) === '' ) {
			$forNameError = 'Vänligen fyll i ditt förnamn';
			$hasError = true;
		}
		if ( trim( $_POST['efternamn'] ) === '' ) {
			$efterNameError = 'Vänligen fyll i ditt efternamn';
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

		if( isset( $_POST['elysium_personnr'] ) \Personnummer::valid( $_POST['elysium_personnr'] ) ) {
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

			$json = file_get_contents('http://yourmoneyisnowmymoney.com/api/zipcodes/?zipcode='. preg_replace('/\s+/','', $_POST['elysium_postnr']) );
			$obj = json_decode($json);
			$address = $obj->results[0]->address;
			$lan = explode(', ', $address);

			update_post_meta( $mid, '_elysium_region', esc_attr( $lan[1] ) );
		}
		if( isset( $_POST['elysium_mobiltelefon'] ) ) {
			update_post_meta( $mid, '_elysium_mobiltelefon', esc_attr( $_POST['elysium_mobiltelefon'] ) );
		}
		if( isset( $_POST['elysium_hemtelefon'] ) ) {
			update_post_meta( $mid, '_elysium_hemtelefon', esc_attr( $_POST['elysium_hemtelefon'] ) );
		}
		if( isset( $_POST['elysium_epost'] ) && is_email( $_POST['elysium_epost'] ) ) {
			update_post_meta( $mid, '_elysium_epost', esc_attr( $_POST['elysium_epost'] ) );
		}
		if( isset( $_POST['elysium_dhr_medlem'] ) ) {
			update_post_meta( $mid, '_elysium_dhr_medlem', esc_attr( 'on' ) );
		}
		elysium()->send_mail($_POST['fornamn'], $_POST['efternamn'], $_POST['elysium_epost']);
	}
?>

<form action="" id="elysium" class="medlem-form" method="POST">
	<fieldset>
		<legend><?php _e("Personnummer", "elysium"); ?> <span class="asterisk">*</span></legend>
		<div class="medlem-input">
			<input type="text" class="input__field--isao" name="elysium_personnr" id="personnr" maxlength="11" required placeholder="<?php _e("921224-1337", "elysium"); ?>">
			<label for="personnr" class="input__label--isao" data-content="<?php _e("Personnummer [ÅÅMMDD-1234]", "elysium"); ?>">
				<span class="input__label-content--isao"><?php _e("Personnummer [ÅÅMMDD-1234]", "elysium"); ?></span>
			</label>
		</div>
	</fieldset>
	<fieldset>
		<legend><?php _e("Namn", "elysium"); ?> <span class="asterisk">*</span></legend>
		<div class="medlem-input">
			<input type="text" class="input__field--isao" name="fornamn" id="fornamn" required placeholder="<?php _e("John", "elysium"); ?>">
			<label for="fornamn" class="input__label--isao" data-content="<?php _e("Förnamn", "elysium"); ?>">
				<span class="input__label-content--isao"><?php _e("Förnamn", "elysium"); ?></span>
			</label>
		</div>
		<?php if ( $forNameError != ''): ?>
			<span class="error"><?php echo $forNameError; ?></span>
		<?php endif; ?>
		<div class="medlem-input">
			<input type="text" class="input__field--isao" name="efternamn" id="efternamn" required placeholder="<?php _e("Doe", "elysium"); ?>">
			<label for="efternamn" class="input__label--isao" data-content="<?php _e("Efternamn", "elysium"); ?>">
				<span class="input__label-content--isao"><?php _e("Efternamn", "elysium"); ?></span>
			</label>
		</div>
		<?php if ( $efterNameError != ''): ?>
			<span class="error"><?php echo $efterNameError; ?></span>
		<?php endif; ?>
	</fieldset>
	<fieldset>
		<legend><?php _e("Adress", "elysium"); ?> <span class="asterisk">*</span></legend>
		<div class="medlem-input">
			<input type="text" class="input__field--isao" name="elysium_gatuadress" id="gatuadress" required placeholder="<?php _e("Närgången 1", "elysium"); ?>">
			<label for="gatuadress" class="input__label--isao" data-content="<?php _e("Gatuadress", "elysium"); ?>">
				<span class="input__label-content--isao"><?php _e("Gatuadress", "elysium"); ?></span>
			</label>
		</div>
		<div class="medlem-input">
			<input type="text" class="input__field--isao" name="elysium_stad" id="stad" required placeholder="<?php _e("Göteborg", "elysium"); ?>">
			<label for="stad" class="input__label--isao" data-content="<?php _e("Stad", "elysium"); ?>">
				<span class="input__label-content--isao"><?php _e("Stad", "elysium"); ?></span>
			</label>
		</div>
		<div class="medlem-input">
			<input type="text" class="input__field--isao" name="elysium_postnr" id="postnr" required placeholder="<?php _e("417 56", "elysium"); ?>">
			<label for="postnr" class="input__label--isao" data-content="<?php _e("Postnr", "elysium"); ?>">
				<span class="input__label-content--isao"><?php _e("Postnr", "elysium"); ?></span>
			</label>
		</div>
	</fieldset>
	<fieldset>
		<legend><?php _e("Kontakt", "elysium"); ?></legend>
		<div class="medlem-input">
			<input type="text" class="input__field--isao" name="elysium_mobiltelefon" id="mobiltelefon" placeholder="<?php _e("073- 999 13 37", "elysium"); ?>">
			<label for="mobiltelefon" class="input__label--isao" data-content="<?php _e("Mobiltelefon", "elysium"); ?>">
				<span class="input__label-content--isao"><?php _e("Mobiltelefon", "elysium"); ?></span>
			</label>
		</div>
		<div class="medlem-input">
			<input type="text" class="input__field--isao" name="elysium_hemtelefon" id="hemtelefon" placeholder="<?php _e("08 – 685 80 80", "elysium"); ?>">
			<label for="hemtelefon" class="input__label--isao" data-content="<?php _e("Hemtelefon", "elysium"); ?>">
				<span class="input__label-content--isao"><?php _e("Hemtelefon", "elysium"); ?></span>
			</label>
		</div>
		<div class="medlem-input">
			<input type="email" class="input__field--isao" name="elysium_epost" id="epost" placeholder="<?php _e("john.doe@gmail.com", "elysium"); ?>">
			<label for="epost" class="input__label--isao" data-content="<?php _e("E-post", "elysium"); ?>">
				<span class="input__label-content--isao"><?php _e("E-post", "elysium"); ?></span>
			</label>
		</div>
	</fieldset>
	<fieldset>
		<label for="elysium_dhr_medlem">
			<input type="checkbox" name="elysium_dhr_medlem" id="elysium_dhr_medlem">
			<span class="medlem-checkbox__label">Vill du automatiskt bli medlem i DHR?</span>
		</label>
	</fieldset>
	<fieldset>
		<input type="hidden" name="submitted" id="submitted" value="true" />
		<button type="submit" class="button--wayra" id="elysium_submit"><?php _e("Bli medlem nu", "elysium"); ?></button>
		<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
	</fieldset>
</form>
