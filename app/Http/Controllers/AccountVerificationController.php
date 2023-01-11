<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Acme\Services\AccountVerifications as Services;
use Acme\Common\Entity\AccountVerification as Entity;

use Acme\Common\DataResult as DataResult;
use Acme\Common\CommonFunction;

class AccountVerificationController extends Controller
{

    use CommonFunction;

    protected $services;

    public function __construct()
    {
        $this->services = new Services;
    }


    public function update(Request $request, $id)
    {
        $result = new DataResult;
        //
        try {
            $input = $request->all();
            $input['id'] = $id;
            $UserID = $input['user_id'];
            $Code = $input['code'];
            $entity = new Entity;
            $entity->SetData($input);
            $data = $entity->Serialize();

            $result->data = $this->services->getVerified($entity, $UserID, $Code, $id);
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }
}
