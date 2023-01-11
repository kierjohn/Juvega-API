<?php
namespace Acme\Common\Entity;

use Acme\Common\DataFields\UserInfo as DataField;

class UserInfo extends BaseEntity
{
    public $UserID = 0;
    public $FirstName = "";
    public $LastName = "";
    public $UserType = 0;
    public $Status = 0;
    public $FileId = 0;
    public $DeviceToken = "";

    public function Validate()
    {

    }

    public function SetData($input)
    {
        $this->Id = isset($input[Datafield::ID])?$input[Datafield::ID] : 0 ;
        $this->UserID = isset($input[Datafield::USER_ID])?$input[Datafield::USER_ID] : 0 ;
        $this->FirstName = isset($input[Datafield::FIRST_NAME])?$input[Datafield::FIRST_NAME] : '' ;
        $this->LastName = isset($input[Datafield::LAST_NAME])?$input[Datafield::LAST_NAME] : '' ;
        $this->UserType = isset($input[Datafield::USER_TYPE])?$input[Datafield::USER_TYPE] : 0 ;
        $this->Status = isset($input[Datafield::STATUS])?$input[Datafield::STATUS] : 0 ;
        $this->FileId = isset($input[Datafield::FILE_ID])?$input[Datafield::FILE_ID] : 0 ;
        $this->DeviceToken = isset($input[Datafield::DEVICE_TOKEN])?$input[Datafield::DEVICE_TOKEN] : '' ;
    }

    public function Serialize()
    {
        $this->Validate();
        $data = array();

        $data[Datafield::ID] = $this->Id;
        $data[Datafield::USER_ID] = $this->UserID;
        $data[Datafield::FIRST_NAME] = $this->FirstName;
        $data[Datafield::LAST_NAME] = $this->LastName;
        $data[Datafield::USER_TYPE] = $this->UserType;
        $data[Datafield::STATUS] = $this->Status;
        $data[Datafield::FILE_ID] = $this->FileId;
        $data[Datafield::DEVICE_TOKEN] = $this->DeviceToken;

        return $data;
    }
}


?>
