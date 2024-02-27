<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor form Engage action.
 *
 * Custom Elementor form action which adds user to Engage after form submission.
 *
 * @since 1.0.0
 */
class Engage_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base {

	/**
	 * Get action name.
	 *
	 * Retrieve Engage action name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string
	 */
	public function get_name() {
		return 'engage';
	}

	/**
	 * Get action label.
	 *
	 * Retrieve Engage action label.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string
	 */
	public function get_label() {
		return esc_html__( 'Engage', 'elementor-forms-engage-action' );
	}

	/**
	 * Register action controls.
	 *
	 * Add input fields to allow the user to customize the action settings.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param \Elementor\Widget_Base $widget
	 */
	public function register_settings_section( $widget ) {

		$widget->start_controls_section(
			'section_engage',
			[
				'label' => esc_html__( 'Engage', 'elementor-forms-engage-action' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$widget->add_control(
			'engage_list',
			[
				'label' => esc_html__( 'Engage List ID', 'elementor-forms-engage-action' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'The ID of the List you want to subscribe users to. This can be found in the "Settings" page of your List.', 'elementor-forms-engage-action' ),
			]
		);

		$widget->add_control(
			'engage_email_field',
			[
				'label' => esc_html__( 'Email Field ID', 'elementor-forms-engage-action' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'engage_firstname_field',
			[
				'label' => esc_html__( 'First Name Field ID', 'elementor-forms-engage-action' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'engage_lastname_field',
			[
				'label' => esc_html__( 'Last Name Field ID', 'elementor-forms-engage-action' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);
		$widget->add_control(
			'engage_number_field',
			[
				'label' => esc_html__( 'Number Field ID', 'elementor-forms-engage-action' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$widget->end_controls_section();

	}

	/**
	 * Run action.
	 *
	 * Runs the Engage action after form submission.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
	 */
	public function run( $record, $ajax_handler ) {

		$settings = $record->get( 'form_settings' );

		//  Make sure that there is a Engage list ID.
		if ( empty( $settings['engage_list'] ) ) {
			return;
		}

		// Get submitted form data.
		$raw_fields = $record->get( 'fields' );

		// Normalize form data.
		$data = [];
    $map = [
      'engage_firstname_field' => 'first_name',
      'engage_lastname_field' => 'last_name',
      'engage_number_field' => 'number',
      'engage_email_field' => 'email'
    ];
		foreach ( $raw_fields as $id => $field ) {
      $map_key = array_search($id, $settings);
      if ($map_key) {
        $data[$map[$map_key]] = $field['value'];
      } else {
        $data[$id] = $field['value'];
      }
		}

    $url = 'https://app.engage.so/embed/' . $settings['engage_list'];

		// Send the request
		wp_remote_post($url, [
				'body' => $data
			]
		);

	}

	/**
	 * On export.
	 *
	 * Clears Engage form settings/fields when exporting.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param array $element
	 */
	public function on_export( $element ) {

		unset(
			$element['engage_list'],
			$element['engage_firstname_field'],
			$element['engage_lastname_field'],
			$element['engage_number_field'],
			$element['engage_email_field']
		);

		return $element;

	}

}