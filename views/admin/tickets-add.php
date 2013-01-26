<div class="wrap">
	<a href="http://9seeds.com/" target="_blank"><div id="seeds-icon"></div></a>
	<h2><?php _e('Add Ticket', 'wpet'); ?></h2>
<form method="post" action="">
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Ticket Name', 'wpet'); ?></th>
				<td><input name="" type="text" id="" value=""></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e('Ticket Options', 'wpet'); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Name', 'wpet'); ?></th>
				<td>
					<input type="checkbox" name="options[name]" value="1" checked="checked" disabled="disabled">
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Email', 'wpet'); ?></th>
				<td>
					<input type="checkbox" name="options[email]" value="1" checked="checked" disabled="disabled">
				</td>
			</tr>
<?php
// @TODO
// auto-fill checkboxes with all available ticket options
?>
		</tbody>
	</table>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Ticket', 'wpet'); ?>"></p>
</form>

</div>