<?php

namespace Backend\Repositories;

interface CategoryRepositoryInterface
{
    public function get($category_id);
    public function all();
    public function delete($category_id);
    public function update($category_id, $category_data);
    public function create($category_data);
    public function getParentCategory();
    public function getChildCategory($parent_id);
    public function updateParent($category_id, $category_data);
    public function updateChildrent($category_id, $category_data);
    public function getCatParentHaveChild();
}
