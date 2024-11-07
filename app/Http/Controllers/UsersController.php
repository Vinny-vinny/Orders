<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends BaseController
{
    public function user(Request $request)
    {
        $user = $request->user();

        return $this->sendResponse($user, 'User found');
    }
}
