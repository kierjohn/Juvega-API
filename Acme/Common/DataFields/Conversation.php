<?php

namespace Acme\Common\DataFields;

class Conversation extends BaseDataField
{
    const TABLE = 'conversation';

    const NAME = 'name';
    const TYPE = "type";
    const MESSAGE_DELETE_TIMER = "message_delete_timer";
    const NOTIFICATION = "notification";
    const LOCK_SCREEN = "lock_screen";
    const ALERT_TONE = "alert_tone";
    const VIBRATE = "vibrate";
    const FILE_ID = "file_id";
    const DELETED_TIME = "deleted_time";
    const ROOM_SID = "room_sid";
}
