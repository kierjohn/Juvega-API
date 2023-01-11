<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Message;
use Illuminate\Http\Request;

use Acme\Common\CommonFunction;
use Acme\Common\Constants as Constants;
use Acme\Services\Messages as Services;
use Acme\Services\Users as UserServices;
use Acme\Services\Conversations as ConversationServices;
use Acme\Services\UserInfos as UserInfosServices;
use Acme\Services\UserSettings as UserSettingsServices;
use Acme\Common\DataResult as DataResult;
use Acme\Common\Entity\Message as Entity;

use Acme\Common\Entity\Conversation as ConversationEntity;
use Acme\Common\Entity\ConversationUser as ConversationUserEntity;

class MessagesController extends BaseController
{

    use CommonFunction;

    protected $services;

    public function __construct()
    {
        $this->services = new Services;
        $this->user_services = new UserServices;
        $this->conversation_services = new ConversationServices;
        $this->user_info_services = new UserInfosServices;
        $this->user_setting_services = new UserSettingsServices;
    }

    public function create(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();
            $input['created_by'] = $request['user_info_id'];

            $user = Message::where('conversation_id', '=', $input["conversation_id"])->first();
            if ($user != null) {
                $result->message = "Conversation Exist.";
                $result->tags = $user;
                $result->error = true;

                return response()->json($result, 200);
            }
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

    public function update(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();
            $id = $request['id'];
            $input['created_by'] = auth("api")->user()->info->id;
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

    public function getConversationId(Request $request, $ConversationId)
    {
        $result = new DataResult;
        try {
            $entity = new Entity;

            $data = $this->services->getByUserInfoID($ConversationId);

            $result->message = 'Success';
            $result->data = $data;
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function new(Request $request)
    {
        $result = new DataResult;
        $current_time = now();

        try {

            $input = $request->all();
            $input['user_sender'] =  auth("api")->user()->info->id;

            $entity = new Entity;
            $ConversationEntity = new ConversationEntity;
            $ConversationUserEntity = new ConversationUserEntity;

            $entity->SetData($input);
            $ConversationEntity->SetData($input);
            $ConversationUserEntity->SetData($input);

            $data = $entity->Serialize();
            $Conversationdata = $ConversationEntity->Serialize();
            $ConversationUserdata = $ConversationUserEntity->Serialize();

            $result->data = $this->services->new($input, $data, $Conversationdata, $ConversationUserdata);

            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function send(Request $request)
    {
        $result = new DataResult;
        $current_time = now();

        try {
            $input = $request->all();
            $user_sender =  auth("api")->user()->info->id;
            $input['created_by'] = $user_sender;
            $input['user_info_id'] = $user_sender;
            $input['message'] = $this->encrypt($request['message']);
            $id = $request['conversation_id'];
            $conversation = $this->conversation_services->checkExist($id);
            if ($conversation) {
                $id = $conversation->id;
                $c_data = $this->conversation_services->getById($id);
                $us_delete_timer = $c_data->message_delete_timer;
                $input["deleted_time"] = $current_time->addSeconds($us_delete_timer);

                $entity = new Entity;
                $entity->SetData($input);
                $data = $entity->Serialize();

                $result->data = $this->services->send($data);
                $result->message = 'Success';
            } else {
                $result->message = 'No Conversation exist';
            }
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function deleteByTimer(Request $request)
    {

        $result = new DataResult;
        $data = new DataResult;

        try {

            $result->data = $this->services->deleteByTimer($data);
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }
}
