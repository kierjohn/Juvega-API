<?php

namespace Acme\Repositories;

use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Inbox as Model;
use Illuminate\Support\Facades\DB;
use Acme\Common\DataFields\Inbox as DataField;

use Acme\Common\Constants as Constants;
use Acme\Common\Pagination as Pagination;


class Inboxes extends Repository
{

    use Pagination;

    public function __construct()
    {
        $this->model = new Model;
    }

    public function contactUsList($keyword, $status)
    {
        $result =  DB::table("inboxes")
            ->join("user_infos", function ($join) {
                $join->on("inboxes.user_info_id", "=", "user_infos.id");
            })
            ->join("users", function ($join) {
                $join->on("user_infos.user_id", "=", "users.id");
            })
            ->where("email", "like", "%" . $keyword . "%")
            ->where("inboxes.status", "like", "%" . $status . "%")
            ->where("user_type", "=", "1")
            ->select("inboxes.id", "email", "title", "body", "inboxes.status", "inboxes.file_id", "inboxes.created_at")
            ->get();

        return $result;
    }

    public function read($id)
    {
        $result = $this->model->where(Constants::ID, $id)->update(['unread' => 0]);

        return $result;
    }
}
