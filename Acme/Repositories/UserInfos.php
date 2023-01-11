<?php

namespace Acme\Repositories;

use App\Models\UserInfo as Model;
use Illuminate\Support\Facades\DB;

use Acme\Common\Constants as Constants;
use Acme\Common\Pagination as Pagination;

use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Acme\Common\DataFields\UserInfo as DataField;

class UserInfos extends Repository
{
    use Pagination;

    public function __construct()
    {
        $this->model = new Model;
    }

    public function getDeviceTokens($user_info_ids)
    {
        $list = $this->model
            ->whereNotNull('device_token')
            ->whereIn(DataField::ID, $user_info_ids)
            ->get();

        $result = $list->map(function ($item) {
            return $item->device_token;
        });

        return $result;
    }

    public function getDeviceTokensWithId($user_info_ids)
    {
        $result = $this->model
            ->whereNotNull('device_token')
            ->whereIn(DataField::ID, $user_info_ids)
            ->get();

        return $result;
    }

    public function update($entity, $id)
    {
        $result = $this->model->where(DataField::USER_ID, $id)->update($entity);

        return $result;
    }

    public function updateFileId($file_data, $id)
    {
        $result = $this->model->where(CONSTANTS::ID, $id)->update(["file_id" => $file_data->id]);

        return $result;
    }

    public function adminList($keyword)
    {
        $result = DB::table("users")
            ->join("user_infos", function ($join) {
                $join->on("users.id", "=", "user_infos.user_id");
            })
            ->where("email", "like", "%" . $keyword . "%")
            ->where("user_type", "<>", "1")
            ->get();

        return $result;
    }

    public function getAdminUsernames()
    {
        $result = DB::table("users")
            ->join("user_infos", function ($join) {
                $join->on("users.id", "=", "user_infos.user_id");
            })
            ->where("user_type", "<>", "1")
            ->get('email');

        return $result;
    }

    public function userList($keyword)
    {
        $result = DB::table("users")
            ->join("user_infos", function ($join) {
                $join->on("users.id", "=", "user_infos.user_id");
            })
            ->where("email", "like", "%" . $keyword . "%")
            ->where("user_type", "=", "1")
            ->get();

        return $result;
    }

    public function action($user_info_id, $status)
    {
        $result = $this->model->where(CONSTANTS::ID, $user_info_id)->update(["status" => $status]);

        return $result;
    }
}
