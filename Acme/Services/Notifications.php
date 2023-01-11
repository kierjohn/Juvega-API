<?php

namespace Acme\Services;

use Acme\Repositories\Notifications as Repository;

use Acme\Common\DataFields\Notification as DataField;
use Acme\Common\Entity\Notification as Entity;
use Acme\Common\Constants as Constants;

class Notifications extends Services
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new Repository;
    }

    public function getByUserInfoId($user_info_id)
    {
        $result = $this->repository->getByUserInfoId($user_info_id);

        return $result;
    }

    public function deleteByUserInfoId($UserInfoId)
    {
        $result = $this->repository->deleteByUserInfoId($UserInfoId);

        return $result;
    }

    public function read($id)
    {
        $result = $this->repository->read($id);

        return $result;
    }

    public function saveNotification($user_info_data,  $data)
    {
        $input["user_info_id"] = $data->user_info_id;
        $input["title"] =  $user_info_data->user->email;
        $input["type"] = $data->type;
        $input["content"] = $this->encrypt($data->message);

        $entity = new Entity;
        $entity->SetData($input);
        $data = $entity->Serialize();

        $result = $this->repository->saveNotification($data);

        return $result;
    }
}
