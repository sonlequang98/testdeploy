<?php 

namespace Backend\Repositories;

interface ReleaseNumberRepositoryInterface
{
	public function get($release_number_id);
	public function all();
	public function delete($release_number_id);
	public function update($release_number_id,$release_number_data);
	public function create($release_number_data);
	public function getAll();
}

