<?php
/**
 * @package SedoxPerformanceCatalogPlugin
 */

namespace SedoxVDb\Base;


use SedoxVDb\Traits\ApiConnection;
use SedoxWPCatalogue\Dependencies\Analog\Analog;
use SedoxWPCatalogue\Dependencies\Analog\Handler\File;
use SedoxWPCatalogue\Dependencies\Analog\Handler\LevelBuffer;
use SedoxWPCatalogue\Dependencies\Analog\Logger;

class BaseController
{
    use ApiConnection;

	public $pluginPath;
	public $pluginUrl;
	public $pluginName;

	public $options = [];

	/**
     * @var $logger Logger
     */
	protected $logger;

	public function __construct() {
	    $this->setLogger();
		$this->pluginPath = plugin_dir_path(dirname(__FILE__, 2));
		$this->pluginUrl = plugin_dir_url(dirname(__FILE__, 2));
		$this->pluginName = plugin_basename(dirname(__FILE__, 3)) . '/sedox-catalogue.php';

		$this->options = Config::$optionsStatic;
	}


    private function setLogger()
    {
        $logfile = dirname(__FILE__, 3) . '/sedox-vehicle-catalogue.log';
        $this->logger = new Logger;
        $this->logger->handler(Analog::handler(LevelBuffer::init (
            File::init($logfile),
            Analog::ERROR
        )));
    }
}
