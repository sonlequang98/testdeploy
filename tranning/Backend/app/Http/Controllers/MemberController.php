<?php

namespace Backend\Http\Controllers;

use Backend\Enums\Message;
use Backend\Http\Requests\PostMemberRequest;
use Backend\Repositories\MemberRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $member;
    public function __construct(MemberRepositoryInterface $member)
    {
        $this->member = $member;
    }
    
    public function index()
    {
        return response()->json([
            'data' => $this->member->all(),
        ]);
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
    public function store(PostMemberRequest $request)
    {
        $member = $this->member->create($request);
        if ($member) {
            return response()->json([
                'data' => $member,
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
    public function update(Request $request, $id)
    {
        $member = $this->member->get($id);
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                Rule::unique('users')->ignore($member),
            ],
            'name'  =>  'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => Message::processError,
            ], 400);
        }
        if ($this->member->update($id, $request)) {
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
        if ($this->member->delete($id)) {
            return response()->json([
                'data' => true,
            ]);
        }
        return response()->json([

            'message' => Message::processError,
        ]);
    }

    public function search(Request $request)
    {
        return response()->json([
            'data' => $this->member->search($request),
        ]);
    }
}
