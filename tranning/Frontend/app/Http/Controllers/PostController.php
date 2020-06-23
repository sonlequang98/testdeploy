<?php

namespace Frontend\Http\Controllers;

use Illuminate\Http\Request;
use Frontend\Repositories\PostRepository;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $post;
    public function __construct(PostRepository $post)
    {
        $this->post = $post;
    }
    public function index()
    {
        return $this->post->fetchData();
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
        $post = $this->post->show($id);
        return view('post.post')->with(compact('post'));
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
        //
    }
    public function getPostsByCategoryId($id)
    {
        return $this->post->getPostsByCategoryId($id);
    }
    public function getPostById($id)
    {
        return $this->post->show($id);
    }

    // return view search with param search-value
    public function search(Request $request)
    {
        $searchQuery = $request->searchQuery;
        return view('post.searchPost')->with(compact('searchQuery'));
    }
    //get result search from api
    public function getSearchResult(Request $request)
    {
        return $this->post->search($request);
    }

}
