<?php

namespace Acme\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\UserActivity as Model;

use Acme\Common\Constants as Constants;
use Acme\Common\Pagination as Pagination;

use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Acme\Common\DataFields\UserActivity as DataField;

class UserActivities extends Repository
{
    use Pagination;

    public function __construct()
    {
        $this->model = new Model;
    }

    public function log($entity)
    {
        $this->model->create($entity);
    }

    public function getLatestLogin($user_info_id)
    {
        $result = $this->model->where("user_info_id", "=", $user_info_id)
            ->where("activity", "=", "LOGIN")
            ->orderBy('created_at', 'DESC')
            ->first();

        return $result;
    }
}
