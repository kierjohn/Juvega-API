<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Acme\Services\ConversationUsers as Services;

use Acme\Common\DataResult as DataResult;
use Acme\Common\Entity\ConversationUser as Entity;
use Acme\Common\CommonFunction;
use App\Models\ConversationUser;
use App\Models\User;

class ConversationUserController extends BaseController
{

    use CommonFunction;

    protected $services;

    public function __construct()
    {
        $this->services = new Services;
    }

    public function create(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();

            $user = User::where('email', '=', $input["username"])->first();
            $userInfoId = ConversationUser::where('user_info_id', '=', $user['id'])->first();
            if ($userInfoId != null) {
                $result->message = "User Already In Conversation";
                $result->tags = ["username" => $user['email']];
                $result->error = true;

                return response()->json($result, 200);
            }
            $input['user_info_id'] = $user['id'];
            $Conversation_id = $input['conversation_id'];
            $username = $input['username'];
            $entity = new Entity;
            $entity->SetData($input);
            $data = $entity->Serialize();

            $result->data = $this->services->createConversationUser($data, $Conversation_id, $username);
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
}
