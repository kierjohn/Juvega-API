<?php
namespace Acme\Repositories;

use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Files as Model;
use Acme\Common\DataFields\File as DataField;

use Acme\Common\Constants as Constants;
use Acme\Common\Pagination as Pagination;


class Files extends Repository{

    use Pagination;

	public function __construct()
	{
		$this->model = new Model;
	}

    public function getByUserInfoId($UserInfoId){
        $result = $this->model
            ->with(['contact_info' => function ($query) {
                $query->with("user");
            }])
            ->where(Constants::USER_INFO_ID, $UserInfoId)->get();

        return $result;
    }

}
