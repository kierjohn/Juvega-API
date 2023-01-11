<?php
namespace Acme\Common\DataFields;

class UserInfo extends BaseDataField
{
    const TABLE = 'user_infos';

    const USER_ID = "user_id";
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const USER_TYPE = 'user_type';
    const STATUS = 'status';
    const FILE_ID = "file_id";
    const DEVICE_TOKEN = "device_token";
}

?>