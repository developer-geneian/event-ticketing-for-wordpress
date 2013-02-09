<h1>Manual Payment</h1>
<div id="eventTicketing">
<form action="" method="post">
	<p><?php _e( 'Please enter a name and email address for your confirmation and tickets', 'wpet' ); ?></p>
	<ul class="ticketPurchaseInfo">
		<li>
			<label for="name"><?php _e( 'Name', 'wpet' ); ?>:</label>
			<input name="name" id="email" size="35" value="">
		</li>
		<li>
			<label for="email"><?php _e( 'Email', 'wpet' ); ?>:</label>
			<input name="email" id="email" size="35" value="">
		</li>
	</ul>
	 	<p>
			<input type="submit" name="submit" value="Submit" />
	 	</p>
</form>
</div>