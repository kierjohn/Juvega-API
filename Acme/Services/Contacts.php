<?php

namespace Acme\Services;

use Acme\Repositories\Contacts as Repository;
use Acme\Repositories\Notifications as NotificationsRepository;
use Acme\Services\Conversations as ConversationServices;
use Acme\Services\Users as UsersServices;
use Acme\Services\UserInfos as UserInfoServices;

use Acme\Common\DataFields\Contact as DataField;
use Acme\Common\Entity\Contact as Entity;
use Acme\Common\Entity\Notification as NotificationEntity;
use Acme\Common\Constants as Constants;

class Contacts extends Services
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new Repository;
        $this->notification_repository = new NotificationsRepository;
        $this->conversation_services = new ConversationServices;
        $this->user_services = new UsersServices;
        $this->user_info_services = new UserInfoServices;
    }

    public function getByUserInfoId($UserInfoId)
    {
        $result = $this->repository->getByUserInfoId($UserInfoId);

        return $result;
    }

    public function getContacts($UserInfoId, $keyword)
    {

        if ($keyword) {
            $data = $this->repository->getContacts($UserInfoId, $keyword);
            $data = $data->map(function ($entity) {
                $sender = $entity->user_info_id;
                $receiver = $entity->contact_id;
                $conversation = $this->conversation_services->check($sender, $receiver);
                $conversation_id = 0;
                if ($conversation) {
                    $conversation_id = $conversation->id;
                }

                $item = [
                    "id" => $entity->id,
                    "status" => $entity->status,
                    "favorite" => $entity->favorite,
                    "contact_id" => $entity->contact_id,
                    "created_at" => $entity->created_at,
                    "name" => $entity->email,
                    "conversation_id" => $conversation_id
                ];

                return $item;
            });
        } else {
            $data = $this->repository->getByUserInfoId($UserInfoId);
            $data = $data->map(function ($entity) {
                $sender = $entity->user_info_id;
                $receiver = $entity->contact_id;
                $conversation = $this->conversation_services->check($sender, $receiver);
                $conversation_id = 0;
                if ($conversation) {
                    $conversation_id = $conversation->id;
                }

                $item = [
                    "id" => $entity->id,
                    "status" => $entity->status,
                    "favorite" => $entity->favorite,
                    "contact_id" => $entity->contact_id,
                    "created_at" => $entity->created_at,
                    "name" => $entity->contact_info->user->email,
                    "conversation_id" => $conversation_id
                ];

                return $item;
            });
        }
        return $data;
    }

    public function getPinnedID($UserInfoId)
    {
        $result = $this->repository->getPinnedID($UserInfoId);

        return $result;
    }

    public function getUnpinnedID($UserInfoId)
    {
        $result = $this->repository->getUnpinnedID($UserInfoId);

        return $result;
    }

    public function deleteByUserInfoId($UserInfoId)
    {
        $result = $this->repository->deleteByUserInfoId($UserInfoId);

        return $result;
    }

    public function search($keyword)
    {
        $data =  $this->repository->search($keyword);
        $data = $data->map(function ($entity) {
            $item = [
                "id" => $entity->id,
                "nickname" => $entity->name,
                "status" => $entity->status,
                "user_photo_id" => $entity->file_id,
                "name" => $entity->email,
            ];

            return $item;
        });

        return $data;
    }

    public function pin($id, $flag)
    {
        $result = $this->repository->pin($id, $flag);

        return $result;
    }

    public function getByIdWithDetails($id)
    {
        $result = $this->repository->getByIdWithDetails($id);

        return $result;
    }

    public function sendContactNotification($data)
    {
        $id = $data["contact_id"];
        $user_info_data = $this->user_info_services->getByID($id);


        $input["user_info_id"] = $data['user_info_id'];
        $input["icon"] = "";
        $input["title"] =  $user_info_data->user->email;
        $input["content"] = "";
        $input["type"] = "NEW CONTACT";
        $input["created_at"] = $user_info_data->created_at;
        $input["updated_at"] = $user_info_data->updated_at;

        $entity = new NotificationEntity;
        $entity->SetData($input);
        $data = $entity->Serialize();

        $result = $this->notification_repository->saveNotification($data);

        return $result;
    }
}
