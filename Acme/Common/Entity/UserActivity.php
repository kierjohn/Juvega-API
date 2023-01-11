<?php
namespace Acme\Common\Entity;

use Acme\Common\DataFields\UserActivity as DataField;

class UserActivity extends BaseEntity
{
    public $UserInfoId = 0;
    public $Status = 0;
    public $Activity = "";
    public $ModuleName = "";

    public function Validate()
    {

    }

    public function SetData($input)
    {
        $this->Id = isset($input[Datafield::ID])?$input[Datafield::ID] : 0 ;
        $this->UserInfoId = isset($input[Datafield::USER_INFO_ID])?$input[Datafield::USER_INFO_ID] : 0 ;
        $this->Status = isset($input[Datafield::STATUS])?$input[Datafield::STATUS] : 0 ;
        $this->Activity = isset($input[Datafield::ACTIVITY])?$input[Datafield::ACTIVITY] : '' ;
        $this->ModuleName = isset($input[Datafield::MODULE_NAME])?$input[Datafield::MODULE_NAME] : '' ;

    }

    public function Serialize()
    {
        $this->Validate();
        $data = array();

        $data[Datafield::ID] = $this->Id;
        $data[Datafield::USER_INFO_ID] = $this->UserInfoId;
        $data[Datafield::STATUS] = $this->Status;
        $data[Datafield::ACTIVITY] = $this->Activity;
        $data[Datafield::MODULE_NAME] = $this->ModuleName;

        return $data;
    }
}


?>
