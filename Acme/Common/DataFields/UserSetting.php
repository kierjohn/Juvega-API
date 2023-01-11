<?php
namespace Acme\Common\DataFields;

class UserSetting extends BaseDataField
{
    const TABLE = 'user_settings';

    const USER_INFO_ID = "user_info_id";
    const STATUS = 'status';
    const NOTIFICATION = 'notification';
    const DELETE_TIMER = 'delete_timer';
    const LOCK_SCREEN = "lock_screen";
    const ALERT_TONE = "alert_tone";
    const VIBRATE = "vibrate";
    const TIMEZONE = "timezone";
}
