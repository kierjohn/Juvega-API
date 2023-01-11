<?php
namespace Acme\Common\Entity;

use Acme\Common\DataFields\ConversationUser as DataField;

class ConversationUser extends BaseEntity
{
    public $Conversation_id = 0;
    public $User_info_id = 0 ;
    public $Unread_message = 0 ;


    public function Validate()
    {

    }

    public function SetData($input)
    {
        $this->Id = isset($input[Datafield::ID])?$input[Datafield::ID] : 0 ;
        $this->Conversation_id = isset($input[Datafield::CONVERSATION_ID])?$input[Datafield::CONVERSATION_ID] : 0 ;
        $this->User_info_id = isset($input[Datafield::USER_INFO_ID])?$input[Datafield::USER_INFO_ID] : 0 ;
        $this->Unread_message = isset($input[Datafield::UNREAD_MESSAGE])?$input[Datafield::UNREAD_MESSAGE] : 0 ;

    }
    public function Serialize()
    {
        $this->Validate();
        $data = array();

        $data[Datafield::ID] = $this->Id;
        $data[Datafield::CONVERSATION_ID] = $this->Conversation_id;
        $data[Datafield::USER_INFO_ID] = $this->User_info_id;
        $data[Datafield::UNREAD_MESSAGE] = $this->Unread_message;

        return $data;
    }
}


?>
