<?php
/*
	Plugin Name: Login Form
	Version: 1.0
	Author: Martin Steenbergen
	Description: Dit is een login form plugin.
*/

ob_start();

function login_form_shortcode() {
	global $wpdb;

	var_dump( $_POST );

	if ( isset( $_GET["id"] ) ) {
		if ( $_GET['action'] == "delete" ) {
			$wpdb->query(
				$wpdb->prepare( "DELETE FROM `wp_users` WHERE `id` = '%d'", $_GET["id"] )
			);
			wp_redirect( "http://localhost/WP/index.php/login-form/" );
		}
		elseif ( $_GET["action"] == "update" ) {
			echo "Update now:   ";
			$result = $wpdb->get_results(
				$wpdb->prepare( "SELECT * FROM `wp_users` WHERE `id` = '%d'", $_GET["id"] ), ARRAY_A
			);

			$record = array_shift( $result );


			$output = "";
			$output .= "<form action='http://localhost/WP/index.php/login-form?id=" . $_GET['id'] . "&action=write' method='post'>
                                <table>
                                    <tr>
                                        <td style='width:200px'>User Login:</td>
                                        <td><input type='text' name='user_login' value='" . $record["user_login"] . "' ></td>
                                    </tr>
                                    <tr>
                                        <td>User Nice Name:</td>
                                        <td><input type='text' name='user_nicename' value='" . $record["user_nicename"] . "' ></td>
                                    </tr>
                                    <tr>
                                        <td>User E-mail:</td>
                                        <td><input type='text' name='user_email' value='" . $record["user_email"] . "' ></td>
                                    </tr>
                                    <tr>
                                        <td>User Registered:</td>
                                        <td><input type='text' name='user_registered' value='" . $record["user_registered"] . "' ></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><input type='submit' name='updateSubmit' ></td>
                                    </tr>            
                                </table>       
                            </form>";
			echo $output;
		}
		elseif ( $_GET["action"] == "write" && isset( $_POST["updateSubmit"] ) ) {
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE `wp_users` SET `user_login` = '%s' WHERE `ID` = '%d'",
					$_POST['user_login'],
					$_GET['id']
				)
			);
			wp_redirect( "http://localhost/WP/index.php/login-form/" );
		}
	}

	// Straatnaam huisnummer stad postcode 06nummer

	if ( isset( $_POST["submit"] ) ) {
		date_default_timezone_set( "Europe/Amsterdam" );
		$date = date( "Y-m-d H:i:s" );
		echo $date;


		$wpdb->insert( "wp_users", array(
			"user_login"      => $_POST["firstname"],
			"user_registered" => $date,
			"user_email"      => $_POST["email"],
			"user_pass"       => md5( $_POST["password"] ),
			"display_name"    => $_POST["firstname"] . " " . $_POST['infix'] . " " . $_POST['lastname']
		) );

		$inserted_id = $wpdb->insert_id;

		$wpdb->insert( "user_gegevens", array(
			"id"         => $inserted_id,
			"straatnaam" => $_POST['adress'],
			"huisnummer" => $_POST['houseNumber'],
			"stad"       => $_POST['city'],
			"tel"        => $_POST['tel']

		) );

	}


	$result = $wpdb->get_results( "SELECT `ID`, `user_login`, `user_email` FROM `wp_users`", ARRAY_A );


	//var_dump($result);
	echo "<table>
                <tr>
                    <th style='width: 50px;'>ID</th>
                    <th>User Login</th>
                    <th style='width: 400px;'>User Email</th>
                    <th style='width: 50px;'></th>
                    <th style='width: 50px;'></th>
                </tr>";
	for ( $i = 0; $i < sizeof( $result ); $i++ ) {
		echo "<tr>
                       <td>" . $result[ $i ]["ID"] . "</td>
                       <td>" . $result[ $i ]["user_login"] . "</td>
                       <td>" . $result[ $i ]["user_email"] . "</td>
                       <td>
                           <a href='http://localhost/WP/index.php/login-form?id=" . $result[ $i ]["ID"] . "&action=delete'>
                               <img src='https://image.freepik.com/iconen-gratis/sluit-knop-met-een-kruis-in-een-cirkel_318-26587.png' alt='kruis'>
                           </a>
                       </td>
                       <td>
                           <a href='http://localhost/WP/index.php/login-form?id=" . $result[ $i ]["ID"] . "&action=update'>
                               <img src='http://img.freepik.com/vrije-vector/schrijftafeltje-potloodontwerp_1095-187.jpg?size=338&ext=jpg' alt='kruis'>
                           </a>
                       </td>              
                  </tr>";
	}
	echo "</table>";


	$output1 = "";
	$output1 .= "<form method='post' action='http://localhost/WP/index.php/login-form/'>
                        voornaam: <input type='text' name='firstname' >
                        tussenvoegsel: <input type='text' name='infix' >
                        achternaam: <input type='text' name='lastname'>
                        email: <input type='email' name='email' >
                        wachtwoord: <input type='password' name='password' >
                        straatnaam: <input type='text' name='adress'>
                        huisnummer: <input type='number' name='houseNumber'>
                        stad: <input type='text' name='city'>
                        tel: <input type='text' name='tel'>
                        <input type='submit' name='submit'></form>";

	return $output1;

}

function login_form_register_shortcode() {
	add_shortcode( 'login_form', 'login_form_shortcode' );
}

add_action( 'init', 'login_form_register_shortcode' );

?>