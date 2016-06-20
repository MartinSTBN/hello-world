<?php
/*
	Plugin Name: Plugin Test
	Plugin URI:  localhost/wp/index.php/about/
	Description: Still useless.
	Version:     1.0
	Author:      Martin Steenbergen
	Author URI:  localhost/wp/
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
?>



<?php

		//SHORTCODE
		function helloWorld($atts, $content = null)
		{
			$output = "";

			if (!empty($content))
			{
				$output .= "<h1>".$content."</h1>";
			}
			else
			{
				$output .= "<h1>Er is geen tekst tussen de shortcode tags gezet</h1>";
			}

			$hallo_world_atts = shortcode_atts(array(
				"firstname" => "Martin",
				"infix" => "",
				"lastname" => "Steenbergen",
			), $atts);

			$output .= "Dit is mijn eerste plugin met wordpress. Helemaal zelf gemaakt. <br>
		                   Mijn naam is ".$hallo_world_atts["firstname"]." ".$hallo_world_atts["infix"]." ".$hallo_world_atts["lastname"]."<br>
		                   <img class='alignnone size-medium wp-image-34' src='http://www.annasanimaties.com/files/annasplaatjes/bert-ernie/bert-ernie-440.bmp' />"."";
			return $output;

		}




		function helloWorldInit() {
			add_shortcode( "hello_world", "helloWorld" );
		}

	add_action( "init", "helloWorldInit" );

/*
		function halloWereld_Admin() {
			include( 'halloWereld_Admin.php' );
		}

		//NEW MENU ITEM
		function helloWorldMenu() {
			add_menu_page(
				"Hallo Wereld",
			    "HalloWereld",
	            "manage_options",
				"HalloWereld",
				"halloWereld_Admin" );
		}

	add_action( 'admin_menu', 'helloWorldMenu' );
*/
?>
