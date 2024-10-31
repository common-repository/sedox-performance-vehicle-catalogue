<?php
/**
 * @package SedoxPerformanceCatalogPlugin
 */

namespace SedoxVDb\Pages;


use SedoxVDb\Api\Callbacks\AdminCallbacks;
use SedoxVDb\Api\Callbacks\ManagerCallbacks;
use SedoxVDb\Api\SettingsApi;
use SedoxVDb\Base\BaseController;
use SedoxVDb\Base\Config;

class Admin extends BaseController
{
	private $settings;
	private $settingsTab1 = 'sedox_catalog_configuration';
	private $settingsTab2 = 'sedox_catalog_customization';
	private $callbacks;
	private $callbacksMngr;
	public $pages = [];
	public $subpages = [];

	public function __construct() {
		parent::__construct();
		$this->settings = new SettingsApi();
	}

	public function register()
	{
		$this->callbacks = new AdminCallbacks();
		$this->callbacksMngr = new ManagerCallbacks();

		$this->setPages();

		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings
			->addPages( $this->pages )
			->withSubPage('Settings')
			->addSubPages($this->subpages)
			->register();
	}

	public function setPages()
	{
		$this->pages[] = [
			'page_title' => 'Sedox VDb',
			'menu_title' => 'Sedox VDb',
			'capability' => 'manage_options',
			'menu_slug'  => SEDOX_VDB_MAIN_MENU_SLUG,
			'callback'   => [$this->callbacks, 'adminSettings'],
			'icon_url'   => 'dashicons-dashboard',
			'position'   => 110
		];
	}

	public function setSettings()
	{
		$args = [];
		foreach($this->options as $type => $options) {
			foreach($options as $option) {
				$args[] = [
					'option_group' => 'sedox_catalog_'.$type,
					'option_name' => SEDOX_VDB_MAIN_MENU_SLUG.'_'.$type,
					'callback' => [$this->callbacksMngr, $option['type'].'Sanitize']
				];
			}
		}

		$this->settings->setSettings($args);
	}

	public function setSections()
	{
		$args = [
			[
				'id' => 'sedox_section_configuration',
				//'title' => 'Configuration',
				'title' => '',
				'callback' => [$this->callbacksMngr, 'adminConfigurationManager'],
				'page' => $this->settingsTab1,
			],
			[
				'id' => 'sedox_section_customization',
				//'title' => 'Customization',
				'title' => '',
				'callback' => [$this->callbacksMngr, 'adminCustomizationManager'],
				'page' => $this->settingsTab2,
			]
		];

		$this->settings->setSections($args);
	}

	public function setFields()
	{
        $config = new Config();
        $adminOptions = $config->adminOptions();

		$args = [];
		foreach($adminOptions as $type =>$options) {
			foreach($options as $option) {
			    $elementClass = $option['elementClass'] ?? '';
			    $class = $option['type'] == 'checkbox'? ' ui-toggle ' : '';
			    if($option['type'] == 'hidden') {
			        $class .= ' hidden';
                }
				$field =  [
					'id' => $option['name'],
					'title' => $option['title'],
					'callback' => [$this->callbacksMngr, $option['type'].'Field'],
					'page' => $type == 'configuration'? $this->settingsTab1 : $this->settingsTab2,
					'section' => 'sedox_section_'.$type,
					'args' => [
						'optionName' => SEDOX_VDB_MAIN_MENU_SLUG.'_'.$type,
						'label_for' => $option['name'],
						'class' => $class,
						'elementClass' => $elementClass,
                        'placeholder' => $option['placeholder'] ?? '',
                        'hint' => $option['hint'] ?? '',
                        'title' => $option['title'] ?? '',
                        'extraInfo' => $option['extraInfo'] ?? '',
                        'tooltip' => $option['tooltip'] ?? '',
					],
				];

				if($option['type'] == 'select') {
					$field['args']['options'] = $option['options'];
				}

				$args[] = $field;
			}
		}

		$this->settings->setFields($args);
	}
}
