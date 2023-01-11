<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Acme\Services\UserSettings as Services;
use Acme\Services\Users as UserServices;
use Acme\Common\DataFields\UserSetting as DataField;
use Acme\Common\Entity\UserSetting as Entity;
use Acme\Common\Entity\User as UserEntity;

use Acme\Common\DataResult as DataResult;
use Acme\Common\Constants as Constants;
use Acme\Common\CommonFunction;
use App\Models\User;

class UserSettingController extends BaseController
{

    use CommonFunction;

    protected $services;

    public function __construct()
    {
        $this->services = new Services;
        $this->user_services = new UserServices;
    }

    public function create(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();

            $entity = new Entity;
            $entity->SetData($input);
            $data = $entity->Serialize();

            $result->data = $this->services->create($data);
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function store(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();

            $entity = new Entity;

            $entity->SetData($input);
            $data = $entity->Serialize();

            $result->data = $this->services->save($data);
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function show($id)
    {
        $result = new DataResult;
        try {
            $entity = new Entity;

            $data = $this->services->getByID($id);

            $result->message = 'Success';
            $result->data = $data;
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }


    public function edit($id)
    {
        $result = new DataResult;
        try {
            $entity = new Entity;

            $data = $this->services->getByID($id);

            $result->message = 'Success';
            $result->data = $data;
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function updateUserSetting(Request $request)
    {
        $result = new DataResult;
        //
        try {
            $input = $request->all();
            $request['user_info_id'] = auth("api")->user()->info->id;
            if ($request['username'] <> '') {

                $user = User::where('email', '=', $input["username"])->first();
                if ($user != null) {
                    $result->message = "User Name Exist.";
                    $username = $input["username"];
                    $i = random_int(1, 100);
                    $j = random_int(1, 100);
                    $k = random_int(1, 100);
                    $l = random_int(1, 100);
                    while (User::whereemail($username)->exists()) {
                        $i++;
                        $username = [
                            $input["username"] . $i,
                            $input["username"] . $j,
                            $input["username"] . $k,
                            $input["username"] . $l
                        ];
                    }
                    $result->tags = [$username];
                    $result->error = true;
                    return response()->json($result, 200);
                }
            } else {
                $request['username'] = auth("api")->user()->email;
            }
            $result->data =  $this->services->updateUserSetting($request);
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function updateTimer(Request $request)
    {
        $result = new DataResult;
        //
        try {
            $input = $request->all();
            $request['user_info_id'] = auth("api")->user()->info->id;
            $result->data = $this->services->updateTimer($request);
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function status(Request $request)
    {
        $result = new DataResult;
        //
        try {
            $input = $request->all();
            $id = auth("api")->user()->info->id;
            $status = $input['status'];
            $result->data = $this->services->status($id, $status);
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function destroy($id)
    {

        $result = new DataResult;

        try {
            $result->data = $this->services->destroy($id);
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }
        return response()->json($result, 200);
    }

    public function deleteByUserInfoId($UserInfoId)
    {
        $result = new DataResult;
        try {
            $entity = new Entity;

            $data = $this->services->deleteByUserInfoID($UserInfoId);

            $result->message = 'Success';
            $result->data = $data;
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function delete(Request $request)
    {
        $result = new DataResult;

        try {

            $input = $request->all();
            $result->data = $this->services->delete($input[Constants::ID]);
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }


    public function list(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();
            $result->data = $this->services->getAll();
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function getByUserInfoID(Request $request, $UserInfoId)
    {
        $result = new DataResult;
        try {
            $entity = new Entity;

            $data = $this->services->getByUserInfoID($UserInfoId);

            $result->message = 'Success';
            $result->data = $data;
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }
}
