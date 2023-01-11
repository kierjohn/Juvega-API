<?php

namespace Acme\Services;

use Acme\Repositories\UserActivities as Repository;

use Acme\Common\DataFields\UserActivity as DataField;
use Acme\Common\Entity\UserActivity as Entity;
use Acme\Common\Constants as Constants;

class UserActivities extends Services
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new Repository;
    }

    public function log($entity)
    {
        $this->repository->log($entity);
    }
    public function getLatestLogin($user_info_id)
    {
        $data = $this->repository->getLatestLogin($user_info_id);
        return  $data;
    }
}
