<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Psr\Http\Message\ServerRequestInterface;
use \Laravel\Passport\Http\Controllers\AccessTokenController;
use Acme\Common\DataResult as DataResult;

class TestController extends BaseController
{
    public function server(ServerRequestInterface $request)
    {
            return php_info();
    }

    public function testHeader(Request $request)
    {
        $result = new DataResult;

        try {
            $value = $request->header('Authorization');
            $token = $request->header('custom-token');

            $result->data = [
                "token" => $value,
                "custom" => $token
            ];

        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

}