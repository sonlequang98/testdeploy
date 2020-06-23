<?php

namespace Frontend\Http\Controllers;

use Frontend\Http\Requests\PostUserRequest;
use Frontend\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Frontend\Enums\Message;

class AuthController extends Controller
{
    private $user;
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->route('index');
        }
        return redirect()->route('index')->with('failedLogin', Message::incorrectAccount);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' =>  'required|unique:users',
            'name'  =>  'required',
            'password'  =>  'required'
        ]);
        if ($validator->fails()) {
            return redirect()->route('index')->with('failedRegister', Message::processError);
        }
        $user = $this->user->create($request);
        if ($user) {
            auth()->login($user);
            return redirect()->route('index');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }

}
