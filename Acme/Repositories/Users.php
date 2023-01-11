<?php
namespace Acme\Repositories;

use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\User as Model;
use Acme\Common\DataFields\User as DataField;

use Acme\Common\Constants as Constants;
use Acme\Common\Pagination as Pagination;


class Users extends Repository{

    use Pagination;

	public function __construct()
	{
		$this->model = new Model;
	}

	public function getByUsername($username){
        $result = $this->model
				->with("info")
				->where(DataField::EMAIL, $username)->first();

        return $result;
    }

    public function searchIdByUsername($username){
        $result = $this->model
        ->with("info")
				->where(DataField::EMAIL, $username)->first();

        return $result;
    }

    public function updateUserSetting($request){

        $update_details= array(
            'email'=>$request['username'],
            'name'=>$request['display_name'],
            'password'=>hash('sha512',$request['passcode'])

        );

        $result = $this->model->where(Constants::ID,$request['user_info_id'])
        ->update($update_details);



        return $result;
     }


}
