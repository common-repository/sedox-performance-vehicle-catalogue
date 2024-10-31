<?php
/**
 * @package SedoxPerformanceCatalogPlugin
 */

namespace SedoxVDb\Repositories;

use Exception;
use SedoxWPCatalogue\Dependencies\Analog\Analog;
use SedoxWPCatalogue\Dependencies\Analog\Handler\File;
use SedoxWPCatalogue\Dependencies\Analog\Handler\LevelBuffer;
use SedoxWPCatalogue\Dependencies\Analog\Logger;
use SedoxWPCatalogue\Dependencies\GuzzleHttp\Client as GuzzleCLient;
use SedoxVDb\Util\Util;
use SedoxWPCatalogue\Dependencies\Tuningfiles\Api\VehicleDatabaseApi;
use SedoxWPCatalogue\Dependencies\Tuningfiles\Configuration;

class DataRepository
{
    private const API_INSTANCE_CACHE_NAME = 'sedoxApiKeyValid';
    private const VEHICLE_TYPES = 'sedoxVehicleTypes';
    private const BRANDS_PREFIX = 'sedoxBrands';
    private const MODELS_PREFIX = 'sedoxModels';
    private const GENERATIONS_PREFIX = 'sedoxGenerations';
    private const GENERATION_DATA_PREFIX = 'sedoxGenerationData';
    private const ENGINES_PREFIX = 'sedoxEngines';
    private const ENGINE_DATA_PREFIX = 'sedoxEngineData';
    private const TRANSLATION_TIME_PREFIX = 'sedoxTranslations';
    private const CACHE_TIME_DATABASE = 3600 * 24;

    /**
     * @var VehicleDatabaseApi
     */
    public $apiInstance;

    /**
     * @var GuzzleCLient
     */
    public $rawApiInstance;

    public $error = '';

    private $settingsCustomization = null;

    /**
     * @var $logger Logger
     */
    private $logger;

    public $connected = false;

    public function __construct() {
        $this->setLogger();

        $this->connect();
        $this->settingsCustomization = get_option( SEDOX_VDB_MAIN_MENU_SLUG . '_customization' );
    }

    public function connect() : bool
    {
        $configuration = get_option( SEDOX_VDB_MAIN_MENU_SLUG . '_configuration' );
        if ( isset( $configuration['api_key'] ) && $configuration['api_key'] ) {
            $config = Configuration::getDefaultConfiguration()
                ->setApiKey( 'x-api-key', $configuration['api_key'] );

            try {
                $this->logger->warning('Instantiating API');
                $this->apiInstance = new VehicleDatabaseApi(
                    new GuzzleCLient(['timeout' => 6.0, 'connect_timeout' => 6.0]),
                    $config
                );

                $instanceStatus = get_transient(DataRepository::API_INSTANCE_CACHE_NAME);
                if ($instanceStatus === 'ok') {
                    $this->connectRaw();
                    $this->connected = true;
                    return true;
                }

                if ($instanceStatus === 'err') {
                    $this->connected = false;
                    $this->logger->error('API error');
                    $this->error = 'Error: API down';
                    return false;
                }

                $this->logger->info('Checking api key');

                $valid = $this->apiInstance->vdbSubscription()->valid();
                if($valid) {

                    $this->connectRaw();

                    $this->connected = true;
                    set_transient(DataRepository::API_INSTANCE_CACHE_NAME, 'ok', 3600 * 3);
                }
                return $valid;

            } catch (Exception $e) {
                set_transient(DataRepository::API_INSTANCE_CACHE_NAME, 'err', 30);
                $this->logger->error($e->getMessage(), $e->getTrace());
                $this->error = $e->getMessage();
                return false;
            }
        } else {
            $this->logger->error('API is not set.');
            $this->error = 'Set your API key';
        }

        return false;
    }

    private function connectRaw()
    {
        $configuration = get_option( SEDOX_VDB_MAIN_MENU_SLUG . '_configuration' );
        $config = Configuration::getDefaultConfiguration()
            ->setApiKey( 'x-api-key', $configuration['api_key'] );

        $this->rawApiInstance = new GuzzleCLient([
            'timeout' => 6,
            'connect_timeout' => 6,
            'base_uri' => $config->getHost(),
            'headers' => [
                'x-api-key' => $configuration['api_key']
            ],
        ]);
    }

    public function getVehicleTypes()
    {
        $configuration = get_option( SEDOX_VDB_MAIN_MENU_SLUG . '_configuration' );
        $debugOn = isset($configuration['debug']) && $configuration['debug'];
        if($this->connected) {
            try {
                $logMessage = 'Getting vehicle types';

                if(get_transient(self::VEHICLE_TYPES)) {
                    $logMessage .= ' from cache';
                    $vehicleTypes = get_transient(self::VEHICLE_TYPES);
                } else {
                    $logMessage .= ' from api';
                    $vehicleTypes = $this->apiInstance->vdbListTypes();
                    set_transient(self::VEHICLE_TYPES, $vehicleTypes, self::CACHE_TIME_DATABASE);
                }

                $this->logger->info($logMessage);

                return [
                    'vehicleTypes' => $vehicleTypes,
                ];
            } catch ( Exception $e ) {
                $this->logger->error($e->getMessage(), $e->getTrace());
                return [
                    'error' => [
                        'message' => $e->getMessage(),
                        'code' => $e->getCode()
                    ]
                ];
            }
        } else if ($debugOn) {
            return $this->error;
        }

        return [];
    }

    public function getManufacturers($vehicleType)
    {
        try {
            $logMessage = 'Getting manufacturers for vehicle type ' . $vehicleType;
            $manufacturers = [];

            if(get_transient(self::BRANDS_PREFIX.$vehicleType)) {
                $logMessage .= ' from cache';
                $manufacturers = get_transient(self::BRANDS_PREFIX.$vehicleType);
            } else {
                $logMessage .= ' from api';
                $response = $this->apiInstance->vdbListManufacturers( $vehicleType );
                foreach($response as $manufacturer) {
                    $manufacturers[] = [
                        'id' => $manufacturer->getId(),
                        'name' => $manufacturer->getName(),
                        'brandLogo' => $manufacturer->getBrandLogo(),
                        'slug' => $manufacturer->getSlug(),
                    ];
                }
                set_transient(self::BRANDS_PREFIX.$vehicleType, $manufacturers, self::CACHE_TIME_DATABASE);
            }

            $this->logger->info($logMessage);

            return $manufacturers;
        } catch ( Exception $e ) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return [];
        }
    }

    public function getModels($manufacturerId)
    {
        try {
            $logMessage = 'Getting models for manufacturer ' . $manufacturerId;
            $models = [];

            if(get_transient(self::MODELS_PREFIX.$manufacturerId)) {
                $logMessage .= ' from cache';
                $models = get_transient(self::MODELS_PREFIX.$manufacturerId);
            } else {
                $logMessage .= ' from api';
                $response = $this->apiInstance->vdbListModels( $manufacturerId );
                foreach($response as $model) {
                    $models[] = [
                        'id' => $model->getId(),
                        'name' => $model->getName(),
                        'modelPhoto' => $model->getModelPhoto(),
                        'slug' => $model->getSlug(),
                    ];
                }
                set_transient(self::MODELS_PREFIX.$manufacturerId, $models, self::CACHE_TIME_DATABASE);
            }

            $this->logger->info($logMessage);

            return $models;
        } catch ( Exception $e ) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return [];
        }
    }

    public function getGenerations($modelId)
    {
        try {
            $logMessage = 'Getting generations for model ' . $modelId;
            $generations = [];

            if(get_transient(self::GENERATIONS_PREFIX.$modelId)) {
                $logMessage .= ' from cache';
                $generations = get_transient(self::GENERATIONS_PREFIX.$modelId);
            } else {
                $logMessage .= ' from api';
                $response = $this->apiInstance->vdbListGenerations( $modelId );
                foreach($response as $generation) {
                    $name = $generation->getName() . ' (' . $generation->getYear() .
                        ($generation->getYearend() ? ' - ' . $generation->getYearend() : ' +') .
                        ')';

                    $generations[] = [
                        'id' => $generation->getId(),
                        'name' => $name,
                        'slug' => $generation->getSlug(),
                    ];
                }
                set_transient(self::GENERATIONS_PREFIX.$modelId, $generations, self::CACHE_TIME_DATABASE);
            }

            $this->logger->info($logMessage);

            return $generations;
        } catch ( Exception $e ) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return [];
        }
    }

    public function getGenerationData($generationId)
    {
        try {
            $logMessage = 'Getting generation data for ID ' . $generationId;

            if(get_transient(self::GENERATION_DATA_PREFIX.$generationId)) {
                $logMessage .= ' from cache';
                $data = get_transient(self::GENERATION_DATA_PREFIX.$generationId);
            } else {
                $logMessage .= ' from api';
                $response = $this->apiInstance->vdbViewGeneration( $generationId );

                // use unbranded photo if setting is turned on and available
                if(isset($this->settingsCustomization['use_unbranded_photos'])
                    && $this->settingsCustomization['use_unbranded_photos'] == 1
                ) {
                    $photo = $response->getPhotoUnbranded();
                } else {
                    $photo = $response->getPhoto();
                }

                if($photo == null) {
                    $photo = plugin_dir_url(dirname(__FILE__, 2)) . 'assets/images/nophoto.png';
                }

                $data = [
                    'id' => $response->getId(),
                    'name' => $response->getName(),
                    'slug' => $response->getSlug(),
                    'year' => $response->getYear(),
                    'yearEnd' => $response->getYearend(),
                    'photo' => $photo,
                ];

                set_transient(self::GENERATION_DATA_PREFIX.$generationId, $data, self::CACHE_TIME_DATABASE);
            }

            $this->logger->info($logMessage);

            return $data;
        } catch ( Exception $e ) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return [];
        }
    }

    public function getEngines($generationId)
    {
        try {
            $logMessage = 'Getting engines for generation ' . $generationId;
            $engines = [];

            if(get_transient(self::ENGINES_PREFIX.$generationId)) {
                $logMessage .= ' from cache';
                $engines = get_transient(self::ENGINES_PREFIX.$generationId);
            } else {
                $logMessage .= ' from api';
                $response = $this->apiInstance->vdbListEngines( $generationId );
                foreach($response as $engine) {
                    $engines[$engine->getFuelName()][] = [
                        'id' => $engine->getId(),
                        'name' => Util::engineNameWithKW($engine),
                        'slug' => $engine->getSlug(),
                    ];
                }
                set_transient(self::ENGINES_PREFIX.$generationId, $engines, self::CACHE_TIME_DATABASE);
            }

            $this->logger->info($logMessage);

            return $engines;
        } catch ( Exception $e ) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return [];
        }
    }

    public function getEngineData($engineId)
    {
        try {
            $logMessage = 'Getting engine data for engine ID ' . $engineId;

            if(get_transient(self::ENGINE_DATA_PREFIX.$engineId)) {
                $logMessage .= ' from cache';
                $data = get_transient(self::ENGINE_DATA_PREFIX.$engineId);
            } else {
                $logMessage .= ' from api';

                $response = $this->apiInstance->vdbViewEngine( $engineId );
                $data = [
                    'id' => $response->getId(),
                    'name' => Util::engineNameWithKW($response),
                    'slug' => $response->getSlug(),
                    'year' => $response->getYear(),
                    'type' => $response->getType(),
                    'cylinders' => $response->getCylinders(),
                    'capacity' => $response->getCapacity(),
                    'engineCode' => $response->getEngineCode(),
                    'ecu' => $response->getEcu(),
                    'modelName' => $response->getModelName(),
                    'fuelName' => $response->getFuelName(),
                    'options' => $response->getOptions(),
                    'hpValues' => $response->getHpValues(),
                    'nmValues' => $response->getNmValues(),
                    'rpmValues' => $response->getRpmValues(),
                    'powerHP' => $response->getPower(),
                    'powerKW' => Util::hp2kw($response->getPower()),
                    'torqueNm' => $response->getTorque(),
                    'torqueFtlb' => Util::nm2ftlb($response->getTorque()),
                    'remapStages' => $response->getRemapStages(),
                    'tcu' => $response->getTcu(),
                    'readMethods' => $response->getReadMethods(),
                    'readTools' => $response->getReadTools(),
                ];

                // change read methods response
                if($data['readMethods']) {
                    foreach($data['readMethods'] as $k => $method) {
                        $data['readMethods'][$k] = $method['value'];
                    }
                }

                // change read tools response
                if($data['readTools']) {
                    foreach($data['readTools'] as $k => $tool) {
                        $data['readTools'][$k] = $tool['name'];
                    }
                }

                foreach($data['remapStages'] as  $k => $stage) {
                    $powerKW = Util::hp2kw($stage->getPower());
                    $torqueFblb = Util::nm2ftlb($stage->getTorque());

                    $modifications = $stage->getModifications();
                    if($modifications) {
                        $modifications = strip_tags($modifications);
                    }

                    $data['remapStages'][$k] = [
                        'stage_no' => $stage->getStageNo(),
                        'powerHP' => $stage->getPower(),
                        'powerKW' => $powerKW,
                        'torqueNm' => $stage->getTorque(),
                        'torqueFtlb' => $torqueFblb,
                        'powerIncreaseKW' => $powerKW - $data['powerKW'],
                        'powerIncreaseHP' => $stage->getPower() - $data['powerHP'],
                        'torqueIncreaseNm' => $stage->getTorque() - $data['torqueNm'],
                        'torqueIncreaseFtlb' => $torqueFblb - $data['torqueFtlb'],
                        'hp_values' => $stage->getHpValues(),
                        'nm_values' => $stage->getNmValues(),
                        'modifications' => $modifications,
                    ];
                }

                set_transient(self::ENGINE_DATA_PREFIX.$engineId, $data, self::CACHE_TIME_DATABASE);
            }

            $this->logger->info($logMessage);

            return $data;
        } catch ( Exception $e ) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return [];
        }
    }

    public function getManufacturer($id)
    {
        try {
            $response = $this->apiInstance->vdbViewManufacturer($id);

            return [
                'id' => $response->getId(),
                'name' => $response->getName(),
                'brandLogo' => $response->getBrandLogo(),
                'slug' => $response->getSlug(),
            ];
        } catch ( Exception $e ) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return [];
        }
    }

    public function getModel($id)
    {
        try {
            $response = $this->apiInstance->vdbViewModel($id);

            return [
                'id' => $response->getId(),
                'name' => $response->getName(),
                'modelPhoto' => $response->getModelPhoto(),
                'slug' => $response->getSlug(),
                'year' => $response->getYear(),
            ];
        } catch ( Exception $e ) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return [];
        }
    }

    public function getLanguages()
    {
        if($this->connected) {
            $locale = Util::getPluginLocale();

            $translationsDir = dirname(__FILE__, 3) . '/languages';
            $translationFilenameMo = $translationsDir . '/'.SEDOX_VDB_TEXT_DOMAIN.'-' . $locale['wp_code'] . '.mo';
            $translationFilenamePo = $translationsDir . '/'.SEDOX_VDB_TEXT_DOMAIN.'-' . $locale['wp_code'] . '.po';
            $translationFilenameJson = $translationsDir . '/'.SEDOX_VDB_TEXT_DOMAIN.'-' . $locale['wp_code'] . '.json';

            $lastDownloadedTime = get_transient(self::TRANSLATION_TIME_PREFIX.$locale['code']);
            $threeDaysAgo = time() - (3600 * 24 * 3);
            if(!$lastDownloadedTime || $lastDownloadedTime < $threeDaysAgo || !file_exists($translationFilenameMo)) {
                $this->logger->info('Downloading languages');

                try {
                    $response = $this->rawApiInstance->get('/sapi/languages/mo/'.$locale['code']);
                    file_put_contents($translationFilenameMo, $response->getBody()->getContents());
                    $response = $this->rawApiInstance->get('/sapi/languages/po/'.$locale['code']);
                    file_put_contents($translationFilenamePo, $response->getBody()->getContents());
                    $response = $this->rawApiInstance->get('/sapi/languages/json/'.$locale['code']);
                    file_put_contents($translationFilenameJson, $response->getBody()->getContents());

                    set_transient(self::TRANSLATION_TIME_PREFIX.$locale['code'], time());

                    return true;
                } catch ( Exception $e ) {
                    $this->logger->error($e->getMessage(), $e->getTrace());
                    $this->error = $e->getMessage();

                    return [];
                }
            }

        }

        return false;
    }

    public function getSupportedLanguages()
    {
        if($this->connected) {
            $this->logger->info('Downloading supported languages');
            try {
                $response = $this->rawApiInstance->get('/sapi/languages/catalogue');

                if ($response->getStatusCode() === 200) {
                    $languages = json_decode($response->getBody()->getContents());

                    return array_reduce($languages, function ($carry, $language) {
                        $key = $language->code . '|' . $language->wp_code;
                        $carry[$key] = $language->name;

                        return $carry;
                    }, []);
                }

                $this->logger->error("Error fetching languages, status: {$response->getStatusCode()}");
                return [];

            } catch ( Exception $e ) {
                $this->logger->error($e->getMessage(), $e->getTrace());
                $this->error = $e->getMessage();

                return [];
            }
        }

        return false;
    }

    private function setLogger()
    {
        $logfile = dirname(__FILE__, 3) . '/sedox-vehicle-catalogue.log';
        $this->logger = new Logger;
        $this->logger->handler(Analog::handler(LevelBuffer::init(
            File::init($logfile),
            Analog::ERROR
        )));
    }
}
