<?php 

namespace Backend\Repositories;

interface UserAdminRepositoryInterface
{
	public function get($user);
	public function all();
	public function delete($user);
	public function update($user,$user_data);
	public function create($user_data);
	public function getRoles();
	public function searchUser($query);
}

