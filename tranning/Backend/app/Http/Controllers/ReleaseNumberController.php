<?php

namespace Backend\Http\Controllers;

use Backend\Enums\Message;
use Backend\Http\Requests\PostReleaseNumberRequest;
use Backend\Http\Requests\PutReleaseNumberRequest;
use Backend\Repositories\ReleaseNumberRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class ReleaseNumberController extends Controller
{
    protected $release_number;
    public function __construct(ReleaseNumberRepositoryInterface $release_number)
    {
        $this->release_number = $release_number;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->release_number->all()) {
            return response()->json([
                'data' => $this->release_number->all(),
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
    public function store(PostReleaseNumberRequest $request)
    {
        if ($this->release_number->create($request)) {
            return response()->json([
                'data' => true,
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json([
            'data' => $this->release_number->get($id),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PutReleaseNumberRequest $request, $id)
    {
        $releaseNumber = $this->release_number->get($id);
        $validator = Validator::make($request->all(), [
            'release_number_name' => [
                'required',
                Rule::unique('release_numbers')->ignore($releaseNumber),
            ],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => Message::processError,
            ], 400);
        }
        if ($this->release_number->update($id, $request)) {
            return response()->json([
                'data' => true,
            ]);
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
        if ($this->release_number->delete($id)) {
            return response()->json([
                'data' => true,
            ]);
        }
        return response()->json([
            'message' => Message::processError,
        ]);

    }

    public function getAll()
    {
        return response()->json([
            'data' => $this->release_number->getAll(),
        ]);
    }
}
