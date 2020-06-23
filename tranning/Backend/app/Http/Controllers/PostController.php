<?php

namespace Backend\Http\Controllers;

use Backend\Enums\Message;
use Backend\Http\Requests\PostPostRequest;
use Backend\Repositories\PostRepositoryInterface;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $post;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(PostRepositoryInterface $post)
    {
        $this->post = $post;
    }
    public function index()
    {
        if ($this->post->all()) {
            return response()->json([
                'data' => $this->post->all(),
            ], 200);
        }
        return response()->json([
            'data' => false,
            'error' => [
                'status' => true,
                'message' => Message::processError,
            ],
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
    public function store(PostPostRequest $request)
    {
        if ($this->post->create($request)) {
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
        return response()->json([
            'data' => $this->post->get($id),
        ]);
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
        if ($this->post->update($id, $request)) {
            return response()->json([
                'data' => true,
            ]);
        }
        return response()->json([
            'data' => false,
            'error' => [
                'status' => true,
                'message' => Message::processError,
            ],
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
        if ($this->post->delete($id)) {
            return response()->json([
                'data' => true,
                'error' => [
                    'status' => false,
                ],
            ]);
        }
        return response()->json([
            'data' => false,
            'error' => [
                'status' => true,
                'message' => Message::processError,
            ],
        ]);

    }
    public function search(Request $request)
    {
        return response()->json([
            'data' => $this->post->search($request),
        ]);
    }
}
