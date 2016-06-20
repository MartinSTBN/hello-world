<?php

function sandbox_example_theme_menu() {

	add_theme_page(
		'Sandbox Options',          // The title to be displayed on the corresponding page for this menu
		'Sandbox Theme',                  // The text to be displayed for this actual menu item
		'manage_options',            // Which type of users can see this menu
		'sandbox_theme_options',                  // The unique ID - that is, the slug - for this menu item
		'sandbox_theme_display'// The name of the function to call when rendering the menu for this page

	);

	add_menu_page(
		'Sandbox Theme',
		'Sandbox Theme',
		'manage_options',
		'sandbox_theme_menu',
		'sandbox_theme_display'
	);

	add_submenu_page(
		'sandbox_theme_menu',
		'Display Options',
		'Display Options',
		'manage_options',
		'sandbox_theme_display_options',
		'sandbox_theme_display'
	);

	add_submenu_page(
		'sandbox_theme_menu',
		'Create Post',
		'Create Post',
		'manage_options',
		'sandbox_theme_social_options',
		create_function( null, 'sandbox_theme_display( "social_options" );' )
	);

} // end sandbox_create_menu_page
add_action( 'admin_menu', 'sandbox_example_theme_menu' );

function sandbox_theme_display( $active_tab = '' ) {
	?>
	<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">

		<div id="icon-themes" class="icon32"></div>
		<h2>Sandbox Theme Options</h2>
		<?php settings_errors(); ?>

		<?php if ( isset( $_GET['tab'] ) ) {
			$active_tab = $_GET['tab'];
		}
		else if ( $active_tab == 'social_options' ) {
			$active_tab = 'social_options';
		}
		else {
			$active_tab = 'display_options';
		} // end if/else ?>

		<h2 class="nav-tab-wrapper">
			<a href="?page=sandbox_theme_options&tab=display_options" class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>">Display Options</a>
			<a href="?page=sandbox_theme_options&tab=social_options" class="nav-tab <?php echo $active_tab == 'social_options' ? 'nav-tab-active' : ''; ?>">Create Post</a>
		</h2>

		<form method="post" action="">
			<?php

			if ( $active_tab == 'display_options' ) {
				settings_fields( 'sandbox_theme_display_options' );
				do_settings_sections( 'sandbox_theme_display_options' );
				submit_button();
			}
			else {
				settings_fields( 'sandbox_theme_social_options' );
				do_settings_sections( 'sandbox_theme_social_options' );
			} // end if/else


			?>
		</form>

	</div><!-- /.wrap -->
	<?php
} // end sandbox_theme_display


/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */

/**
 * Initializes the theme options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */
add_action( 'admin_init', 'sandbox_initialize_social_options' );
function sandbox_initialize_social_options() {

	if ( false == get_option( 'sandbox_theme_social_options' ) ) {
		add_option( 'sandbox_theme_social_options' );
	}

	add_settings_section(
		'general_settings_section',
		'Create Post',
		'sandbox_social_options_callback',
		'sandbox_theme_social_options'
	);

	add_settings_field(
		'show_twitter',
		'New post',
		'sandbox_twitter_callback',
		'sandbox_theme_social_options',
		'general_settings_section'
	);


	register_setting(
		'sandbox_theme_social_options',
		'show_twitter'
	);
} // end sandbox_initialize_theme_options

add_action( 'admin_init', 'sandbox_initialize_theme_options' );
function sandbox_initialize_theme_options() {


	//Since we're creating our own custom group of options as opposed to adding fields into an existing set,
	// we need to make sure that our collection of options exists in the database.
	// To do this, we'll make a call to the get_option function.
	// If it returns false, then we'll add our new set of options using the add_option function.

	if ( false == get_option( 'sandbox_theme_display_options' ) ) {
		add_option( 'sandbox_theme_display_options' );
	}

	// First, we register a section. This is necessary since all future options must belong to one.
	add_settings_section(
		'general_settings_section',         // ID used to identify this section and with which to register options
		'Display Options',                  // Title to be displayed on the administration page
		'sandbox_general_options_callback', // Callback used to render the description of the section
		'sandbox_theme_display_options'                           // Page on which to add this section of options
	);

	// Next, we will introduce the fields for toggling the visibility of content elements.
	add_settings_field(
		'show_header',                      // ID used to identify the field throughout the theme
		'Header',                           // The label to the left of the option interface element
		'sandbox_toggle_header_callback',   // The name of the function responsible for rendering the option interface
		'sandbox_theme_display_options',                          // The page on which this option will be displayed
		'general_settings_section',         // The name of the section to which this field belongs
		array(                              // The array of arguments to pass to the callback. In this case, just a description.
			'Disable header'
		)
	);

	add_settings_field(
		'show_content',
		'Content',
		'sandbox_toggle_content_callback',
		'sandbox_theme_display_options',
		'general_settings_section',
		array(
			'Disable content'
		)
	);

	add_settings_field(
		'show_footer',
		'Footer',
		'sandbox_toggle_footer_callback',
		'sandbox_theme_display_options',
		'general_settings_section',
		array(
			'Disable footer'
		)
	);

	// Finally, we register the fields with WordPress
	register_setting(
		'sandbox_theme_display_options',
		'show_header'
	);

	register_setting(
		'sandbox_theme_display_options',
		'show_content'
	);

	register_setting(
		'sandbox_theme_display_options',
		'show_footer'
	);

} // end sandbox_initialize_theme_options

/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */

/**
 * This function provides a simple description for the General Options page.
 *
 * It is called from the 'sandbox_initialize_theme_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function sandbox_general_options_callback() {
	echo '<p>Select which areas of content you wish to display.</p>';
} // end sandbox_general_options_callback

/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */

/**
 * This function renders the interface elements for toggling the visibility of the header element.
 *
 * It accepts an array of arguments and expects the first element in the array to be the description
 * to be displayed next to the checkbox.
 */
function sandbox_toggle_header_callback( $args ) { ?>

	<!--Note the ID and the name attribute of the element match that of the ID in the call to add_settings_field-->
	<input type="checkbox" id="show_header" name="show_header" value="1" <?php checked( 1, get_option( 'show_header' ) ) ?> />

	<!--Here, we will take the first argument of the array and add it to a label next to the checkbox-->
	<label for="show_header"> <?php echo $args[0] ?></label>

<?php }

function sandbox_toggle_content_callback( $args ) { ?>

	<input type="checkbox" id="show_content" name="show_content" value="1" <?php checked( 1, get_option( 'show_content' ) ) ?> />
	<label for="show_content"> <?php echo $args[0] ?></label>

<?php }

function sandbox_toggle_footer_callback( $args ) { ?>

	<input type="checkbox" id="show_footer" name="show_footer" value="1" <?php checked( 1, get_option( 'show_footer' ) ) ?> />
	<label for="show_footer"> <?php echo $args[0] ?></label>

<?php }


function sandbox_social_options_callback() { ?>

<?php }

function sandbox_twitter_callback() { ?>

	<form>
		<button id="new_post" name="new_post">Create</button>
	</form>

	<?php


	$ran_int = random_int( 1, 100 );
	$ran_img_int = random_int( 0, 7 );
	$ran_bool = array( 'yes', 'no' );
	$bool = $ran_bool[ array_rand( $ran_bool ) ];
	$ran_bool_manage_stock = array( 'yes', 'no' );
	$bool_manage_stock = $ran_bool_manage_stock[ array_rand( $ran_bool_manage_stock ) ];

	$ran_bool_empty = array( 'yes', '' );
	$bool_empty = $ran_bool_empty[ array_rand( $ran_bool_empty ) ];

	$random_title = array( 'Jan', 'Klaas', 'Piet' );
	$title = $random_title[ array_rand( $random_title ) ];

	$SKU_id = "SKU_$ran_int";

	$random_stock_status = array( 'instock', 'outofstock' );
	$stock_status = $random_stock_status[ array_rand( $random_stock_status ) ];

	$random_backorders = array( 'yes', 'no', 'notify' );
	$backorders = $random_backorders[ array_rand( $random_backorders ) ];


	if ( isset( $_POST['new_post'] ) ) {
		$post_id = wp_insert_post( array(
			'post_title'   => $title . $ran_int,
			'post_type'    => 'product',
			'post_content' => 'random_product'
		) );


		$query_images_args = array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'post_status'    => 'inherit',
			'posts_per_page' => -1
		);

		$query_images = new WP_Query( $query_images_args );

		$images = array();
		foreach ( $query_images->posts as $image ) {
			$images[] = $image->ID;
		}

		$images[ $ran_img_int ];

		$taxonomy = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'fields'     => 'names',
			'number'     => random_int( 0, 5 ),
			'orderby'    => 'name',
			'order'      => 'DESC'

		);

		$terms = get_terms( $taxonomy );
		var_dump( $terms );
		shuffle( $terms );
		wp_set_object_terms( $post_id, $terms, 'product_cat' );
	}


	//GENERAL
	add_post_meta( $post_id, '_sku', $SKU_id );
	add_post_meta( $post_id, '_regular_price', $ran_int );
	add_post_meta( $post_id, '_sale_price', $ran_int );


	//INVENTORY
	add_post_meta( $post_id, '_manage_stock', $bool_manage_stock );
	add_post_meta( $post_id, '_stock_status', $stock_status );
	add_post_meta( $post_id, '_sold_individually', $bool_empty );
	add_post_meta( $post_id, '_backorders', $backorders );
	add_post_meta( $post_id, '_stock', $ran_int );


	//SHIPPING
	add_post_meta( $post_id, '_weight', $ran_int );
	add_post_meta( $post_id, '_length', $ran_int );
	add_post_meta( $post_id, '_width', $ran_int );
	add_post_meta( $post_id, '_height', $ran_int );

	//*
	add_post_meta( $post_id, '_price', $ran_int );
	add_post_meta( $post_id, '_thumbnail_id', $images[ $ran_img_int ] );

} ?>

<?php


?>



