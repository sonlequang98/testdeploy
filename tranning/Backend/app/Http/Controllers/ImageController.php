<?php

namespace Backend\Http\Controllers;

use Backend\Enums\Message;
use Backend\Http\Requests\PostImageRequest;
use Backend\Repositories\ImageRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ImageController extends Controller
{
    protected $image;
    public function __construct(ImageRepositoryInterface $image)
    {
        $this->image = $image;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'data' => $this->image->all(),
        ], 200);
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
    public function store(PostImageRequest $request)
    {
        if ($request->file('file')) {
            $file = $request->file('file');
            $name = $file->getClientOriginalName();
            $now = Carbon::now()->format('YmdHis');
            $name = $name.$now.'.'.$file->getClientOriginalExtension();
            $file->move(public_path() . '/web/images/', $name);
        }
        $data = [
            'name' => $name,
            'upload_path' => '/web/images/' . $name,
        ];
        return response()->json([
            'data' => $this->image->create($data),
        ], 200);

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->image->delete($id)) {
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
        $response = $this->image->search($request);
        return response()->json([
            'data' => $response,
        ]);
    }

    public function getUploadPath($id)
    {
        $res = $this->image->get($id)->upload_path;
        return response()->json([
            'data' => $res,
        ]);
    }

    public function deleteLastImage(Request $request)
    {
        $res = $this->image->deleteLastImage($request->data);
        return response()->json([
            'data' => $res,
        ]);
    }

}
