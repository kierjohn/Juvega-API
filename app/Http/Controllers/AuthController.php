<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Acme\Common\DataResult as DataResult;
use Acme\Common\Entity\UserActivity as Entity;
use Acme\Services\UserActivities as UserActivityServices;
use Acme\Services\Files as FileServices;
use Acme\Services\UserInfos as UserInfoServices;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserSetting;

use Psr\Http\Message\ServerRequestInterface;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Acme\Common\CommonFunction;
use Laravel\Passport\Token;



class AuthController extends AccessTokenController
{
    use CommonFunction;

    protected $user_activity_services;

    public function __construct()
    {
        $this->user_activity_services = new UserActivityServices;
        $this->file_services = new FileServices;
        $this->user_info = new UserInfoServices;
    }

    public function auth(ServerRequestInterface $request)
    {
        $result = new DataResult;

        try {
            $username = $request->getParsedBody()['client_secret'];

            $tokenResponse = parent::issueToken($request);
            $token = $tokenResponse->getContent();

            // $tokenInfo will contain the usual Laravel Passort token response.
            $tokenInfo = json_decode($token, true);

            // Then we just add the user to the response before returning it.
            $username = $request->getParsedBody()['username'];
            $user = User::whereEmail($username)->first();
            $user_info = UserInfo::where("user_id", $user->id);

            $tokenInfo = collect($tokenInfo);
            $tokenInfo->put('user', $user);
            $tokenInfo->put('info', $user_info);

            //$result->data = $tokenInfo;
            $result->data = $tokenInfo;
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function process(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();
            $username = $input["username"];
            $password = $input["password"];

            $user = User::where("email", "=", $username)->first();

            if ($user) {
                if ($user->password == hash('sha512', $password)) {

                    $user_info = UserInfo::where("user_id", $user->id)->first();
                    $token = $user->createToken('Personal Token')->accessToken;

                    $input = $request->all();
                    $input['activity'] = "LOGIN";
                    $input['status'] = "1";
                    $input['user_info_id'] = $user_info->id;

                    $entity = new Entity;
                    $entity->SetData($input);
                    $ua_data = $entity->Serialize();

                    $this->user_activity_services->log($ua_data);

                    $data["token"] = $token;
                    $data["user"] = [
                        "id" => $user->id,
                        "username" => $user->email
                    ];

                    if ($user_info['file_id'] !=  NULL) {
                        $file = $this->file_services->getByID($user_info['file_id']);

                        $data["user_info"] = [
                            "id" => $user_info->id,
                            "status" => $user_info->status,
                            "user_type" => $user_info->user_type,
                            "image" => ($file['url'] . "/uploads/" . $file['bucket'] . "/" . $file['id'] . "." . $file['extension']),
                        ];
                    } else {
                        $data["user_info"] = [
                            "id" => $user_info->id,
                            "status" => $user_info->status,
                            "user_type" => $user_info->user_type,
                            "image" => url('/') . "/images/dashboard_profile_default.png",
                        ];
                    }


                    $user_settings = UserSetting::where("user_info_id", $user_info->id)->first();

                    $data["user_settings"] = [
                        "status" => 1,
                        "notification" => 1,
                        "delete_timer" => 1,
                        "lock_screen" => 1,
                        "alert_tone" => 1,
                        "vibrate" => 1,
                    ];

                    if ($user_settings) {
                        $data["user_settings"] = [
                            "status" => $user_settings->status,
                            "notification" => $user_settings->notification,
                            "delete_timer" => $user_settings->delete_timer,
                            "lock_screen" => $user_settings->lock_screen,
                            "alert_tone" => $user_settings->alert_tone,
                            "vibrate" => $user_settings->vibrate,
                        ];
                    }

                    $result->data = $data;
                    $result->message = 'Successfully logged in';
                } else {
                    $result->error = true;
                    $result->message = 'Invalid credentials';
                }
            } else {
                $result->error = true;
                $result->message = 'User not found.';
            }
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function adminProcess(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();
            $username = $input["user_admin"];
            $password = $input["password"];

            $user = User::where("email", "=", $username)->first();

            if ($user) {
                if ($user->password == hash('sha512', $password)) {

                    $user_info = UserInfo::where("user_id", $user->id)->first();
                    if ($user_info->user_type <> 2) {
                        $result->error = true;
                        $result->message = 'User not found.';
                    } else {
                        $token = $user->createToken('Personal Token')->accessToken;

                        $input = $request->all();
                        $input['activity'] = "LOGIN";
                        $input['status'] = "1";
                        $input['user_info_id'] = $user_info->id;

                        $entity = new Entity;
                        $entity->SetData($input);
                        $ua_data = $entity->Serialize();

                        $this->user_activity_services->log($ua_data);

                        $data["token"] = $token;
                        $data["user"] = [
                            "id" => $user->id,
                            "username" => $user->email
                        ];

                        if ($user_info['file_id'] !=  null) {
                            $file = $this->file_services->getByID($user_info['file_id']);

                            $data["user_info"] = [
                                "id" => $user_info->id,
                                "status" => $user_info->status,
                                "user_type" => $user_info->user_type,
                                "image" => ($file['url'] . "/uploads/" . $file['bucket'] . "/" . $file['id'] . "." . $file['extension']),
                            ];
                        } else {
                            $data["user_info"] = [
                                "id" => $user_info->id,
                                "status" => $user_info->status,
                                "user_type" => $user_info->user_type,
                                "image" => url('/') . "/images/dashboard_profile_default.png",
                            ];
                        }


                        $user_settings = UserSetting::where("user_info_id", $user_info->id)->first();

                        $data["user_settings"] = [
                            "status" => 1,
                            "notification" => 1,
                            "delete_timer" => 1,
                            "lock_screen" => 1,
                            "alert_tone" => 1,
                            "vibrate" => 1,
                        ];

                        if ($user_settings) {
                            $data["user_settings"] = [
                                "status" => $user_settings->status,
                                "notification" => $user_settings->notification,
                                "delete_timer" => $user_settings->delete_timer,
                                "lock_screen" => $user_settings->lock_screen,
                                "alert_tone" => $user_settings->alert_tone,
                                "vibrate" => $user_settings->vibrate,
                            ];
                        }

                        $result->data = $data;
                        $result->message = 'Successfully logged in';
                    }
                } else {
                    $result->error = true;
                    $result->message = 'Invalid credentials';
                }
            } else {
                $result->error = true;
                $result->message = 'User not found.';
            }
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function redirect(Request $request)
    {
        $result = new DataResult;

        try {

            $body = $request->all();

            $response = Http::asForm()->post('/api/system/test', [
                'grant_type' => 'authorization_code',
                'client_id' => 'client-id',
                'client_secret' => 'client-secret',
            ]);

            return $response->json();
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function logout(Request $request)
    {
        $result = new DataResult;

        try {

            $input = $request->all();
            $user_info_id = auth("api")->user()->info->id;

            $user_info = UserInfo::where("id", $user_info_id)->first();
            $user = User::where("id", "=", $user_info->user_id)->first();
            UserInfo::where("id", $user_info_id)->update(['device_token' => '']);

            $input['activity'] = "LOGOUT";
            $input['user_info_id'] = $user_info->id;

            $entity = new Entity;
            $entity->SetData($input);
            $data = $entity->Serialize();


            //logout current device

            $user = auth("api")->user();
            //$user->revoke();

            //logout all devices (delete)
            $tokens =  $user->tokens->pluck('id');
            Token::whereIn('id', $tokens)->delete();

            //Update revoked in Refresh oath Refresh Tokens table
            //RefreshToken::whereIn('access_token_id', $tokens)->update(['revoked' => true]);


            $this->user_activity_services->create($data);
            $result->data = $user;
            $result->message = 'Successfully logged out';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function saveToken(Request $request)
    {
        $result = new DataResult;

        try {

            $input = $request->all();
            $token = $input["token"];
            $user_info_id = $input["user_info_id"];

            $user = auth("api")->user();
            $this->user_info->update(['device_token' => $token], $user_info_id);
            $result->message = 'token saved successfully.';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    private function getGoogleAccessToken()
    {

        $credentialsFilePath = base_path() . '/juvega-5f5e4-65ff7a3b5d34.json';
        $client = new \Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();
        return $token['access_token'];
    }

    public function sendNotification(Request $request)
    {
        $token = $this->getGoogleAccessToken();

        $firebaseToken = UserInfo::whereNotNull('device_token')->pluck('device_token')->all();

        $SERVER_API_KEY = 'AAAAJyQPQOY:APA91bFXyfk-zy5o5e6gYLWma2Ep7EzEbefUhJwUZSVaCjWQZsSK9Jw1IwiA5rqwJbLYToPeSzXhsivTQ8ZRNsge6djdcrbkgOK85gF6rGWyWr8VUJfqQ9DAy3PmnA6ahW_wPGsTQTY6';

        $data = [
            "topic" => "convo-1",
            "notification" => [
                "title" => "notification-test",
                "body" => "test notif",
            ]
        ];

        $message = [
            "message" => $data
        ];

        $dataString = json_encode($message);


        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/juvega-5f5e4/messages:send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        dd($response);
    }

    public function sendNotificationLegacy(Request $request)
    {
        $firebaseToken = UserInfo::whereNotNull('device_token')->pluck('device_token')->all();

        $SERVER_API_KEY = 'AAAAJyQPQOY:APA91bFXyfk-zy5o5e6gYLWma2Ep7EzEbefUhJwUZSVaCjWQZsSK9Jw1IwiA5rqwJbLYToPeSzXhsivTQ8ZRNsge6djdcrbkgOK85gF6rGWyWr8VUJfqQ9DAy3PmnA6ahW_wPGsTQTY6';

        $data = [
            "registration_ids" => [
                "d8xyPKpjTuGK5zSGUW_A34:APA91bHU1NHkV6ZqB8C3CO6bEUS2MeL9sk4GpZctHiK9l40qhkxj8OiUGQBL37oSj7C100Jg5IYrW3RAefvTzoFcNU2Hje-5yWyyYVJW83GRBLWrqjXVPeDnuas5u9w2f_H0f543bFSh"

            ],
            "notification" => [
                "title" => "test-convo",
                "body" => "indi",
            ],
            "data" => [
                "story_id" => "story_12345"
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        dd($response);
    }
}
