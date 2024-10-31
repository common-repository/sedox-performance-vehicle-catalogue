<?php
/**
 * @package SedoxPerformanceCatalogPlugin
 */

namespace SedoxVDb\Api;


class SettingsApi
{
	public $adminPages = [];
	public $adminSubpages = [];
	public $settings = [];
	public $sections = [];
	public $fields = [];

	public function register()
	{
		if ( ! empty($this->adminPages)) {
			add_action('admin_menu', [$this, 'addAdminMenu']);
		}

		if ( ! empty($this->settings)) {
			add_action('admin_init', [$this, 'registerCustomFields']);
		}
	}

	public function addPages(array $pages)
	{
		$this->adminPages = $pages;

		return $this;
	}

	public function withSubPage(string $title = null)
	{
		if (empty($this->adminPages)) {
			return $this;
		}

		$adminPage = $this->adminPages[0];

		$subpages = [
			[
				'parent_slug' => $adminPage['menu_slug'],
				'page_title' => $adminPage['page_title'],
				'menu_title' => $title ?? $adminPage['menu_title'],
				'capability' => $adminPage['capability'],
				'menu_slug'  => $adminPage['menu_slug'],
				'callback'   => $adminPage['callback'],
			]
		];

		$this->adminSubpages = $subpages;

		return $this;
	}

	public function addSubPages(array $pages)
	{
		$this->adminSubpages = array_merge($this->adminSubpages, $pages);

		return $this;
	}

	public function addAdminMenu()
	{
		foreach ( $this->adminPages as $page ) {
			add_menu_page(
				$page['page_title'],
				$page['menu_title'],
				$page['capability'],
				$page['menu_slug'],
				$page['callback'],
				$page['icon_url'],
				$page['position']
			);
		}

		foreach ( $this->adminSubpages as $page ) {
			add_submenu_page(
				$page['parent_slug'],
				$page['page_title'],
				$page['menu_title'],
				$page['capability'],
				$page['menu_slug'],
				$page['callback']
			);
		}
	}

	public function setSettings(array $settings)
	{
		$this->settings = $settings;

		return $this;
	}

	public function setSections(array $sections)
	{
		$this->sections = $sections;

		return $this;
	}

	public function setFields(array $fields)
	{
		$this->fields = $fields;

		return $this;
	}

	public function registerCustomFields()
	{
		// register setting
		foreach ( $this->settings as $setting ) {
			register_setting(
				$setting['option_group'],
				$setting['option_name'],
				$setting['callback'] ?? ''
			);
		}


		// add settings section
		foreach ( $this->sections as $section ) {
			add_settings_section(
				$section['id'],
				$section['title'],
				$section['callback'] ?? '',
				$section['page']
			);
		}

		// add settings field
		foreach ( $this->fields as $field ) {
			add_settings_field(
				$field['id'],
				$field['title'],
				$field['callback'] ?? '',
				$field['page'],
				$field['section'],
				$field['args'] ?? []
			);
		}
	}
}
