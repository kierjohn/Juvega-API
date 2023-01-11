<?php

namespace Acme\Services;

use Acme\Repositories\ConversationUsers as Repository;
use Acme\Repositories\Conversations as ConversationRepository;

use Acme\Common\DataFields\Message as DataField;
use Acme\Common\Entity\Message as Entity;
use Acme\Common\Constants as Constants;
use GuzzleHttp\Promise\Each;

class ConversationUsers extends Services
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new Repository;
        $this->conversation_repository = new ConversationRepository;
    }

    public function getByConversationID($ConversationId)
    {
        $result = $this->repository->getByConversationID($ConversationId);

        return $result;
    }
    public function createConversationUser($entity, $Conversation_id, $username)
    {
        $result = $this->repository->createConversationUser($entity);
        $result = $this->conversation_repository->UpdateType($entity, $Conversation_id, $username);

        return $result;
    }

    public function updateUnreadMessage($conversation_id, $user_info_id)
    {
        $result = $this->conversation_user_services->updateUnreadMessage($conversation_id, $user_info_id);

        return $result;
    }

    public function check($sender, $receiver)
    {
        $result = $this->repository->check($sender, $receiver);
        return $result;
    }

    public function deleteByUserInfoId($user_info_id)
    {
        $conversations = $this->repository->getConversationByUserInfoId($user_info_id);

        $conversations->map(function ($conversation) {
            $conversation_id = $conversation->conversation_id;
            $this->conversation_repository->deleteById($conversation_id);
            $this->repository->deleteByConversationId($conversation_id);
        });

        return $conversations->count();
    }
}
