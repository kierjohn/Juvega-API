<?php

namespace Acme\Common\Entity;

use Acme\Common\DataFields\Inbox as DataField;

class Inbox extends BaseEntity
{
    public $UserInfoId = 0;
    public $Title = "";
    public $Body = "";
    public $Status = "";
    public $FileId = 0;
    public $Unread = 0;

    public function Validate()
    {
    }

    public function SetData($input)
    {
        $this->Id = isset($input[Datafield::ID]) ? $input[Datafield::ID] : 0;
        $this->UserInfoId = isset($input[Datafield::USER_INFO_ID]) ? $input[Datafield::USER_INFO_ID] : 0;
        $this->Title = isset($input[Datafield::TITLE]) ? $input[Datafield::TITLE] : '';
        $this->Body = isset($input[Datafield::BODY]) ? $input[Datafield::BODY] : '';
        $this->Status = isset($input[Datafield::STATUS]) ? $input[Datafield::STATUS] : '';
        $this->FileId = isset($input[Datafield::FILE_ID]) ? $input[Datafield::FILE_ID] : 0;
        $this->Unread = isset($input[Datafield::UNREAD]) ? $input[Datafield::UNREAD] : 0;
    }

    public function Serialize()
    {
        $this->Validate();
        $data = array();

        $data[Datafield::ID] = $this->Id;
        $data[Datafield::USER_INFO_ID] = $this->UserInfoId;
        $data[Datafield::TITLE] = $this->Title;
        $data[Datafield::BODY] = $this->Body;
        $data[Datafield::STATUS] = $this->Status;
        $data[Datafield::FILE_ID] = $this->FileId;
        $data[Datafield::UNREAD] = $this->Unread;

        return $data;
    }
}
