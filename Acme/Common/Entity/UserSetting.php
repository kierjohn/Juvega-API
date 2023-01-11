<?php

namespace Acme\Common\Entity;

use Acme\Common\DataFields\UserSetting as DataField;

class UserSetting extends BaseEntity
{
    public $UserInfoId = 0;
    public $Status = 0;
    public $Notification = 0;
    public $DeleteTimer = 0;
    public $LockScreen = 0;
    public $AlertTone = 0;
    public $Vibrate = 0;
    public $Timezone = "";

    public function Validate()
    {
    }

    public function SetData($input)
    {
        $this->Id = isset($input[Datafield::ID]) ? $input[Datafield::ID] : 0;
        $this->UserInfoId = isset($input[Datafield::USER_INFO_ID]) ? $input[Datafield::USER_INFO_ID] : 0;
        $this->Status = isset($input[Datafield::STATUS]) ? $input[Datafield::STATUS] : 0;
        $this->Notification = isset($input[Datafield::NOTIFICATION]) ? $input[Datafield::NOTIFICATION] : 0;
        $this->DeleteTimer = isset($input[Datafield::DELETE_TIMER]) ? $input[Datafield::DELETE_TIMER] : 0;
        $this->LockScreen = isset($input[Datafield::LOCK_SCREEN]) ? $input[Datafield::LOCK_SCREEN] : 0;
        $this->AlertTone = isset($input[Datafield::ALERT_TONE]) ? $input[Datafield::ALERT_TONE] : 0;
        $this->Vibrate = isset($input[Datafield::VIBRATE]) ? $input[Datafield::VIBRATE] : 0;
        $this->Timezone = isset($input[Datafield::TIMEZONE]) ? $input[Datafield::TIMEZONE] : "";
    }

    public function Serialize()
    {
        $this->Validate();
        $data = array();

        $data[Datafield::ID] = $this->Id;
        $data[Datafield::USER_INFO_ID] = $this->UserInfoId;
        $data[Datafield::STATUS] = $this->Status;
        $data[Datafield::NOTIFICATION] = $this->Notification;
        $data[Datafield::DELETE_TIMER] = $this->DeleteTimer;
        $data[Datafield::LOCK_SCREEN] = $this->LockScreen;
        $data[Datafield::ALERT_TONE] = $this->AlertTone;
        $data[Datafield::VIBRATE] = $this->Vibrate;
        $data[Datafield::TIMEZONE] = $this->Timezone;

        return $data;
    }
}
