<?php

namespace Acme\Repositories;

use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\UserSetting as Model;


use Acme\Common\Pagination as Pagination;
use App\Models\UserSetting;
use Acme\Common\Constants as Constants;

class UserSettings extends Repository
{

    use Pagination;

    public function __construct()
    {
        $this->model = new Model;
    }

    public function status($id, $status)
    {
        $result = UserSetting::where(Constants::USER_INFO_ID, $id)->update(['status' => $status]);

        return $result;
    }

    public function deleteByUserInfoId($UserInfoId)
    {
        $result = null;
        $result = $this->model->where(Constants::USER_INFO_ID, $UserInfoId)->delete();

        return $result;
    }

    public function getByUserInfoId($UserInfoId)
    {
        $result = $this->model->where(Constants::USER_INFO_ID, $UserInfoId)->get();

        return $result;
    }

    public function getTimer($user_info_id)
    {
        $result = $this->model->where(Constants::USER_INFO_ID, $user_info_id)->pluck('delete_timer')->first();

        return $result;
    }

    public function updateUserSetting($request)
    {



        $update_details = array(
            'status' => $request['status'],
            'notification' => $request['notification'],
            'lock_screen' => ($request['lock_screen']),
            'alert_tone' => ($request['alert_tone']),
            'vibrate' => ($request['vibrate'])

        );

        $result = $this->model->where(Constants::USER_INFO_ID, $request['user_info_id'])
            ->update($update_details);


        return $result;
    }

    public function updateTimer($request)
    {


        $result = $this->model->where(Constants::USER_INFO_ID, $request['user_info_id'])
            ->update(['delete_timer' => $request['timer']]);


        return $result;
    }
}
