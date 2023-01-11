<?php

namespace Acme\Services;

use Acme\Repositories\UserInfos as Repository;
use Acme\Repositories\UserActivities as UserActivitiesRepository;

use Acme\Common\DataFields\UserInfo as DataField;
use Acme\Common\Entity\UserInfo as Entity;

use Acme\Common\Constants as Constants;

use Acme\Services\Users as UserService;
use Acme\Common\Entity\User as UserEntity;

use Acme\Common\Entity\AccountVerification as AccountVerificationEntity;
use Acme\Services\AccountVerifications as AccountVerificationService;

use Acme\Common\Entity\UserSetting as UserSettingEntity;
use Acme\Services\UserSettings as UserSettingService;
use Acme\Services\Files as  FileServices;
use Acme\Services\UserActivities as UserActivitiesServices;

use Acme\Common\CommonFunction;

use Illuminate\Support\Str;

class UserInfos extends Services
{

    use CommonFunction;

    protected $repository;
    protected $user_service;
    protected $account_verification;

    public function __construct()
    {
        $this->repository = new Repository;
        $this->user_activities_repository = new UserActivitiesRepository;
        $this->user_service = new UserService;
        $this->account_verification_service = new AccountVerificationService;
        $this->user_setting_service = new UserSettingService;
        $this->file_services = new FileServices;
        $this->user_activities_services = new UserActivitiesServices;
        $this->file_services->bucketName = "users";
    }

    public function createWithCredentials($input)
    {

        $input["email"] = $input["username"];
        $user_entity = new UserEntity;
        $user_entity->SetData($input);
        $user_data = $user_entity->Serialize();

        $user_result = $this->user_service->create($user_data);

        $input["user_id"] = $user_result->id;
        $entity = new Entity;
        $entity->SetData($input);
        $data = $entity->Serialize();

        $result = $this->create($data);

        $av_input["user_id"] = $result->id;
        $av_input["code"] = str::random(6);
        $av_input["type"] = 1;
        $av_input["is_confirm"] = 0;

        $av_entity = new AccountVerificationEntity;
        $av_entity->SetData($av_input);
        $av_data = $av_entity->Serialize();

        $av_result = $this->account_verification_service->create($av_data);

        $us_input["user_info_id"] = $result->id;
        $us_input["status"] = $user_result->status;
        $us_input["notification"] = 0;
        $us_input["delete_timer"] = 3600; //1 hour
        $us_input["lock_screen"] = 0;
        $us_input["alert_tone"] = 0;
        $us_input["vibrate"] = 0;

        $us_entity = new UserSettingEntity;
        $us_entity->SetData($us_input);
        $us_data = $us_entity->Serialize();

        $us_result = $this->user_setting_service->create($us_data);

        return [
            "id" =>  $us_input["user_info_id"],
            "username" => $input["username"]
        ];
    }

    public function uploadPhoto($raw_file, $id)
    {
        $file_data = $this->file_services->SaveFileContent($raw_file);

        return  $this->repository->updateFileId($file_data, $id);
    }

    public function getDeviceTokens($user_info_ids)
    {
        $result = $this->repository->getDeviceTokens($user_info_ids);

        return $result;
    }

    public function getDeviceTokensWithId($user_info_ids)
    {
        $result = $this->repository->getDeviceTokensWithId($user_info_ids);

        return $result;
    }

    public function adminList($keyword)
    {

        $data =  $this->repository->adminList($keyword);
        $data = $data->map(function ($entity) {
            $ua_data =  $this->user_activities_services->getLatestLogin($entity->id);
            $file =  $this->file_services->getByID($entity->file_id);
            //login checker
            if (!$ua_data) {
                $latest_login = "";
            } else {
                $latest_login = date('Y-m-d h-m-i', strtotime($ua_data["created_at"]));
            }
            //file_id checker
            if (!$file) {
                $file = url('/') . "/images/dashboard_profile_default.png";
            } else {
                $file = ($file['url'] . "/uploads/" . $file['bucket'] . "/" . $file['id'] . "." . $file['extension']);
            }
            //user_type checker
            switch ($entity->user_type) {
                case '1':
                    $role = "User";
                    break;
                case '2':
                    $role = "Admin";
                    break;
                case '3':
                    $role = "Content";
                    break;
                case '4':
                    $role = "Reporting";
                    break;

                default:
                    $role = "";
                    break;
            }

            $item = [
                "id" => $entity->id,
                "username" => $entity->email,
                "user_photo_id" => $file,
                "created_at" => $entity->created_at,
                "latest_login" => $latest_login,
                "role_type" => $entity->user_type,
                "role" => $role
            ];

            return $item;
        });

        return $data;
    }

    public function userList($keyword)
    {
        $data =  $this->repository->userList($keyword);
        $data = $data->map(function ($entity) {
            $file =  $this->file_services->getByID($entity->file_id);
            //file_id checker
            if (!$file) {
                $file = url('/') . "/images/dashboard_profile_default.png";
            } else {
                $file = ($file['url'] . "/uploads/" . $file['bucket'] . "/" . $file['id'] . "." . $file['extension']);
            }

            switch ($entity->status) {
                case 1:
                    $status = 'Reactivate';
                    break;
                case 2:
                    $status = 'Inactive';
                    break;
                case 3:
                    $status = 'Suspend';
                    break;

                case 4:
                    $status = 'Ban';
                    break;

                default:
                    $status = '';
                    break;
            }

            $item = [
                "id" => $entity->id,
                "username" => $entity->email,
                "status_type" => $entity->status,
                "status" => $status,
                "user_photo_id" => $file,
                "created_at" => $entity->created_at,

            ];

            return $item;
        });

        return $data;
    }

    public function action($input)
    {
        $user_info_id = $input['user_info_id'];
        $status = ($input['action']);
        $data =  $this->repository->action($user_info_id, $status);
        return $data;
    }

    public function getAdminUsernames()
    {
        $result = $this->repository->getAdminUsernames();
        return $result;
    }
}
