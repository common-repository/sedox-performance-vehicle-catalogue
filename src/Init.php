<?php
/**
 * @package SedoxPerformanceCatalogPlugin
 */

namespace SedoxVDb;

use SedoxVDb\Base\Enqueue;
use SedoxVDb\Base\SettingsLinks;
use SedoxVDb\Base\Translations;
use SedoxVDb\Controllers\DataController;
use SedoxVDb\Pages\Admin;

final class Init
{
	/**
	 * Loop through the classes, initialize them and
	 * call the register() method if it exists
	 */
	public function registerServices()
	{
		foreach ( self::getServices() as $class ) {
			$service = self::instantiate($class);
			if (method_exists($service, 'register')) {
				$service->register();
			}
		}
	}

	public function registerShortcodeAndWidget()
	{
		$controller = new DataController();
		add_shortcode( 'sedox-catalogue', [$controller, 'renderCatalogCode' ]);
		add_action( "wp_ajax_sedox_api", [$controller, 'getApiData']);
		add_action( "wp_ajax_nopriv_sedox_api", [$controller, 'getApiData']);
        add_action( 'wp_ajax_logo_get_image', [$controller, 'getLogoImage']);
        add_action( 'wp_ajax_clear_cache', [$controller, 'clearCache']);
        // add_filter( 'plugins_api', [UpdateService::class, 'pluginInfo'], 20, 3);
        // add_filter( 'site_transient_update_plugins', [UpdateService::class, 'pushUpdate']);
        // add_action( 'upgrader_process_complete', [UpdateService::class, 'afterUpdate'], 10, 2 );
	}

	/**
	 * Store all the classes inside an array
	 * @return array
	 */
	private function getServices() {
		return [
			Admin::class,
			Enqueue::class,
			SettingsLinks::class,
            Translations::class,
		];
	}

	/**
	 * Retrieves the logo image
	 */
	private function getLogoImage() {
        if(isset($_GET['id']) ){
            $image = wp_get_attachment_image( filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ), 'medium', false, array( 'id' => 'logo-preview-image' ) );
            $data = array(
                'image'    => $image,
            );
            wp_send_json_success( $data );
        } else {
            wp_send_json_error();
        }
	}

	/**
	 * @param class $class     class from the services array
	 *
	 * @return class instance  new instance of the class
	 */
	private static function instantiate($class) {
		return new $class();
	}
}
