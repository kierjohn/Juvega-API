<?php
namespace Acme\Common\Entity;

use Acme\Common\DataFields\Message as DataField;

class Message extends BaseEntity
{
    public $UserInfoId = 0;
    public $Message = "";
    public $Conversation_id = 0;
    public $Created_by = 0;
    public $Deleted_time = 0;



    public function Validate()
    {

    }

    public function SetData($input)
    {
        $this->Id = isset($input[Datafield::ID])?$input[Datafield::ID] : 0 ;
        $this->UserInfoId = isset($input[Datafield::USER_INFO_ID])?$input[Datafield::USER_INFO_ID] : 0 ;
        $this->Message = isset($input[Datafield::MESSAGE])?$input[Datafield::MESSAGE] : '' ;
        $this->ConversationId = isset($input[Datafield::CONVERSATION_ID])?$input[Datafield::CONVERSATION_ID] : 0 ;
        $this->Created_by = isset($input[Datafield::CREATED_BY])?$input[Datafield::CREATED_BY] : 0 ;
        $this->Deleted_time = isset($input[Datafield::DELETE_TIME])?$input[Datafield::DELETE_TIME] : 0 ;


    }
    public function Serialize()
    {
        $this->Validate();
        $data = array();

        $data[Datafield::ID] = $this->Id;
        $data[Datafield::USER_INFO_ID] = $this->UserInfoId;
        $data[Datafield::MESSAGE] = $this->Message;
        $data[Datafield::CONVERSATION_ID] = $this->ConversationId;
        $data[Datafield::CREATED_BY] = $this->Created_by;
        $data[Datafield::DELETE_TIME] = $this->Deleted_time;

        return $data;
    }
}


?>
