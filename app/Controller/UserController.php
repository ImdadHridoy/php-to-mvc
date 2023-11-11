<?php
namespace App\Controller;
use App\Models\User;

class UserController 
{
	public function index()
	{
		$result = User::read('users');
		echo json_encode([
			"status" => true,
			"status_code" => 200,
    		"message" => "Data Fetch Success.",
			"data" => $result
		]);
	}

	public function store($data = null)
	{
		$status = User::insert('users', post());
		if ($status === true) {
			echo "Insert Success";
		}else{
			echo $status;
		}
	}


	public function show($id = null)
	{
		$result = User::read_one('users', ['id' => $id]);
		echo json_encode([
			"status" => true,
			"status_code" => 200,
    		"message" => "Data Fetch Success.",
			"data" => $result
		]);
	}

	public function update($id)
	{
		$status = User::update('users', post(), ['id' => $id]);
		if ($status === true) {
			echo "Update Success";
		}else{
			echo $status;
		}
	}


	public function destroy($id)
	{
		$status = User::delete('users', ['id' => $id]);
		if ($status === true) {
			echo "Delete Success";
		}else{
			echo $status;
		}
	}


}

?>