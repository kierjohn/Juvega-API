<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

use Acme\Common\CommonFunction;
use Acme\Services\Files as Services;

use Acme\Common\Entity\File as Entity;
use Acme\Common\Constants as Constants;
use Acme\Common\DataResult as DataResult;



class FilesController extends BaseController
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

    public function update(Request $request, $id)
    {
        $result = new DataResult;
        //
        try {
            $input = $request->all();
            $input['id'] = $id;
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
