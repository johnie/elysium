<?php
	$hasError = false;
	$forNameError = '';
	$efterNameError = '';
	$errorString = '';
	$errors = array();

	if (
		isset( $_POST['submitted'] ) &&
		isset( $_POST['post_nonce_field'] ) &&
		wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) &&
		\Personnummer::valid( $_POST['elysium_personnr'] ) &&
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

		if( isset( $_POST['elysium_personnr'] ) && \Personnummer::valid( $_POST['elysium_personnr'] ) ) {
			update_post_meta( $mid, '_elysium_personnr', elysium()->elysium_encrypt($_POST['elysium_personnr']) );
		}
		if( isset( $_POST['elysium_postnr'] ) ) {
			update_post_meta( $mid, '_elysium_postnr', esc_attr( $_POST['elysium_postnr'] ) );

			$json = file_get_contents('http://yourmoneyisnowmymoney.com/api/zipcodes/?zipcode='. preg_replace('/\s+/','', $_POST['elysium_postnr']) );
			$obj = json_decode($json);
			$address = $obj->results[0]->address;
			$lan = explode(', ', $address);

			update_post_meta( $mid, '_elysium_region', esc_attr( $lan[1] ) );
		}
		if( isset( $_POST['elysium_telefon'] ) ) {
			update_post_meta( $mid, '_elysium_telefon', esc_attr( $_POST['elysium_telefon'] ) );
		}
		if( isset( $_POST['elysium_epost'] ) && is_email( $_POST['elysium_epost'] ) ) {
			update_post_meta( $mid, '_elysium_epost', esc_attr( $_POST['elysium_epost'] ) );
		}
		elysium()->send_mail($_POST['fornamn'], $_POST['efternamn'], $_POST['elysium_epost']);
	}
?>

<?php
if(count($errors) > 0) {
  $errorString .= '<div class="alert-warning"><ul>';
  foreach($errors as $error){
		$errorString .= "<li>$error</li>";
	}
	$errorString .= '</ul></div>';

	echo $errorString;
}
?>

<form action="" id="elysium" class="medlem-form" method="POST">
	<fieldset>
		<legend><?php _e("Personnummer", "elysium"); ?> <span class="asterisk">*</span></legend>
		<div class="medlem-input">
			<input type="text" name="elysium_personnr" id="personnr" maxlength="11" required placeholder="<?php _e("Personnummer [ÅÅMMDD-1234]", "elysium"); ?>">
			<label for="personnr" data-content="<?php _e("Personnummer [ÅÅMMDD-1234]", "elysium"); ?>">
				<span class="input__label-content--isao"><?php _e("Personnummer [ÅÅMMDD-1234]", "elysium"); ?></span>
			</label>
		</div>
	</fieldset>
	<fieldset>
		<legend><?php _e("Namn", "elysium"); ?> <span class="asterisk">*</span></legend>
		<div class="medlem-input">
			<input type="text" name="fornamn" id="fornamn" required placeholder="<?php _e("Förnamn", "elysium"); ?>">
			<label for="fornamn" data-content="<?php _e("Förnamn", "elysium"); ?>">
				<span class="input__label-content--isao"><?php _e("Förnamn", "elysium"); ?></span>
			</label>
		</div>
		<?php if ( $forNameError != ''): ?>
			<span class="error"><?php echo $forNameError; ?></span>
		<?php endif; ?>
		<div class="medlem-input">
			<input type="text" name="efternamn" id="efternamn" required placeholder="<?php _e("Efternamn", "elysium"); ?>">
			<label for="efternamn" data-content="<?php _e("Efternamn", "elysium"); ?>">
				<span class="input__label-content--isao"><?php _e("Efternamn", "elysium"); ?></span>
			</label>
		</div>
		<?php if ( $efterNameError != ''): ?>
			<span class="error"><?php echo $efterNameError; ?></span>
		<?php endif; ?>
	</fieldset>
	<fieldset>
		<legend><?php _e("Kontakt", "elysium"); ?></legend>
		<div class="medlem-input">
			<input type="text" name="elysium_telefon" id="telefon" required placeholder="<?php _e("Telefon", "elysium"); ?>">
			<label for="telefon" data-content="<?php _e("telefon", "elysium"); ?>">
				<span class="input__label-content--isao"><?php _e("Telefon", "elysium"); ?></span>
			</label>
		</div>
		<div class="medlem-input">
			<input type="email" name="elysium_epost" id="epost" required placeholder="<?php _e("E-post", "elysium"); ?>">
			<label for="epost" data-content="<?php _e("E-post", "elysium"); ?>">
				<span class="input__label-content--isao"><?php _e("E-post", "elysium"); ?></span>
			</label>
		</div>
	</fieldset>
	<fieldset>
		<legend><?php _e("Adress", "elysium"); ?> <span class="asterisk">*</span></legend>
		<div class="medlem-input">
			<input type="text" name="elysium_postnr" id="postnr" required placeholder="<?php _e("Postnr", "elysium"); ?>">
			<label for="postnr" data-content="<?php _e("Postnr", "elysium"); ?>">
				<span class="input__label-content--isao"><?php _e("Postnr", "elysium"); ?></span>
			</label>
		</div>
	</fieldset>
	<fieldset>
		<input type="hidden" name="submitted" id="submitted" value="true" />
		<button type="submit" class="button--wayra" id="elysium_submit"><?php _e("Bli medlem nu", "elysium"); ?></button>
		<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
	</fieldset>
</form>
