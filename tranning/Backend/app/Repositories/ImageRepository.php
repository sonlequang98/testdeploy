<?php

namespace Backend\Repositories;

use Backend\Image;
use Backend\Post;
use Backend\Release_number;
use Backend\Repositories\SettingRepositoryInterface;
use Backend\Repositories\ReleaseNumberRepository;

class ImageRepository implements ImageRepositoryInterface
{
    private $setting;
    private $releaseNumber;
    public function __construct(SettingRepositoryInterface $setting,ReleaseNumberRepository $releaseNumber )
    {
        $this->setting = $setting->imgPage();
        $this->releaseNumber = $releaseNumber;
    }
    public function get($image_id)
    {
        return Image::find($image_id);
    }

    public function all()
    {
        return Image::orderBy('created_at','desc')->paginate($this->setting);
    }

    public function delete($image_id)
    {
        $posts = Post::where('image_id', $image_id)->get();
        foreach ($posts as $post) {
            Post::destroy($post->id);
        }
        $releaseNumbers = Release_number::where('image_id', $image_id)->get();
        foreach ($releaseNumbers as $releaseNumber) {
            $this->releaseNumber->delete($releaseNumber->id);
        }
        $image = Image::find($image_id);
        return $image->delete();
    }

    public function create($image_data)
    {
        $image = new Image;
        $image->name = $image_data['name'];
        $image->upload_path = $image_data['upload_path'];
        $image->save();
        return $image;
    }

    public function search($query)
    {
        return Image::where('name', 'LIKE', "%$query->name%")->orderBy('created_at','desc')->paginate($this->setting)->appends(array('name' => $query->name));
    }

    public function deleteLastImage($name)
    {
        $image = Image::where('name','=',$name)->orderBy('created_at','desc')->first();
        $res = $image->upload_path;
        $image->delete();
        return $res;
    }

}
