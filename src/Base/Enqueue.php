<?php
/**
 * @package SedoxPerformanceCatalogPlugin
 */

namespace SedoxVDb\Base;


class Enqueue extends BaseController {
	public function register() {
		add_action('admin_enqueue_scripts', [$this, 'enqueueAdmin']);
		add_action('wp_enqueue_scripts', [$this, 'enqueueFront'], 9999);
	}

	function enqueueAdmin($page) {

        if(strpos($page, 'sedox_vehicle_catalogue') !== false) {
            wp_enqueue_style(
                'sedox_catalog_style',
                $this->pluginUrl . 'assets/sedox_catalog_css_admin.css'
            );

            // change to the $page where you want to enqueue the script
            if ($page == 'toplevel_page_' . SEDOX_VDB_MAIN_MENU_SLUG) {
                // Enqueue WordPress media scripts
                wp_enqueue_media();
                // Enqueue color picker
                wp_enqueue_style('wp-color-picker');
            }

            wp_enqueue_style("wp-jquery-ui-tooltip");

            wp_enqueue_script(
                'sedox_catalog_script',
                $this->pluginUrl . 'assets/js/sedox_catalog_js_admin.js',
                ['wp-color-picker', 'jquery-ui-tooltip'],
                false,
                true
            );
        }
	}

	function enqueueFront() {
		wp_register_style(
			'sedox_fonts',
			'https://use.typekit.net/kck4ntk.css'
		);

		wp_register_style(
			'sedox_google_fonts',
			'https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700&display=swap&subset=cyrillic,cyrillic-ext,latin-ext'
		);

		wp_register_style(
			'sedox_catalog_style',
			$this->pluginUrl . 'assets/sedox_catalog_css_front.css'
		);

		wp_register_script(
			'sedox-catalogue-charts-script',
			"$this->pluginUrl/assets/Chartjs/Chart.min.js",
			[],
			false,
			true);

		wp_register_style(
			'sedox-catalogue-charts-style',
			"$this->pluginUrl/assets/Chartjs/Chart.min.css",
			[],
			false,
			true);

		wp_register_script(
			'sedox-catalogue-front-script',
			"$this->pluginUrl/assets/js/sedox_catalog_js_front.js",
			[],
			false,
			true
        );

        $customization = get_option( SEDOX_VDB_MAIN_MENU_SLUG . '_customization' );
        $filename = explode('|', $customization['language'])[1];
        $translations = file_get_contents(
            $this->pluginPath.'languages/'.SEDOX_VDB_TEXT_DOMAIN.'-'.$filename.'.json'
        );
        $translations = json_decode($translations);
        $params = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('mysedoxnonce'),
            'primaryColor' => esc_html($customization['primary_color']),
            'secondaryColor' => esc_html($customization['secondary_color']),
            'locale' => get_locale(),
            'translations' => $translations,
        );

        wp_add_inline_script(
            'sedox-catalogue-front-script',
            'const sc_ajax = ' . json_encode($params),
            'before'
        );
	}
}
