<?php

namespace Acme\Services;

use Acme\Repositories\UserSettings as Repository;
use Acme\Repositories\Users as UserRepository;
use Acme\Services\User as UserServices;
use Acme\Services\UserInfos as UserInfosServices;
use Acme\Services\Files as FilesServices;

class UserSettings extends Services
{

    protected $repository;
    protected $user_info_services;

    public function __construct()
    {
        $this->repository = new Repository;
        $this->user_repository = new UserRepository;
    }

    public function status($id, $status)
    {

        $result = $this->repository->status($id, $status);

        return $result;
    }

    public function deleteByUserInfoId($UserInfoId)
    {
        $result = $this->repository->deleteByUserInfoId($UserInfoId);

        return $result;
    }

    public function getByUserInfoId($UserInfoId)
    {
        $result = $this->repository->getByUserInfoId($UserInfoId);

        return $result;
    }

    public function getTimer($user_info_id)
    {
        $result = $this->repository->getTimer($user_info_id);

        return $result;
    }

    public function updateUserSetting($request)
    {
        $result = $this->repository->updateUserSetting($request);
        $this->user_repository->updateUserSetting($request);
        if ($request->hasFile('user_photo')) {
            $user_info_services = new UserInfosServices;
            $file_services = new FilesServices;
            $id =  $request['user_info_id'];
            $user_info_data = $user_info_services->getByID($id);
            $file_services->delete($user_info_data->file_id);
            $raw_file = $request->file('user_photo');


            $photodata = $user_info_services->uploadPhoto($raw_file, $id);
        }

        return   $result;
    }

    public function updateTimer($request)
    {

        $result = $this->repository->updateTimer($request);

        return $result;
    }
}
