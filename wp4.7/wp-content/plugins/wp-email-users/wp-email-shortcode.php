<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function weu_unsubscribe_user_scode( $atts ){

	$weu_arconf_buff = get_option( 'weu_ar_config_options' );

	$unubscribe_success = isset($weu_arconf_buff['rbtn_user_unsubscribe_success'])?$weu_arconf_buff['rbtn_user_unsubscribe_success']:'';

	$unubscribe_failure = isset($weu_arconf_buff['rbtn_user_unsubscribe_failure'])?$weu_arconf_buff['rbtn_user_unsubscribe_failure']:'';

	weu_setup_activation_data();

	global $wpdb;

	//INSERT INSERT CODE FOR UNSUBSCRIBED USERS

	$get_current_date = current_time( 'mysql' );

	$get_user_id = $_GET['id'];

	$get_email = $_GET['email'];

	$table_name_unsubscriber = $wpdb->prefix.'weu_unsubscriber';

	weu_setup_activation_data();

	/*INSERT DETAILS OF UNSUBSCRIBERS*/

	$rows_avail = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table_name_unsubscriber WHERE email =%s",$get_email ));

	if(!$rows_avail) {

	$results = $wpdb->query($wpdb->prepare( "INSERT INTO `".$table_name_unsubscriber."`(`uid`, `email`, `datetime`) VALUES (%d,%s,%s)",

	$get_user_id,$get_email,$get_current_date));

				if ($results == "false") {

					return $unubscribe_failure;

				} else {

					return $unubscribe_success;

				}

	} else {

		return "Already Usubscribed";

	}

}

add_shortcode( 'wp-email-users-unsubscribe', 'weu_unsubscribe_user_scode' );

?>