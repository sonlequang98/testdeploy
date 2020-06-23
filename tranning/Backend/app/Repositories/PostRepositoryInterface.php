<?php 

namespace Backend\Repositories;

interface PostRepositoryInterface
{
	public function get($post_id);
	public function all();
	public function delete($post_id);
	public function update($post_id,$post_data);
	public function create($post_data);
	public function search($query);
}

