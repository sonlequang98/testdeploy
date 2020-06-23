<?php

namespace Frontend\Http\Controllers;

use Frontend\Repositories\PostRepository;
use Frontend\Release_number;
use Illuminate\Http\Request;

class UserSideController extends Controller
{
    protected $post;
    public function __construct(PostRepository $post)
    {
        $this->post = $post;
    }
    public function index()
    {
        $releaseNumbers = Release_number::with('image')->orderBy('created_at','desc')->paginate(5);
        $listPost = $this->post->getFourNewPost();
        return  view('index',compact('listPost','releaseNumbers'));
    }
    
}
