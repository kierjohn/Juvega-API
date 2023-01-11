<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Inbox;
use Illuminate\Http\Request;

use Acme\Common\CommonFunction;
use Acme\Services\Inboxes as Services;

use Acme\Common\DataResult as DataResult;

class InboxController extends BaseController
{
    use CommonFunction;

    protected $services;

    public function __construct()
    {
        $this->services = new Services;
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function contactUsList(Request $request)
    {
        $result = new DataResult;

        try {
            $input = $request->all();
            $data =  $this->services->contactUsList($input);

            $result->data = $data;
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function read($id)
    {
        $result = new DataResult;

        try {
            $data =  $this->services->read($id);
            $result->data = $data;
            $result->message = 'Success';
        } catch (Exception $e) {
            $result = $this->RequestError($e);
        }

        return response()->json($result, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
