<?php

namespace Acme\Repositories;

use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Message as Model;
use Acme\Common\DataFields\Message as DataField;

use Acme\Common\Constants as Constants;
use Acme\Common\Pagination as Pagination;


class Messages extends Repository
{

    use Pagination;

    public function __construct()
    {
        $this->model = new Model;
    }

    //get data with the given user_info_id
    public function getByUserInfoId($UserInfoId)
    {
        $result = $this->model->where(Constants::USER_INFO_ID, $UserInfoId)->get();

        return $result;
    }

    //remove recorder with the given user_info_id
    public function deleteByUserInfoId($UserInfoId)
    {
        $result = null;
        $result = $this->model->where(Constants::USER_INFO_ID, $UserInfoId)->delete();

        return $result;
    }

    public function deletebytimer($data)
    {
        return $this->model->where('deleted_time', '<=', now())->delete();
    }

    public function create($entity)
    {
        $result = $this->model->create($entity);
        return $result;
    }
}
