<?php namespace UltimatePushNotifications\admin\options\pages;

/**
 * Class: All Registered Devices List
 *
 * @package Options
 * @since 1.0.0
 * @author M.Tuhin <info@codesolz.net>
 */

if ( ! defined( 'CS_UPN_VERSION' ) ) {
	die();
}

use UltimatePushNotifications\lib\Util;
use UltimatePushNotifications\admin\builders\FormBuilder;
use UltimatePushNotifications\admin\builders\AdminPageBuilder;
use UltimatePushNotifications\admin\options\functions\RegisteredDevicesList;


class AllRegisteredDevices {

	/**
	 * Hold page generator class
	 *
	 * @var type
	 */
	private $Admin_Page_Generator;

	/**
	 * Form Generator
	 *
	 * @var type
	 */
	private $Form_Generator;


	public function __construct( AdminPageBuilder $AdminPageGenerator ) {
		$this->Admin_Page_Generator = $AdminPageGenerator;

		/*create obj form generator*/
		$this->Form_Generator = new FormBuilder();

	}

	/**
	 * Generate add new coin page
	 *
	 * @param type $args
	 * @return type
	 */
	public function generate_page( $args, $option ) {

		$page = isset( $_GET['page'] ) ? Util::check_evil_script( $_GET['page'] ) : '';
		if ( isset( $_GET['s'] ) && ! empty( $sfor = Util::cs_esc_html( $_GET['s'] ) ) ) {
			$args['well'] = sprintf(
				

				__( '%1$s Search results for : %2$s%3$s %4$s << Back to all%5$s', 'ultimate-push-notifications' ),
				"<p class='search-keyword'>",
				"<b> {$sfor} </b>",
				'</p>',
				'<a href="' . Util::cs_generate_admin_url( $page ) . '" class="button">',
				'</a>'
			);
		}

		$args['well'] = Util::upn_no_app_config_notification( $option ) .  $args['well'];


		\ob_start();
		$adCodeList = new RegisteredDevicesList( 'cs-upn-all-registered-devices' );
		$adCodeList->prepare_items();
		echo '<form id="plugins-filter" method="get"><input type="hidden" name="page" value="' . $page . '" />';
		$adCodeList->views();
		$adCodeList->search_box( __( 'Search', 'ultimate-push-notifications' ), '' );
		$adCodeList->display();
		echo '</form>';

		$html = \ob_get_clean();

		$args['content']      = $html;
		$swal_title           = '....';
		$update_hidden_fields = array();

		$hidden_fields = array_merge_recursive(
			array(
				'method'     => array(
					'id'    => 'method',
					'type'  => 'hidden',
					'value' => '....',
				),
				'swal_title' => array(
					'id'    => 'swal_title',
					'type'  => 'hidden',
					'value' => $swal_title,
				),
			),
			$update_hidden_fields
		);

		$args['hidden_fields'] = $this->Form_Generator->generate_hidden_fields( $hidden_fields );

		$args['body_class'] = 'no-bottom-margin';

		return $this->Admin_Page_Generator->generate_page( $args );
	}

}

