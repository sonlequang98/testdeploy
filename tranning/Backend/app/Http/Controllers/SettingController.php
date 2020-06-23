<?php

namespace Backend\Http\Controllers;

use Illuminate\Http\Request;
use Backend\Repositories\SettingRepositoryInterface;
use Backend\Enums\Message;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $setting;
    public function __construct(SettingRepositoryInterface $setting)
    {
        $this->setting = $setting;
    }
    public function index()
    {
        if($this->setting->all())
        {
            return response()->json([
                'data'  =>  $this->setting->all()
            ],200);
        }
        return response()->json([
            'error' => [
                'message'   =>  Message::processError,
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
    public function store(Request $request)
    {
        //
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
        if($this->setting->update($id,$request))
        {
            return response()->json([
                'data'  =>  true,
            ],200);
        }
        return response()->json([
            'error' => [
                'message'   =>  Message::processError,
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
        //
    }
}
