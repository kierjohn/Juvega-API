<?php

namespace Acme\Services;

use Acme\Repositories\Messages as Repository;
use Acme\Repositories\Conversations as ConversationRepository;
use Acme\Repositories\ConversationUsers as ConversationUserRepository;
use Acme\Services\Users as UserServices;
use Acme\Services\UserInfos as UserInfoServices;
use Acme\Services\UserSettings as UserSettingsServices;
use Acme\Services\Conversations as ConversationServices;
use Acme\Services\Notifications as NotificationServices;

use Acme\Common\DataFields\Message as DataField;
use Acme\Common\Entity\Message as Entity;
use Acme\Common\Constants as Constants;
use Acme\Common\CommonFunction;


class Messages extends Services
{

    use CommonFunction;
    protected $repository;

    public function __construct()
    {
        $this->repository = new Repository;
        $this->conversation_repository = new ConversationRepository;
        $this->conversation_user_repository = new ConversationUserRepository;
        $this->user_services = new UserServices;
        $this->user_info_services = new UserInfoServices;
        $this->user_setting_services = new UserSettingsServices;
        $this->conversation_services = new ConversationServices;
        $this->notification_services = new NotificationServices;
    }

    public function getByUserInfoId($UserInfoId)
    {
        $result = $this->repository->getByUserInfoId($UserInfoId);

        return $result;
    }

    public function deleteByUserInfoId($UserInfoId)
    {
        $result = $this->repository->deleteByUserInfoId($UserInfoId);

        return $result;
    }

    public function deletebytimer($data)
    {

        //$conversation_result = $this->conversation_services->getTimer();
        //$message_delete_timer = $conversation_result-> pluck;
        $result = $this->repository->deletebytimer($data);

        return $result;
    }

    public function new($input, $entity, $conversation_entity, $conversation_user_entity)
    {   //message data
        $entity['created_by'] = $input['user_sender'];
        $entity['user_info_id'] = $input['user_sender'];
        $entity['message'] = $this->encrypt($input['message']);
        $id = $input['user_sender'];
        $user_info_data = $this->user_info_services->getByID($id);
        $user_info_id =  $user_info_data->id;
        $us_delete_timer = $this->user_setting_services->getTimer($user_info_id);
        $message_default_time = 300; //in seconds
        $entity["deleted_time"] = now()->addSeconds($message_default_time);

        //conversation data
        $conversation_entity["deleted_time"] = now()->addSeconds($us_delete_timer);
        $conversation_entity["message_delete_timer"] = $message_default_time;

        $usernames = $input['to'];
        $username =  $user_info_data->user->email;
        $conversation_entity['name'] = implode(",", $usernames) . "," . $username;

        //checker
        if (count($usernames) > 1) {
            $conversation_entity['type'] = "2"; //group chat
            $conversation = $this->conversation_repository->createConversation($conversation_entity);
            $conversation_id = $conversation->id;

            $user_info_ids = [];
            foreach ($usernames as $row) {
                $users = $this->user_services->searchIdByUsername($row);

                if ($users) {
                    $user_info_id = $users->info->id;
                    $conversation_user_entity['conversation_id'] = $conversation_id;
                    $conversation_user_entity['user_info_id'] = $user_info_id;
                    $this->conversation_user_repository->createConversationUser($conversation_user_entity);

                    array_push($user_info_ids, $user_info_id);
                }
            }
            $devices = $this->user_info_services->getDeviceTokens($user_info_ids);
            $conversation_user_entity['user_info_id'] = $entity['user_info_id'];
            $user_info_id = $entity['user_info_id'];
            $this->conversation_user_repository->createConversationUser($conversation_user_entity);
        } else {
            $conversation_entity['type'] = "1"; //single chat
            $sender = $input['user_sender'];
            $receiver_data = $this->user_services->searchIdByUsername($input['to']);
            $receiver = $receiver_data->id;
            $conversation_Data = $this->conversation_services->check($sender, $receiver);
            $user_info_id = $entity['user_info_id'];
            //check if conversation exist
            if ($conversation_Data) {
                $conversation_id = $conversation_Data->id;
            } else {

                $conversation = $this->conversation_repository->createConversation($conversation_entity);
                $conversation_id = $conversation->id;
                $conversation_user_entity['conversation_id'] = $conversation_id;
                $conversation_user_entity['user_info_id'] = $sender;
                $this->conversation_user_repository->createConversationUser($conversation_user_entity);
                $conversation_user_entity['user_info_id'] = $receiver;
                $this->conversation_user_repository->createConversationUser($conversation_user_entity);
            }
            $user_info_ids = [];
            $users = $this->user_services->searchIdByUsername($usernames);
            array_push($user_info_ids, $users->info->id);
            $devices = $this->user_info_services->getDeviceTokens($user_info_ids);
        }

        $this->conversation_user_repository->incrementUnreadMessage($conversation_id, $user_info_id);
        $entity['conversation_id'] = $conversation_id;
        $result =  $this->repository->create($entity);

        $notification = [
            "title" => $username,
            "body" => $result->message
        ];
        $result['message'] = $this->decrypt($result->message);

        $data = $result;
        $data["type"] = "NEW_MESSAGE";

        $this->SendNotification($devices, $notification,  $data);
        $this->notification_services->saveNotification($user_info_data,  $data);
        return $data;
    }

    public function send($entity)
    {
        $user_info_ids = [];
        $conversation_id = $entity['conversation_id'];
        $user_info_id = $entity['user_info_id'];
        $result = $this->repository->create($entity);
        $user_info_data = $this->user_info_services->getByID($user_info_id);

        $conversation_users = $this->conversation_user_repository->getByConversationID($conversation_id);

        $user_info_ids = $conversation_users->map(function ($row) {
            return $row->user_info_id;
        });

        $device_ids = [];
        foreach ($user_info_ids as $id) {
            if ($id != $user_info_id) {
                array_push($device_ids, $id);
            }
        }

        $devices = $this->user_info_services->getDeviceTokens($device_ids);

        $this->conversation_user_repository->incrementUnreadMessage($conversation_id, $user_info_id);

        $notification = [
            "title" => $user_info_data->user->email,
            "body" => $result->message
        ];

        $result['message'] = $this->decrypt($result->message);

        $data = $result;
        $data["type"] = "NEW_MESSAGE";
        $this->SendNotification($devices, $notification,  $data);
        $this->notification_services->saveNotification($user_info_data,  $data);

        return $result;
    }
}
