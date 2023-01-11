<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;

use Acme\Services\Conversations as Services;
use Acme\Services\ConversationUsers as ConversationUserServices;
use Acme\Services\Users as UserServices;
use Acme\Services\UserInfos as UserInfoServices;
use Acme\Services\Files as FileServices;

use Acme\Common\DataResult as DataResult;
use Acme\Common\Entity\Conversation as Entity;
use Acme\Common\CommonFunction;

use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;
use Twilio\Rest\Client;
use Twilio\Exceptions\RestException as TwilioException;

use App\Models\Logger;

class ConversationController extends BaseController
{

    use CommonFunction;

    protected $services;

    public function __construct()
    {
        $this->services = new Services;
        $this->conversation_user_services = new ConversationUserServices;
        $this->user_services = new UserServices;
        $this->user_info_services = new UserInfoServices;
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
        //
        try {
            $input = $request->all();
            $id = $request['id'];
            $conversation_data = $this->services->getByID($id);
            $input["deleted_time"] = $conversation_data->deleted_time;
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
            $result->data = $this->services->deleteById($id);
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }
        return response()->json($result, 200);
    }


    public function deleteByUserInfoId($user_info_id)
    {
        $result = new DataResult;

        try {
            $result->data =  $this->conversation_user_services->deleteByUserInfoId($user_info_id);
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


    public function getByUserInfoId()
    {
        $result = new DataResult;
        try {
            $user_info_id = auth("api")->user()->info->id;
            $data = $this->services->getByUserInfoId($user_info_id);
            $data = $data->map(function ($item) {
                $row = [];
                $row["id"] = $item->id;
                $row["type"] = $item->type;
                $row["created_at"] = $item->created_at;
                $row["message_delete_timer"] = $item->message_delete_timer;
                $row["latest_message"] = $item->message_info->count() != 0 ? $this->decrypt($item->message_info[0]->message) : "";
                $row["message_timestamp"] = $item->message_info->count() != 0 ? $item->message_info[0]->created_at : "";
                $row["last_sender"] = $item->message_info->count() != 0 ? $item->message_info[0]->user_info->user->email : "";

                if ($item->type == 1) {
                    $row["title"] = $item->conversation_users->count() == 1 ? $item->conversation_users[0]->user_info->user->email  : "";
                } else {
                    $row["title"] = $item->name;
                }

                $row["image_url"] = url('/') . "/images/dashboard_profile_default.png";
                $row["conversation_remaining_time"] = strtotime($item->deleted_time)  - strtotime("now");
                $row["badge_count"] = $item->unread[0]->unread_message;

                if ($item->conversation_users->count() > 0) {
                    if (is_null($item->conversation_users[0]->user_setting)) {
                        if ($item->conversation_users[0]->user_status) {
                            $row["user_status"] = $item->conversation_users[0]->user_status->status;
                        } else {
                            $row["user_status"] = 0;
                        }
                    } else {
                        if ($item->conversation_users[0]->user_setting->status == 0) {
                            $row["user_status"] = 0;
                        } else {
                            $row["user_status"] = $item->conversation_users[0]->user_status->status;
                        }
                    }
                } else {
                    $row["user_status"] = 0;
                }

                return $row;
            });

            $result->message = 'Success';
            $result->data = $data;
            $result->tags = $data->count();
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function getGroupsByUserInfoId()
    {
        $result = new DataResult;
        try {
            $data = $this->services->getGroupsByUserInfoId(auth("api")->user()->info->id);
            $data = $data->map(function ($item) {
                $row = [];
                $row["id"] = $item->id;
                $row["type"] = $item->type;
                $row["created_at"] = $item->created_at;
                $row["message_delete_timer"] = $item->message_delete_timer;
                $row["latest_message"] = $item->message_info->count() != 0 ? $this->decrypt($item->message_info[0]->message) : "";
                $row["message_timestamp"] = $item->message_info->count() != 0 ? $item->message_info[0]->created_at : "";
                $row["last_sender"] = $item->message_info->count() != 0 ? $item->message_info[0]->user_info->user->email : "";

                if ($item->type == 1) {
                    $row["title"] = $item->conversation_users->count() == 1 ? $item->conversation_users[0]->user_info->user->email  : "";
                } else {
                    $row["title"] = $item->name;
                }
                if ($item->file_id == 0) {
                    $row["image_url"] = url('/') . "/images/dashboard_profile_default.png";
                } else {
                    $file_data = $this->file_services->getByID($item->file_id);
                    $row["image_url"] = $file_data->url . "/uploads/" . $file_data->bucket . "/" . $file_data->id . "." . $file_data->extension;
                }
                $row["conversation_remaining_time"] = strtotime($item->deleted_time)  - strtotime("now");
                $row["badge_count"] = $item->unread[0]->unread_message;

                if ($item->conversation_users->count() > 0) {
                    if (is_null($item->conversation_users[0]->user_setting)) {
                        if ($item->conversation_users[0]->user_status) {
                            $row["user_status"] = $item->conversation_users[0]->user_status->status;
                        } else {
                            $row["user_status"] = 0;
                        }
                    } else {
                        if ($item->conversation_users[0]->user_setting->status == 0) {
                            $row["user_status"] = 0;
                        } else {
                            $row["user_status"] = $item->conversation_users[0]->user_status->status;
                        }
                    }
                } else {
                    $row["user_status"] = 0;
                }

                return $row;
            });

            $result->message = 'Success';
            $result->data = $data;
            $result->tags = $data->count();
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function view(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();
            $input['user_info_id'] = auth("api")->user()->info->id;
            $item = $this->services->view($input);

            $info = [];
            $info["id"] = $item->id;
            $info["type"] = $item->type;
            $info["created_at"] = $item->created_at;
            $info["message_delete_timer"] = $item->message_delete_timer;
            $info["conversation_remaining_time"] = strtotime($item->deleted_time)  - strtotime("now");

            $info["messages"] = $item->message_info->map(function ($row) {
                $entity["id"] = $row->id;
                $entity["created_by"] = $row->created_by;
                $entity["message"] = $this->decrypt($row->message);
                $entity["created_at"] = $row->created_at;
                $entity["now"] = strtotime("now");
                $entity["deleted_time"] = strtotime($row->deleted_time);
                $entity["message_remaining_time"] = strtotime($row->deleted_time)  - strtotime("now");

                if ($row->user_info->user) {
                    $entity["username"] = $row->user_info->user->email;
                } else {
                    $entity["username"] = "Unknown:" . $row->user_info_id;
                }

                return $entity;
            });

            $info["users"] = $item->conversation_users->map(function ($row) {

                $entity["user_info_id"] = $row->user_info_id;

                if ($row->user_info) {
                    $entity["username"] = $row->user_info->user->email;

                    if ($row->user_info->file) {
                        $url =  $row->user_info->file->url;
                        $bucket = $row->user_info->file->bucket;
                        $id = $row->user_info->file->id;
                        $extension = $row->user_info->file->extension;
                        $entity["image"] = $url . "/uploads/" . $bucket . "/" . $id . "." . $extension;
                    } else {
                        $entity["image"] =  url('/') . "/images/dashboard_profile_default.png";
                    }
                } else {
                    $entity["username"] = "Unknown:" . $row->user_info_id;
                }


                if (is_null($row->user_setting)) {
                    if ($row->user_status) {
                        $entity["user_status"] = $row->user_status->status;
                    } else {
                        $entity["user_status"] = 0;
                    }
                } else {
                    if ($row->user_setting->status == 0) {
                        $entity["user_status"] = 0;
                    } else {
                        $entity["user_status"] = $row->user_status->status;
                    }
                }

                return $entity;
            });

            $result->message = 'Success';
            $result->data = $info;
        } catch (DecryptException  $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function check(Request $request)
    {
        $result = new DataResult;
        try {
            $sender = auth("api")->user()->info->id;
            $receiver = $request['contact_id'];


            $result->data = $this->services->check($sender, $receiver);

            if ($result->data) {
                $result->tags = true;
            } else {
                $result->tags = false;
            }

            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function detailedById($id)
    {
        $result = new DataResult;
        try {
            $item = $this->services->detailedById($id);

            $info = [];
            $info["id"] = $item->id;
            $info["name"] = $item->name;
            $info["image_url"] = ($item['url'] . "/images/dashboard_profile_default.png");

            $info["users"] = $item->conversation_users->map(function ($row) {
                $entity["user_info_id"] = $row->user_info_id;
                $entity["user_image"] = "/images/dashboard_profile_default.png";
                if ($row->user_info) {
                    if ($row->user_info->file) {
                        $bucket = $row->user_info->file->bucket;
                        $name = $row->user_info->file->name;
                        $extension = $row->user_info->file->extension;
                        $entity["user_image"] = "/uploads/" . $bucket . "/" . $name . "." . $extension;
                    }

                    $entity["username"] = $row->user_info->user->email;
                } else {
                    $entity["username"] = "Unknown:" . $row->user_info_id;
                }

                return $entity;
            });

            $result->data = $info;
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }


    public function updatePhoto(Request $request)
    {
        $result = new DataResult;
        //
        try {
            $input = $request->all();


            if ($request->hasFile('chat_photo')) {
                $id =  $input['conversation_id'];

                $raw_file = $request->file('chat_photo');
                $this->services->uploadPhoto($raw_file, $id);
            }
            $result->data = $id;
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function createVideoRoom(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();
            $conversation_id =  $input["conversation_id"];
            $user_info_id = auth("api")->user()->info->id;

            $sid = getenv("TWILIO_ACCOUNT_SID");
            $token = getenv("TWILIO_AUTH_TOKEN");
            $twilio = new Client($sid, $token);
            $room_name = "conversation_" . $conversation_id;

            $room = $twilio->video->v1->rooms
                ->create([
                    "uniqueName" => $room_name,
                    "statusCallback" => "http://3.26.220.246/index.php/api/room/webhooks",
                    "statusCallbackMethod" => "POST"
                ]);
            $room_sid = $room->sid;

            $access_token = $this->createGrantAccessVideoRoom($conversation_id,  $room_name, $user_info_id, $room_sid);
            $access_token["room_sid"] =  $room_sid;
            $result->data = $access_token;
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        } catch (TwilioException $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    private function createGrantAccessVideoRoom($conversation_id, $room_name, $user_info_id, $room_sid)
    {
        $twilioAccountSid = getenv('TWILIO_ACCOUNT_SID');
        $twilioApiKey = getenv('TWILIO_API_KEY');
        $twilioApiSecret = getenv('TWILIO_API_KEY_SECRET');

        // Required for Video grant
        $roomName = $room_name;
        // An identifier for your app - can be anything you'd like
        $identity = $user_info_id;

        // Create access token, which we will serialize and send to the client
        $token = new AccessToken(
            $twilioAccountSid,
            $twilioApiKey,
            $twilioApiSecret,
            3600,
            $identity
        );

        // Create Video grant
        $videoGrant = new VideoGrant();
        $video = $videoGrant->setRoom($roomName);

        // Add grant to token
        $token->addGrant($videoGrant);


        // render token to string
        $jtw =  $token->toJWT();

        $res = [
            "token" => $jtw,
            "room_name" => $roomName,
            "room_sid" => $room_sid
        ];

        $device_ids = $this->services->getUserIds($conversation_id);
        $devices = $this->user_info_services->getDeviceTokensWithId($device_ids);


        foreach ($devices as $device) {
            $uii = $device->id;
            if ($uii != $user_info_id) {
                $process = $this->createForOtherUser($conversation_id, $room_name, $uii, $room_sid);

                $device_token = [$device->device_token];

                $notification = [
                    "title" => "Call " . $room_name,
                    "body" => "Incoming Call"
                ];

                $data = [
                    "type" => "INCOMING_CALL",
                    "call_info" => $process
                ];

                //$this->Log("GenerateToken" , $process);

                $this->SendNotification($device_token, $notification, $data);
            }
        }


        return $res;
    }

    private function createForOtherUser($conversation_id, $room_name, $user_info_id, $room_sid)
    {
        $twilioAccountSid = getenv('TWILIO_ACCOUNT_SID');
        $twilioApiKey = getenv('TWILIO_API_KEY');
        $twilioApiSecret = getenv('TWILIO_API_KEY_SECRET');

        // Required for Video grant
        $roomName = $room_name;
        // An identifier for your app - can be anything you'd like
        $identity = $user_info_id;

        // Create access token, which we will serialize and send to the client
        $token = new AccessToken(
            $twilioAccountSid,
            $twilioApiKey,
            $twilioApiSecret,
            3600,
            $identity
        );

        // Create Video grant
        $videoGrant = new VideoGrant();
        $video = $videoGrant->setRoom($roomName);

        // Add grant to token
        $token->addGrant($videoGrant);


        // render token to string
        $jtw =  $token->toJWT();

        $res = [
            "token" => $jtw,
            "room_name" => $roomName,
            "room_sid" => $room_sid
        ];

        return $res;
    }

    public function generateAccessToken(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();
            $conversation_id = $input["conversation_id"];
            $room_name = $input["room_name"];
            $user_info_id = auth("api")->user()->info->id;
            $room_sid = $input["room_sid"];
            $data = $this->createForOtherUser($conversation_id, $room_name, $user_info_id, $room_sid);

            $result->data = $data;
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function endRoom(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();
            $conversation_id =  $input["conversation_id"];
            $room_sid =  $input["room_sid"];

            $sid = getenv("TWILIO_ACCOUNT_SID");
            $token = getenv("TWILIO_AUTH_TOKEN");
            $twilio = new Client($sid, $token);
            $room_name = "conversation_" . $conversation_id;

            $room = $twilio->video->v1->rooms($room_sid)
                ->update("completed");

            $result->data = $room_sid;
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function videoWebhooks(Request $request)
    {
        $result = new DataResult;

        try {

            $input = $request->all();
            $this->Log("Webhooks", $input);
            /*
            $input = $request->all();
            $conversation_id =  $input["conversation_id"];
            $room_sid =  $input["room_sid"];

            $sid = getenv("TWILIO_ACCOUNT_SID");
            $token = getenv("TWILIO_AUTH_TOKEN");
            $twilio = new Client($sid, $token);
            $room_name = "conversation_" . $conversation_id;
            */

            $result->data = 0;
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    private function Log($key, $value)
    {
        Logger::create([
            "key" => $key,
            "value" => json_encode($value)
        ]);
    }
}
