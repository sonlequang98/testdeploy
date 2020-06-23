<?php

namespace Backend\Repositories;

use Backend\Post;
use Backend\Release_number;
use Backend\Repositories\SettingRepositoryInterface;

class ReleaseNumberRepository implements ReleaseNumberRepositoryInterface
{
    private $setting;
    public function __construct(SettingRepositoryInterface $setting)
    {
        $this->setting = $setting->releaseNumberPage();
    }
    public function get($release_number_id)
    {
        return Release_number::with('image')->where('id', $release_number_id)->first();
    }

    public function all()
    {
        return Release_number::with('image')->orderBy('created_at','desc')->paginate($this->setting);
    }

    public function delete($release_number_id)
    {
        $release_number = Release_number::find($release_number_id);
        $posts = Post::where('release_number_id', $release_number_id)->get();
        foreach ($posts as $post) {
            $post->delete();
        }
        return $release_number->delete();
    }

    public function update($release_number_id, $release_number_data)
    {
        return Release_number::where('id', $release_number_id)->update([
            'release_number_name' => $release_number_data->release_number_name,
            'release_number_description' => $release_number_data->release_number_description,
            'image_id' => $release_number_data->image_id,
        ]);
    }

    public function create($release_number_data)
    {
        $release_number = new Release_number();
        $release_number->release_number_name = $release_number_data->release_number_name;
        $release_number->release_number_description = $release_number_data->release_number_description;
        $release_number->image_id = $release_number_data->image;
        if ($release_number->save()) {
            return true;
        }
        return false;
    }
    public function getAll()
    {
        return Release_number::latest()->get();
    }

}
