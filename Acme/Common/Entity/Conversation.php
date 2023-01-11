<?php

namespace Acme\Common\Entity;

use Acme\Common\DataFields\Conversation as DataField;

class Conversation extends BaseEntity
{

    public $Name = "";
    public $Type = 0;
    public $MessageDeleteTimer = 0;
    public $Notification = 0;
    public $LockScreen = 0;
    public $AlertTone = 0;
    public $Vibrate = 0;
    public $FileId = 0;
    public $DeletedTime = 0;
    public $RoomSid = 0;

    public function Validate()
    {
    }

    public function SetData($input)
    {
        $this->Id = isset($input[Datafield::ID]) ? $input[Datafield::ID] : 0;
        $this->Name = isset($input[Datafield::NAME]) ? $input[Datafield::NAME] : "";
        $this->Type = isset($input[Datafield::TYPE]) ? $input[Datafield::TYPE] : 0;
        $this->MessageDeleteTimer = isset($input[Datafield::MESSAGE_DELETE_TIMER]) ? $input[Datafield::MESSAGE_DELETE_TIMER] : 0;
        $this->Notification = isset($input[Datafield::NOTIFICATION]) ? $input[Datafield::NOTIFICATION] : 0;
        $this->LockScreen = isset($input[Datafield::LOCK_SCREEN]) ? $input[Datafield::LOCK_SCREEN] : 0;
        $this->AlertTone = isset($input[Datafield::ALERT_TONE]) ? $input[Datafield::ALERT_TONE] : 0;
        $this->Vibrate = isset($input[Datafield::VIBRATE]) ? $input[Datafield::VIBRATE] : 0;
        $this->FileId = isset($input[Datafield::FILE_ID]) ? $input[Datafield::FILE_ID] : 0;
        $this->DeletedTime = isset($input[Datafield::DELETED_TIME]) ? $input[Datafield::DELETED_TIME] : 0;
        $this->RoomSid = isset($input[Datafield::ROOM_SID]) ? $input[Datafield::ROOM_SID] : 0;
    }
    public function Serialize()
    {
        $this->Validate();
        $data = array();

        $data[Datafield::ID] = $this->Id;
        $data[Datafield::NAME] = $this->Name;
        $data[Datafield::TYPE] = $this->Type;
        $data[Datafield::MESSAGE_DELETE_TIMER] = $this->MessageDeleteTimer;
        $data[Datafield::NOTIFICATION] = $this->Notification;
        $data[Datafield::LOCK_SCREEN] = $this->LockScreen;
        $data[Datafield::ALERT_TONE] = $this->AlertTone;
        $data[Datafield::VIBRATE] = $this->Vibrate;
        $data[Datafield::FILE_ID] = $this->FileId;
        $data[Datafield::DELETED_TIME] = $this->DeletedTime;
        $data[Datafield::ROOM_SID] = $this->RoomSid;


        return $data;
    }
}
