<?php

namespace Backend\Http\Controllers;

use Backend\Enums\Message;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AuthenticatesUsers;
    protected $maxLoginAttempts = 5;
    protected $maxAttempts = 5;
    protected $lockoutTime = 300;
    protected $decayMinutes = 1000;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['login']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
            return response()->json(['error' => Message::limitLogin], 429);
        }

        if (!$token = auth('api')->attempt($credentials)) {
            $this->incrementLoginAttempts($request);
            return response()->json(['error' => Message::incorrectAccount], 401);
        }
        return $this->respondWithToken($token);
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'user' => $this->guard()->user(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
    public function guard()
    {
        return \Auth::Guard('api');
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        $attempts = 5;
        $lockoutMinites = 50000;
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $attempts, $lockoutMinites
        );
    }
}
