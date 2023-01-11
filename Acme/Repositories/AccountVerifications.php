<?php
namespace Acme\Repositories;

use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\AccountVerification as Model;
use Acme\Common\DataFields\AccountVerification as DataField;

use Acme\Common\Constants as Constants;
use Acme\Common\Pagination as Pagination;


class AccountVerifications extends Repository{

    use Pagination;

	public function __construct()
	{
		$this->model = new Model;
	}
}