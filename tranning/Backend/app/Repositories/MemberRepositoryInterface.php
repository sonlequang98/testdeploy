<?php 

namespace Backend\Repositories;

interface MemberRepositoryInterface
{
	public function get($member);
	public function all();
	public function delete($member);
	public function update($member_id,$member_data);
	public function create($member_data);
	public function search($query);
}

