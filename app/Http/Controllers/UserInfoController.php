<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Acme\Common\CommonFunction;

use Acme\Common\Constants as Constants;
use Acme\Services\Files as FileServices;
use Acme\Services\UserInfos as Services;
use Acme\Services\Users as UserServices;
use Acme\Services\Inboxes as InboxServices;
use Acme\Common\DataResult as DataResult;
use Acme\Common\Entity\UserInfo as Entity;
use Acme\Services\UserSettings as SettingServices;

class UserInfoController extends BaseController
{

    use CommonFunction;

    protected $services;

    public function __construct()
    {
        $this->services = new Services;
        $this->setting_services = new SettingServices;
        $this->user_services = new UserServices;
        $this->inboxes_services = new InboxServices;
        $this->file_services = new FileServices;
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

    public function createWithCredentials(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();

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

            $result->tags = [];
            $data = $this->services->createWithCredentials($input);

            if ($request->hasFile('user_photo')) {
                $id =  $data['id'];

                $raw_file = $request->file('user_photo');

                $this->services->uploadPhoto($raw_file, $id);
            }
            /*else {
                $max_size = ini_get("upload_max_filesize");
                $result->error = true;
                $result->message = "The file uploaded exceeds the Maximum file size of {$max_size}B, Please select another file";
            }
            */

            $result->data = $data;
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function usernameSuggestions(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();

            $username = $input["username"];
            $usernames = $this->getSuggestions($username);

            $result->data = ["suggestions" => $usernames];
            $result->error = true;

            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function checker(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();

            $user = User::where('email', '=', $input["username"])->first();
            if ($user != null) {

                $username = $input["username"];
                $usernames = $this->getSuggestions($username);

                $result->message = "Unavailable";
                $result->data = false;
                $result->tags = $usernames;
                $result->error = true;

                return response()->json($result, 200);
            }

            $result->tags = [];
            $result->message = 'Available';
            $result->data = true;
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    private function getSuggestions($name)
    {
        $j = random_int(1, 100);
        $k = random_int(1, 100);
        $l = random_int(1, 100);
        $i = random_int(1, 100);

        $usernames = [
            $name . $i,
            $name . $j,
            $name . $k,
            $name . $l
        ];

        return $usernames;
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
        //
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

    public function update(Request $request, $id)
    {
        $result = new DataResult;
        //
        try {
            $input = $request->all();

            $entity = new Entity;
            $entity->SetData($input);
            $data = $entity->Serialize();

            $result->data = $this->services->update($data, $id);
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

    public function withSettings($id)
    {
        $result = new DataResult;

        try {
            $id =  auth("api")->user()->info->id;
            $info = $this->services->getByID($id);
            $settings = $this->setting_services->getByUserInfoId($id);
            $user = $this->user_services->getByID($info['user_id']);

            //image in file table
            if ($info['file_id'] !=  NULL) {
                $file = $this->file_services->getByID($info['file_id']);

                $data["info"] = [
                    "user_id" => $info['user_id'],
                    "bucket" => $file['bucket'],
                    "image" => ($file['url'] . "/uploads/" . $file['bucket'] . "/" . $file['id'] . "." . $file['extension']),
                    "username" => $user['email'],
                    "display_name" => $user['name']
                ];
                $data["settings"] = $settings;

                $result->data = $data;
                $result->message = 'Success';
            }
            //default user image
            else {
                $data["info"] = [
                    "user_id" => $info['user_id'],
                    "bucket" => "images",
                    "image" => url('/') . "/images/dashboard_profile_default.png",
                    "username" => $user['email'],
                    "display_name" => $user['name']
                ];
                $data["settings"] = $settings;

                $result->data = $data;
                $result->message = 'Success';
            }
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function adminlist(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();

            $keyword = $input["user_admin"];

            $contacts =  $this->services->adminList($keyword);

            $result->data = $contacts;
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function userList(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();

            $keyword = $input["username"];

            $contacts =  $this->services->userList($keyword);

            $result->data = $contacts;
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function action(Request $request)
    {
        $result = new DataResult;
        //
        try {
            $input = $request->all();
            $result->data = $this->services->action($input);
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function contactUs(Request $request)
    {
        $result = new DataResult;
        $data = array();
        //
        try {
            $input = $request->all();
            $input['user_info_id'] = auth("api")->user()->info->id;

            if ($request->hasFile('attachment')) {
                foreach ($request->file('attachment') as $file) {
                    $file = $this->file_services->SaveFileContent($file);
                    $input['file_id'] =  $file->id;
                }
            } else {
                $input['file_id'] = 0;
            }
            $result->data = $this->inboxes_services->create($input);
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }
}
