<?php   

namespace Frontend\Repositories;

use Frontend\Post;

class PostRepository
{
    public function getFourNewPost()
	{
		return Post::with('image','release_number')->orderBy('created_at','desc')->where('status','=','public')->paginate(4);
	}
	public function fetchData()
	{
		return Post::with('image')->orderBy('created_at','desc')->where('status','=','public')->paginate(8);
	}
	public function getPostsByCategoryId($id)
	{
		return Post::with('image')->orWhere('category_id',$id)->where('status','=','public')->orWhere('category_parent_id',$id)->paginate(8);
	}
	public function show($id)
	{
		return Post::with('user','release_number')->where('id',$id)->first();
	}
	public function search($query)
	{
		return Post::with('image')->where('title','LIKE',"%$query->searchQuery%")->where('status','=','public')->paginate(8)->appends(array('searchQuery'	=>	$query->searchQuey));
	}
}

