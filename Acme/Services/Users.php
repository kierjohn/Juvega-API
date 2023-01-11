<?php
namespace Acme\Services;

use Acme\Repositories\Users as Repository;

use Acme\Common\DataFields\User as DataField;
use Acme\Common\Entity\User as Entity;
use Acme\Common\Constants as Constants;

class Users extends Services{

    protected $repository;

    public function __construct()
	{
        $this->repository = new Repository;
    }

    public function getByUsername($username){
        $result = $this->repository->getByUsername($username);

        return $result;
    }

    public function searchIdByUsername($row){
        $user_info_id = $this->repository->searchIdByUsername($row);

        return $user_info_id;
    }


}
