<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Acme\Common\DataResult as DataResult;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $result = new DataResult;
        $result->error = true;
        $result->message = "Unauthorized";

        return response()->json($result, 200);
    }

    protected function unauthenticated($request, array $guards)
    {
        $result = new DataResult;
        $result->error = true;
        $result->message = "Unauthorized";

        abort(response()->json($result, 401));
    }
}
