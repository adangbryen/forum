<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        $user = User::where('confirmation_token', request('token'))
            ->firstOrFail();
        if (!$user) {
            return \redirect('/threads')
                ->with('flash', 'Invalid Token');
        }
        $user->confirm();
        return \redirect('/threads')
                ->with('flash', 'You are confirmed!!');
    }
}
