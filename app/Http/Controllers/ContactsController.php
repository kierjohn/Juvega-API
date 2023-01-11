<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Acme\Common\CommonFunction;

use Acme\Services\Users as Users;
use Acme\Services\ConversationUsers;
use Acme\Common\Constants as Constants;

use Acme\Services\Contacts as Services;
use Acme\Common\DataResult as DataResult;
use Acme\Common\Entity\Contact as Entity;
use Acme\Services\Conversations as ConversationServices;

class ContactsController extends BaseController
{

    use CommonFunction;

    protected $services;
    protected $user_services;

    public function __construct()
    {
        $this->services = new Services;
        $this->conversation_user_services = new ConversationUsers;
        $this->user_services = new Users;
        $this->conversation_services = new ConversationServices;
    }

    public function create(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();
            $username = $input["username"];
            $user = $this->user_services->getByUsername($username);

            if ($user) {
                $new_input = [
                    "contact_id" => $user->info->id,
                    "user_info_id" => auth("api")->user()->info->id,
                    "status" => 1
                ];

                $new_input2 = [
                    "contact_id" => auth("api")->user()->info->id,
                    "user_info_id" => $user->info->id,
                    "status" => 1
                ];

                $entity = new Entity;
                $entity->SetData($new_input);
                $data = $entity->Serialize();

                $entity2 = new Entity;
                $entity2->SetData($new_input2);
                $data2 = $entity2->Serialize();

                $result->message = 'Success';
                $result->data = $this->services->create($data);
                $result->data =  $this->services->sendContactNotification($data);
                $result->data = $this->services->create($data2);
            } else {
                $result->error = true;
                $result->message = 'User not found';
            }
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

            $data = $this->services->getByIdWithDetails($id);
            $sender = $data->user_info_id;
            $receiver = $data->contact_id;
            $conversation = $this->conversation_services->check($sender, $receiver);
            $conversation_id = 0;
            if ($conversation) {
                $conversation_id = $conversation->id;
            }

            $res = [
                "id" => $data->id,
                "contact_id" => $data->contact_id,
                "user_info_id" => $data->user_info_id,
                "status" => $data->status,
                "created_at" => $data->created_at,
                "updated_at" => $data->updated_at,
                "favorite" => $data->favorite,
                "username" => $data->user_info->user->email,
                "conversation_id" => $conversation_id
            ];

            $result->message = 'Success';
            $result->data = $res;
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

    public function update(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();
            $id = $request['id'];
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
            $keyword = $request['username'];
            $result->message = 'Success';
            $UserInfoId = auth("api")->user()->info->id;
            $result->data =  $this->services->getContacts($UserInfoId, $keyword);
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function search(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();

            $keyword = $input["username"];

            $contacts = $this->services->search($keyword);

            $result->data = $contacts;
            $result->message = '';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function getPinnedID(Request $request)
    {
        $result = new DataResult;
        try {
            $entity = new Entity;
            $UserInfoId = auth("api")->user()->info->id;
            $data = $this->services->getPinnedID($UserInfoId);

            $data = $data->map(function ($entity) {

                $sender = $entity->user_info_id;
                $receiver = $entity->contact_id;
                $conversation = $this->conversation_services->check($sender, $receiver);
                $conversation_id = 0;
                if ($conversation) {
                    $conversation_id = $conversation->id;
                }

                $item = [
                    "id" => $entity->id,
                    "status" => $entity->status,
                    "favorite" => $entity->favorite,
                    "contact_id" => $entity->contact_id,
                    "created_at" => $entity->created_at,
                    "name" => $entity->contact_info->user->email,
                    "conversation_id" => $conversation_id
                ];

                return $item;
            });

            $result->message = 'Success';
            $result->data = $data;
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function getUnpinnedID(Request $request)
    {
        $result = new DataResult;
        try {
            $entity = new Entity;
            $UserInfoId = auth("api")->user()->info->id;
            $data = $this->services->getUnpinnedID($UserInfoId);

            $data = $data->map(function ($entity) {

                $sender = $entity->user_info_id;
                $receiver = $entity->contact_id;
                $conversation = $this->conversation_services->check($sender, $receiver);
                $conversation_id = 0;
                if ($conversation) {
                    $conversation_id = $conversation->id;
                }

                $item = [
                    "id" => $entity->id,
                    "status" => $entity->status,
                    "favorite" => $entity->favorite,
                    "contact_id" => $entity->contact_id,
                    "created_at" => $entity->created_at,
                    "name" => $entity->contact_info->user->email,
                    "conversation_id" => $conversation_id
                ];

                return $item;
            });

            $result->message = 'Success';
            $result->data = $data;
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function pin(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();
            $id = $request['id'];
            $flag = $request['favorite'];


            $result->data = $this->services->pin($id, $flag);
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }
}
