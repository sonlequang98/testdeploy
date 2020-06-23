<?php

namespace Backend\Http\Controllers;

use Backend\Enums\Message;
use Backend\Http\Requests\PostUserAdminRequest;
use Backend\Repositories\UserAdminRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class UserAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $user;
    public function __construct(UserAdminRepositoryInterface $user)
    {
        $this->user = $user;
    }
    public function index()
    {
        if ($this->user->all()) {
            return response()->json([
                'data' => $this->user->all(),
            ], 200);
        }
        return response()->json([

            'message' => Message::processError,

        ], 400);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostUserAdminRequest $request)
    {
        $user = $this->user->create($request);
        if ($user) {
            return response()->json([
                'data' => $user,
            ], 200);
        }
        return response()->json([
            'message' => Message::processError,
        ], 400);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
    public function update($id, Request $request)
    {
        $user = $this->user->get($id);
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                Rule::unique('users')->ignore($user),
            ],
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => Message::processError,
            ], 400);
        }
        if ($this->user->update($id, $request)) {
            return response()->json([
                'data' => true,
            ], 200);
        }
        return response()->json([
            'message' => Message::processError,
        ], 400);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->user->delete($id)) {
            return response()->json([
                'status' => false,
            ]);
        }
        return response()->json([
            'message' => Message::processError,
        ], 400);

    }

    public function getRoles()
    {
        return $this->user->getRoles();
    }

    public function search(Request $request)
    {
        return response()->json([
            'data' => $this->user->searchUser($request),
        ]);
    }

    public function getRoleId()
    {
        return auth('api')->user()->role_id;
    }
}
