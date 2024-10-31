<?php
/**
 * @package SedoxPerformanceCatalogPlugin
 */

namespace SedoxVDb\Base;

use SedoxVDb\Traits\ApiConnection;

class Translations extends BaseController
{
    use ApiConnection;

    public function __construct()
    {
        parent::__construct();
        $this->initApi();
    }

    public function register() {
        add_action('plugins_loaded', [$this, 'loadTranslations']);
    }

    public function loadTranslations()
    {
        if($this->api->connected) {
            $this->api->getLanguages();

            load_plugin_textdomain(
                SEDOX_VDB_TEXT_DOMAIN,
                false,
                basename(dirname(__FILE__, 3)) . '/languages'
            );
        }
    }
}
