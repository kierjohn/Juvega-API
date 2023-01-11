<?php

namespace Acme\Repositories;

use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Notification as Model;
use Acme\Common\DataFields\Notification as DataField;

use Acme\Common\Constants as Constants;
use Acme\Common\Pagination as Pagination;
use App\Models\Notification;

class Notifications extends Repository
{

    use Pagination;

    public function __construct()
    {
        $this->model = new Model;
    }

    //get data with the given user_info_id
    public function getByUserInfoId($user_info_id)
    {
        $result = $this->model->where(Constants::USER_INFO_ID, $user_info_id)->get();

        return $result;
    }

    //remove recorder with the given user_info_id
    public function deleteByUserInfoId($UserInfoId)
    {
        $result = null;
        $result = $this->model->where(Constants::USER_INFO_ID, $UserInfoId)->delete();

        return $result;
    }

    public function read($id)
    {
        $result = Notification::where(Constants::ID, $id)->update(['flags' => 1]);

        return $result;
    }

    public function saveNotification($entity)
    {
        $result = $this->model->create($entity);
        return $result;
    }
}
