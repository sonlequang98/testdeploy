<?php

namespace Backend\Repositories;

interface ImageRepositoryInterface
{
    public function get($image_id);
    public function all();
    public function delete($image_id);
    public function create($image_data);
    public function search($query);
    public function deleteLastImage($name);
}
