<?php

namespace Frontend\Http\Controllers;

use Frontend\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $user;
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        $user = Auth::user();
        return View('user')->with(compact('user'));
    }

    public function update($id, Request $request)
    {
        if ($this->user->update($id, $request)) {
            return redirect()->route('user-profile');
        }
        return redirect()->route('users')->with('failed', '情報処理の中に、エーラが発生しました。');
    }
}
