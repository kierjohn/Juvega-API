<?php

namespace Acme\Common\Entity;

use Acme\Common\DataFields\Notification as DataField;

class Notification extends BaseEntity
{
    public $UserInfoId = 0;
    public $Icon = "";
    public $Title = "";
    public $Content = "";
    public $Type = "";
    public $Flags = 0;

    public function Validate()
    {
    }

    public function SetData($input)
    {
        $this->Id = isset($input[Datafield::ID]) ? $input[Datafield::ID] : 0;
        $this->UserInfoId = isset($input[Datafield::USER_INFO_ID]) ? $input[Datafield::USER_INFO_ID] : 0;
        $this->Icon = isset($input[Datafield::ICON]) ? $input[Datafield::ICON] : "";
        $this->Title = isset($input[Datafield::TITLE]) ? $input[Datafield::TITLE] : '';
        $this->Content = isset($input[Datafield::CONTENT]) ? $input[Datafield::CONTENT] : '';
        $this->Type = isset($input[Datafield::TYPE]) ? $input[Datafield::TYPE] : '';
        $this->Flags = isset($input[Datafield::FLAGS]) ? $input[Datafield::FLAGS] : 0;
    }

    public function Serialize()
    {
        $this->Validate();
        $data = array();

        $data[Datafield::ID] = $this->Id;
        $data[Datafield::USER_INFO_ID] = $this->UserInfoId;
        $data[Datafield::ICON] = $this->Icon;
        $data[Datafield::TITLE] = $this->Title;
        $data[Datafield::CONTENT] = $this->Content;
        $data[Datafield::TYPE] = $this->Type;
        $data[Datafield::FLAGS] = $this->Flags;

        return $data;
    }
}
