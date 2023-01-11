<?php
namespace Acme\Common\DataFields;

class AccountVerification extends BaseDataField
{
    const TABLE = 'account_verification';

    const USER_ID = "user_id";
    const CODE = "code";
    const TYPE = "type";
    const IS_CONFIRM = "is_confirm";

}

?>