
	<h2><?php _e('Registration Email', 'wpet'); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[from-name]"><?php _e('From Name', 'wpet'); ?></label></th>
				<td><input name="options[from-name]" type="text" id="options[from-name]" value="<?php esc_attr_e( $data['from-name'] ) ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[from-email]"><?php _e('From Email', 'wpet'); ?></label></th>
				<td><input name="options[from-email]" type="text" id="options[from-email]" value="<?php esc_attr_e( $data['from-email'] ) ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[subject]"><?php _e('Subject', 'wpet'); ?></label></th>
				<td><input name="options[subject]" type="text" id="options[subject]" value="<?php esc_attr_e( $data['subject'] ) ?>"></td>
			</tr>
			<tr class="form-required">
				<th scope="row"><label for="options[email-body]"><?php _e('Email Body', 'wpet'); ?></label></th>
				<td>
				<div class="postarea">
				<?php wp_editor( esc_attr( $data['email-body'] ), 'options[email-body]' ); ?>
				</div>
					<em><?php _e('Note: Include the shortcode [ticketlinks] in the email body to send the buyer the link to edit their ticket information.', 'wpet'); ?></em>
				</td>
			</tr>
		</tbody>
	</table>
