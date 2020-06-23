<?php

namespace Backend\Repositories;

use Backend\Category;
use Backend\Post;
use DB;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function get($category_id)
    {
        return Category::find($category_id);
    }

    public function all()
    {
        return Category::all();
    }

    public function delete($category_id)
    {
        $category = Category::where('id',$category_id)->first();
        $listParentsHaveChild = Category::select('parent_id')->whereNotNull('parent_id')->distinct()->get();
        foreach ($listParentsHaveChild as $item) {
            if ($category->id == $item->parent_id) {
                return false;
            }
        }
        $posts = Post::where('category_id', $category_id)->get();
        foreach ($posts as $post) {
            $post->delete();
        }
        return $category->delete();
    }

    public function update($category_id, $category_data)
    {

    }

    public function create($category_data)
    {
        $category = new Category();
        $category->category_name = $category_data->category_name;
        $category->global_display = $category_data->global_display;
        $category->menu_display = $category_data->menu_display;
        if (!empty($category_data->parent_id)) {
            $category->parent_id = $category_data->parent_id;
        }
        if ($category->save()) {
            return true;
        }
        return false;
    }

    public function getParentCategory()
    {
        return DB::table('categories')->whereNull('parent_id')->get();
    }

    public function getChildCategory($parent_id)
    {
        return Category::where('parent_id', $parent_id)->get();
    }

    public function updateParent($category_id, $category_data)
    {
        return Category::where('id', $category_id, $category_data)->update([
            'category_name' => $category_data->category_name,
            'global_display' => $category_data->global_display,
            'menu_display' => $category_data->menu_display,
        ]);
    }

    public function updateChildrent($category_id, $category_data)
    {
        return Category::where('id', $category_id, $category_data)->update([
            'category_name' => $category_data->category_name,
            'parent_id' => $category_data->parent_id,
            'global_display' => $category_data->global_display,
            'menu_display' => $category_data->menu_display,
        ]);
    }

    public function getCatParentHaveChild()
    {
        $listParentsHaveChild = Category::select('parent_id')->whereNotNull('parent_id')->distinct()->get();
        return Category::whereIn('id', $listParentsHaveChild)->get();
    }
}
