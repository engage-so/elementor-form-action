<?php
/**
 * Plugin Name: Elementor Forms Engage Action
 * Description: Custom addon which adds a user to Engage after form submission.
 * Plugin URI:  https://github.com/engage-so/elementor-form-action
 * Version:     1.0.0
 * Author:      Opeyemi Obembe
 * Author URI:  https://engage.so/
 * Text Domain: elementor-forms-engage-action
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Add new subscriber to Engage.
 *
 * @since 1.0.0
 * @param ElementorPro\Modules\Forms\Registrars\Form_Actions_Registrar $form_actions_registrar
 * @return void
 */
function add_new_engage_form_action( $form_actions_registrar ) {

	include_once( __DIR__ .  '/form-actions/engage.php' );

	$form_actions_registrar->register( new Engage_Action_After_Submit() );

}
add_action( 'elementor_pro/forms/actions/register', 'add_new_engage_form_action' );