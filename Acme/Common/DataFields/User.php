<?php
namespace Acme\Common\DataFields;

class User extends BaseDataField
{
    const TABLE = 'users';

    const NAME = "name";
    const EMAIL = 'email';
    const EMAIL_VERIFIED_AT = 'email_verified_at';
    const PASSWORD = 'password';
    const REMEMBER_TOKEN = 'remember_token';
}

?>