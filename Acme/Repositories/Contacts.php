<?php

namespace Acme\Repositories;

use App\Models\Contact;
use App\Models\Contact as Model;

use Illuminate\Support\Facades\DB;
use Acme\Common\Constants as Constants;

use Acme\Common\DataFields\BaseDataField;
use Acme\Common\Pagination as Pagination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Acme\Common\DataFields\Contact as DataField;

class Contacts extends Repository
{

    use Pagination;

    public function __construct()
    {
        $this->model = new Model;
    }

    //get data with the given user_info_id
    public function getByUserInfoId($UserInfoId)
    {
        $result = $this->model
            ->with(['contact_info' => function ($query) {
                $query->with("user");
            }])
            ->where(Constants::USER_INFO_ID, $UserInfoId)->get();

        return $result;
    }

    public function getContacts($UserInfoId, $keyword)
    {
        $result = DB::table("contacts")
            ->join("user_infos", function ($join) {
                $join->on("contacts.contact_id", "=", "user_infos.id");
            })
            ->join("users", function ($join) {
                $join->on("user_infos.user_id", "=", "users.id");
            })
            ->where("contacts.user_info_id", "=", $UserInfoId)
            ->where("users.email", "like", '%' . $keyword . '%')
            ->get();

        return $result;
    }

    //get pinned contact with the given user_info_id
    public function getPinnedID($UserInfoId)
    {
        $result = $this->model
            ->with(['contact_info' => function ($query) {
                $query->with("user");
            }])
            ->where(Constants::USER_INFO_ID, $UserInfoId)
            ->where(DataField::FAVORITE, 1)
            ->get();

        return $result;
    }

    //get unpinned contact with the given user_info_id
    public function getUnpinnedID($UserInfoId)
    {
        $result = $this->model
            ->with(['contact_info' => function ($query) {
                $query->with("user");
            }])
            ->where(Constants::USER_INFO_ID, $UserInfoId)
            ->where(DataField::FAVORITE, 0)->get();

        return $result;
    }

    //remove recorder with the given user_info_id
    public function deleteByUserInfoId($UserInfoId)
    {
        $result = null;
        $result = $this->model->where(Constants::USER_INFO_ID, $UserInfoId)->delete();

        return $result;
    }

    public function search($keyword)
    {
        $result = DB::table("users")
            ->join("user_infos", function ($join) {
                $join->on("users.id", "=", "user_infos.user_id");
            })
            ->where("email", "like", "%" . $keyword . "%")
            ->get();

        return $result;
    }

    public function pin($id, $flag)
    {
        $result = $this->model->where(Constants::ID, $id)->update(['favorite' => $flag]);

        return $result;
    }

    public function getByIdWithDetails($id)
    {
        $result = $this->model->where(Constants::ID, $id)
            ->with("user_info.user")
            ->first();

        return $result;
    }
}
