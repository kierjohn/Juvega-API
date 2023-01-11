<?php

namespace Acme\Services;

use Acme\Repositories\Conversations as Repository;
use Acme\Repositories\ConversationUsers as ConversationUserRepository;
use Acme\Services\Files as  FileServices;
use Acme\Services\UserInfos as UserInfoServices;

use Acme\Common\DataFields\Message as DataField;
use Acme\Common\Entity\Message as Entity;
use Acme\Common\Constants as Constants;

class Conversations extends Services
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new Repository;
        $this->conversation_user_repository = new ConversationUserRepository;
        $this->user_info_services = new UserInfoServices;
        $this->file_services = new FileServices;
    }

    public function getByUserInfoId($user_info_id)
    {
        $result = $this->repository->getByUserInfoId($user_info_id);

        return $result;
    }

    public function getGroupsByUserInfoId($user_info_id)
    {
        $result = $this->repository->getGroupsByUserInfoId($user_info_id);

        return $result;
    }

    public function detailedById($id)
    {
        $result = $this->repository->detailedById($id);

        return $result;
    }

    public function view($input)
    {
        $conversation_id = $input['conversation_id'];
        $user_info_id = $input['user_info_id'];
        $result = $this->repository->view($conversation_id, $user_info_id);
        $this->conversation_user_repository->updateUnreadMessage($conversation_id, $user_info_id);

        return $result;
    }

    public function check($sender, $receiver)
    {
        $result = $this->repository->check($sender, $receiver);
        return $result;
    }

    public function uploadPhoto($raw_file, $id)
    {
        $file_data = $this->file_services->SaveFileContent($raw_file);

        return $this->update(["file_id" => $file_data->id], $id);
    }

    public function deletebytimer($data)
    {

        $result = $this->repository->deletebytimer($data);

        return $result;
    }

    public function getUserIds($id)
    {
        $user_info_id = 0;

        $conversation_users = $this->conversation_user_repository->getByConversationID($id);

        $user_info_ids = $conversation_users->map(function ($row) {
            return $row->user_info_id;
        });

        $device_ids = [];
        foreach ($user_info_ids as $id) {
            if ($id != $user_info_id) {
                array_push($device_ids, $id);
            }
        }

        return $device_ids;
    }

    public function deleteById($id)
    {
        $data = $this->getByID($id);

        $conversation_users = $this->conversation_user_repository->getByConversationID($id);

        $user_info_ids = $conversation_users->map(function ($row) {
            return $row->user_info_id;
        });

        $device_ids = [];
        foreach ($user_info_ids as $id) {
            // if ($id != $user_info_id) {
            array_push($device_ids, $id);
            // }
        }

        $devices = $this->user_info_services->getDeviceTokens($device_ids);

        $notification = [
            "title" => $data->name,
            "body" => "Conversation has been deleted"
        ];

        $data["type"] = "DELETE_CONVERSATION";
        $this->SendNotification($devices, $notification,  $data);

        $this->destroy($id);

        return [];
    }

    public function  checkExist($id)
    {
        $result = $this->repository->checkExist($id);

        return $result;
    }
}
