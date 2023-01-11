<?php

namespace Acme\Services;

use Acme\Repositories\Inboxes as Repository;
use Acme\Services\Files as FilesServices;
use Acme\Services\UserInfos as UserInfosServices;
use Acme\Services\Notifications as NotificationsServices;
use Acme\Services\Users as UsersServices;

use Acme\Common\DataFields\Inbox as DataField;
use Acme\Common\Entity\Inbox as Entity;
use Acme\Common\Constants as Constants;
use Acme\Common\CommonFunction;

class Inboxes extends Services
{
    use CommonFunction;
    protected $repository;

    public function __construct()
    {
        $this->repository = new Repository;
        $this->file_services = new FilesServices;
        $this->user_info_services = new UserInfosServices;
        $this->notification_services = new NotificationsServices;
        $this->user_services = new UsersServices;
    }

    public function contactUsList($input)
    {

        $keyword = $input["username"];
        $status = $input["status"];
        $data = $this->repository->contactUsList($keyword, $status);
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
                "title" => $entity->title,
                "body" => $entity->body,
                "status_type" => $entity->status,
                "status" => $status,
                "attachment" => $file,
                "created_at" => $entity->created_at,

            ];

            return $item;
        });

        return $data;
    }

    public function create($input)
    {
        $input['unread'] = 1;
        $entity = new Entity;
        $entity->SetData($input);
        $data = $entity->Serialize();

        $result = $this->repository->create($data);

        //firebase notification

        $user_info_id = $input['user_info_id'];
        $user_info_data = $this->user_info_services->getByID($user_info_id);
        $username =  $user_info_data->user->email;
        $keyword = "";
        $usernames = $this->user_info_services->getAdminUsernames();
        $usernames =  $usernames->pluck('email');
        //checker
        if (count($usernames) > 1) {
            $user_info_ids = [];
            foreach ($usernames as $row) {
                $users = $this->user_services->searchIdByUsername($row);
                if ($users) {
                    $user_info_id = $users->info->id;
                    array_push($user_info_ids, $user_info_id);
                }
            }

            $devices = $this->user_info_services->getDeviceTokens($user_info_ids);
        } else {
            $user_info_ids = [];
            $users = $this->user_services->searchIdByUsername($usernames);
            array_push($user_info_ids, $users->info->id);
            $devices = $this->user_info_services->getDeviceTokens($user_info_ids);
        }

        //---------------------------------------------------------------
        $notification = [
            "title" => $username,
            "body" => $result->body
        ];
        $data = $result;
        $data["type"] = "NEW_TICKET";
        $this->SendNotification($devices, $notification,  $data);
        $this->notification_services->saveNotification($user_info_data,  $data);

        return $result;
    }
    public function read($id)
    {
        $result = $this->repository->read($id);

        return $result;
    }
}
