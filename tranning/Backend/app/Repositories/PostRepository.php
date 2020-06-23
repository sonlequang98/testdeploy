<?php

namespace Backend\Repositories;

use Backend\Post;
use Backend\Repositories\SettingRepositoryInterface;
use DB;
use Illuminate\Support\Facades\Auth;

class PostRepository implements PostRepositoryInterface
{
    private $setting;
    public function __construct(SettingRepositoryInterface $setting)
    {
        $this->setting = $setting->postPage();
    }
    public function get($post_id)
    {
        return Post::with('release_number')->where('id', $post_id)->first();
    }

    public function all()
    {
        return DB::table('posts as p')
            ->join('release_numbers as r', 'p.release_number_id', '=', 'r.id')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->join('categories as child', 'p.category_id', '=', 'child.id')
            ->join('categories as parent', 'p.category_parent_id', '=', 'parent.id')
            ->join('images as img', 'p.image_id', '=', 'img.id')
            ->orderBy('created_at','desc')
            ->select('p.id', 'p.title', 'u.name as poster', 'p.updated_at', 'p.status', 'r.release_number_name', 'p.created_at', 'p.content', 'child.category_name as category_child', 'parent.category_name as category_parent'
                , 'img.upload_path')->paginate($this->setting);
    }
    public function delete($post_id)
    {
        $post = Post::find($post_id);
        return $post->delete();
    }
    public function update($post_id, $post_data)
    {
        return Post::where('id', $post_id)->update([
            'title' => $post_data->title,
            'category_id' => $post_data->category_id,
            'category_parent_id' =>  $post_data->category_parent_id,
            'content' => $post_data->content,
            'image_id' => $post_data->image_id,
            'release_number_id' => $post_data->release_number_id,
            'status' => $post_data->status,
        ]);
    }

    public function create($post_data)
    {
        $post = new Post();
        $post->title = $post_data->title;
        $post->content = $post_data->content;
        $post->status = $post_data->status;
        $post->category_id = $post_data->category_id;
        $post->category_parent_id = $post_data->category_parent_id;
        $post->release_number_id = $post_data->release_number_id;
        $post->image_id = $post_data->image_id;
        $post->user_id = $post_data->poster;
        if ($post->save()) {
            return true;
        }
        return false;
    }

    public function search($query)
    {
        $result =  DB::table('posts as p')
            ->join('release_numbers as r', 'p.release_number_id', '=', 'r.id')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->join('categories as child', 'p.category_id', '=', 'child.id')
            ->join('categories as parent', 'p.category_parent_id', '=', 'parent.id')
            ->join('images as img', 'p.image_id', '=', 'img.id')
            ->orderBy('created_at','desc')
            ->where('p.release_number_id', 'LIKE', "%$query->releaseNumber%")
            ->where('p.title', 'LIKE', "%$query->keyWord%");
        if(!empty($query->catParent))
        {
            $result->where('p.category_parent_id', '=', $query->catParent);
            if(!empty($query->catChild))
                {
                    $result->where('p.category_id', 'LIKE', "%$query->catChild%");
                }
        }
        if(!empty($query->status))
        {
            $result->where('p.status', '=', $query->status);
        }
        return $result->select('p.id', 'p.title', 'u.name as poster', 'p.updated_at', 'p.status', 'r.release_number_name', 'p.created_at', 'p.content', 'child.category_name as category_child',
         'parent.category_name as category_parent', 'img.upload_path')
            ->paginate($this->setting)
            ->appends(array('status' => $query->status, 'releaseNumber' => $query->releaseNumber, 'catParent' => $query->catParent, 'catChild' => $query->catChild, 'keyWord' => $query->keyWord));
    }

}
