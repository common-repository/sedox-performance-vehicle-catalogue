<?php

namespace SedoxVDb\Base;

use SedoxVDb\Traits\ApiConnection;

class Config
{
    use ApiConnection;

    public static $optionsStatic = [
        'configuration' => [
            [
                'name' => 'api_key',
                'title' => 'Enter your API Key',
                'type' => 'apiKey',
                'default' => '',
                'hint' => 'E.g. 1234567890123'
            ],
            [
                'name' => 'languages',
                'title' => 'Languages',
                'type' => 'hidden',
                'default' => [],
            ],
            [
                'name' => 'debug',
                'title' => 'Show errors',
                'type' => 'checkbox',
                'default' => false,
                'extraInfo' => 'If on - show errors on the frontend (for debugging only)'
            ],
            [
                'name' => 'btnClearCache',
                'title' => 'Clear cache',
                'elementClass' => 'btn btn-danger',
                'type' => 'clearCacheButton',
                'default' => false,
                'tooltip' => 'Clear cached data and update from the database',
            ],
            [
                'name' => 'translations_timestamp',
                'title' => 'Translations timestamp',
                'type' => 'hidden',
                'default' => 0,
            ],
        ],
        'customization' => [
            [
                'name' => 'catalog_theme',
                'title' => 'Choose theme',
                'type' => 'select',
                'options' => [
                    'theme-light' => 'Light',
                    'theme-dark' => 'Dark',
                ],
                'default' => 'theme-light'
            ],
            [
                'name' => 'catalog_custom_css_class',
                'type' => 'text',
                'title' => 'Custom CSS class',
                'default' => '',
            ],
            [
                'name' => 'primary_color',
                'type' => 'color',
                'title' => 'Primary color',
                'default' => '#c30f0f',
                'placeholder' => '#c30f0f',
            ],
            [
                'name' => 'secondary_color',
                'type' => 'color',
                'title' => 'Secondary color',
                'default' => '#3c86d9',
                'placeholder' => '#3c86d9',
            ],
            [
                'name' => 'company_name',
                'type' => 'text',
                'title' => 'Company name',
                'default' => 'Sedox performance tuning',
            ],
            [
                'name' => 'show_vehicle_photos',
                'type' => 'checkbox',
                'title' => 'Show vehicle\'s photos',
                'default' => 1,
            ],
            [
                'name' => 'show_dynochart',
                'type' => 'checkbox',
                'title' => 'Show dynochart',
                'default' => 1,
            ],
            [
                'name' => 'use_unbranded_photos',
                'type' => 'checkbox',
                'title' => 'Use unbranded photos',
                'default' => 0,
            ],
            [
                'name' => 'company_logo',
                'type' => 'media',
                'title' => 'Replace logo',
                'default' => '',
            ],
            [
                'name' => 'default_torque_units',
                'type' => 'select',
                'title' => 'Default Torque units',
                'default' => 'nm',
                'options' => [
                    'nm' => 'Nm',
                    'ftlb' => 'ft-lb',
                ]
            ],
            [
                'name' => 'language',
                'type' => 'select',
                'title' => 'Language',
                'default' => 'en-GB',
                'options' => [],
                'extraInfo' => "Please make sure that you have the language installed! It doesn't ".
                    "need to be active, but it is mandatory to be on the list of installed ".
                    "WordPress languages. Navigate to Settings > General > Site Language and ".
                    "check if you have the language installed. If the language is not installed, ".
                    "please select it from the dropdown and click on \"Save Changes\"",
            ],
        ]
    ];

    public function __construct() {
        $this->initApi();
    }

    public function adminOptions()
    {
        $supporedLanguages = $this->api->getSupportedLanguages();

        self::$optionsStatic['customization'][10]['options'] = $supporedLanguages;

        return self::$optionsStatic;
    }
}
