<?php
namespace Acme\Services;

use Acme\Repositories\AccountVerifications as Repository;

use Acme\Common\DataFields\AccountVerification as DataField;
use Acme\Common\Entity\AccountVerification as Entity;
use Acme\Common\Constants as Constants;

class AccountVerifications extends Services{

    protected $repository;

    public function __construct()
	{
        $this->repository = new Repository;
    }
    
}
