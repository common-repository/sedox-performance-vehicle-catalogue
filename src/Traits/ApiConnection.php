<?php
namespace SedoxVDb\Traits;

use SedoxVDb\Repositories\DataRepository;

trait ApiConnection
{
    /**
     * @var DataRepository $api
     */
    protected $api;

    protected function initApi()
    {
        $this->api = new DataRepository();
    }
}
