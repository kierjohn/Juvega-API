<?php

namespace Acme\Repositories;


use App\Models\Conversation;
use App\Models\ConversationUser;

use App\Models\Conversation as Model;
use Acme\Common\Constants as Constants;
use Acme\Common\Pagination as Pagination;

use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Acme\Common\DataFields\Conversation as DataField;
use Acme\Common\DataFields\ConversationUsers as ConversationUserDataField;
use App\Models\ConversationUser as ConversationUserModel;


class Conversations extends Repository
{

    use Pagination;

    public function __construct()
    {
        $this->model = new Model;
        $this->conversation_user_model = new ConversationUserModel;
    }

    public function getByUserInfoId($user_info_id)
    {

        $result = $this->model->whereHas('conversation_users', function ($q) use ($user_info_id) {
            $q->where("user_info_id", $user_info_id);
        })
            ->with(["message_info" => function ($q) {
                $q->latest()->first();
            }])
            ->with(["conversation_users" => function ($q) use ($user_info_id) {
                $q->with(["user_info.user" => function ($q2) {
                }])
                    ->with(["user_status" => function ($q2) {
                        $q2->latest();
                    }])
                    ->with(["user_setting" => function ($q2) {
                    }])
                    ->where("user_info_id", "<>", $user_info_id);
            }])
            ->with(["unread" => function ($q) use ($user_info_id) {
                $q->with(["user_info.user" => function ($q2) {
                }])->where("user_info_id", "=", $user_info_id);
            }])
            ->where("deleted_time", ">", now())
            ->where("type", 1)
            ->orderBy('created_at', 'desc')
            ->get();


        return $result;
    }

    public function getGroupsByUserInfoId($user_info_id)
    {

        $result = $this->model->whereHas('conversation_users', function ($q) use ($user_info_id) {
            $q->where("user_info_id", $user_info_id);
        })
            ->with(["message_info" => function ($q) {
                $q->latest()->first();
            }])
            ->with(["conversation_users" => function ($q) use ($user_info_id) {
                $q->with(["user_info.user" => function ($q2) {
                }])
                    ->with(["user_status" => function ($q2) {
                        $q2->latest();
                    }])
                    ->with(["user_setting" => function ($q2) {
                    }])
                    ->where("user_info_id", "<>", $user_info_id);
            }])
            ->with(["unread" => function ($q) use ($user_info_id) {
                $q->with(["user_info.user" => function ($q2) {
                }])->where("user_info_id", "=", $user_info_id);
            }])
            ->where("deleted_time", ">", now())
            ->where("type", 2)
            ->orderBy('created_at', 'desc')
            ->get();


        return $result;
    }

    public function detailedById($conversation_id)
    {

        $result = $this->model
            ->with(["conversation_users" => function ($q) {
                $q->with(["user_info.user" => function ($q2) {
                }])->with(["user_info.file" => function ($q2) {
                }]);
            }])
            ->where(DataField::ID, $conversation_id)
            ->first();

        return $result;
    }


    public function view($conversation_id)
    {

        $result = $this->model
            ->with(["message_info" => function ($q) {
                $q
                    ->with(["user_info.user" => function ($q2) {
                    }])->with(["user_info.file" => function ($q2) {
                    }])
                    ->latest();
            }])
            ->with(["conversation_users" => function ($q) {
                $q->with(["user_info.user" => function ($q2) {
                }])
                    ->with(["user_info.file" => function ($q2) {
                    }])
                    ->with(["user_status" => function ($q2) {
                        $q2->latest();
                    }])
                    ->with(["user_setting" => function ($q2) {
                    }]);
            }])
            ->where(DataField::ID, $conversation_id)
            ->first();

        return $result;
    }


    public function createConversation($entity)
    {
        $result = $this->model->create($entity);

        return $result;
    }

    public function UpdateType($entity, $conversation_id, $username)
    {

        $result = $this->model->where(Constants::ID, $conversation_id)
            ->update([
                'type' => 2, 'name' => model::raw("CONCAT(name, ',','" . $username . "')")
            ]);


        return $result;
    }

    public function updatePhoto($request)
    {


        $result = $this->model->where(Constants::USER_INFO_ID, $request['user_info_id'])
            ->update(['delete_timer' => $request['timer']]);


        return $result;
    }

    public function check($sender, $receiver)
    {
        $result = $this->model
            ->whereHas('conversation_users', function ($q) use ($sender) {
                $q->where("user_info_id", $sender);
            })
            ->whereHas('conversation_users', function ($q) use ($receiver) {
                $q->where("user_info_id", $receiver);
            })
            ->where("type", 1)
            ->where("deleted_time", ">", now())
            ->first();
        return $result;
    }

    public function getTimer($UserInfoId)
    {
        $result = $this->model->where(Constants::USER_INFO_ID, $UserInfoId)->pluck('message_delete_timer')->first();

        return $result;
    }

    public function deletebytimer($data)
    {
        return $this->model->where('deleted_time', '<=', now())->delete();
    }

    public function deleteById($id)
    {
        return $this->model->where(Constants::ID, $id)->delete();
    }

    public function checkExist($id)
    {
        $result = $this->model->where(Constants::ID, $id)
            ->where("deleted_time", ">", now())
            ->first();
        return $result;
    }
}
