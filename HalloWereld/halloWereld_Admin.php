
<?php
	if($_POST['hw_hidden'] == 'updated') {
		//Form data sent
		$dbtextFirst = $_POST['hw_dbtextFirst'];
		update_option('hw_dbtextFirst', $dbtextFirst);


		$dbtextInfix = $_POST['hw_dbtextInfix'];
		update_option('hw_dbtextInfix', $dbtextInfix);

		$dbtextLast = $_POST['hw_dbtextLast'];
		update_option('hw_dbtextLast', $dbtextLast);
		?>

		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
		<?php
	} else {
		//Normal page display
		$dbtextFirst = get_option('hw_dbtextFirst');
		$dbtextInfix= get_option('hw_dbtextInfix');
		$dbtextLast = get_option('hw_dbtextLast');
}

?>



<div class="wrap">
	<?php echo "<h1>" . __('Hallo Wereld', 'hw_trdrom') . "</h1>"; ?>




			<form name="hw_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

				<input type="hidden" name="hw_hidden" value="updated">

				<p><?php _e("Firstname")?> <input type="text" name="hw_dbtextFirst" value="<?php echo $dbtextFirst ?>"</p>
				<p><?php _e("Infix")?> <input type="text" name="hw_dbtextInfix" value="<?php echo $dbtextInfix ?>"</p>
				<p><?php _e("Lastname")?> <input type="text" name="hw_dbtextLast" value="<?php echo $dbtextLast ?>"</p>

				<p class="submit">
					<input type="submit" name="Submit" value="<?php _e('Save Changes', 'hw_trdrom' ) ?>" />
				</p>
			</form>
 
</div>
