<?php

namespace Acme\Repositories;

use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\ConversationUser as Model;
use Acme\Common\DataFields\ConversationUser as DataField;

use Acme\Common\Constants as Constants;
use Acme\Common\Pagination as Pagination;


class ConversationUsers extends Repository
{

    use Pagination;

    public function __construct()
    {
        $this->model = new Model;
    }

    public function getByConversationID($Conversation_id)
    {
        $result = $this->model
            ->with("user_info")
            ->where(DataField::CONVERSATION_ID, $Conversation_id)->get();

        return $result;
    }

    public function createConversationUser($entity)
    {
        $result = $this->model->create($entity);

        return $result;
    }

    public function incrementUnreadMessage($conversation_id, $user_info_id)
    {

        $result = Model::where(DataField::CONVERSATION_ID, $conversation_id)
            ->where(DataField::USER_INFO_ID, "<>", $user_info_id)
            ->update(['unread_message' => Model::raw('unread_message + 1')]);

        return $result;
    }


    public function updateUnreadMessage($conversation_id, $user_info_id)
    {

        $result = Model::where(DataField::CONVERSATION_ID, $conversation_id)
            ->where(DataField::USER_INFO_ID, "=", $user_info_id)
            ->update(['unread_message' => 0]);

        return $result;
    }

    public function getConversationByUserInfoId($user_info_id)
    {
        $result = $this->model
            ->where("user_info_id", $user_info_id)
            ->get('conversation_id');

        return $result;
    }
    public function deleteByUserInfoId($user_info_id)
    {
        $result = $this->model
            ->where("user_info_id", $user_info_id)
            ->delete();

        return $result;
    }

    public function deleteByConversationId($conversation_id)
    {
        $result = $this->model
            ->where("conversation_id", $conversation_id)
            ->delete();

        return $result;
    }

    public function check($sender, $receiver)
    {
        $result = $this->model
            ->where("user_info_id", $sender)
            ->get();

        return $result;
    }
}
