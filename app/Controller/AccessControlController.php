<?php
namespace App\Controller;

use App\Models\User;
use \Firebase\JWT\JWT;

class AccessControlController
{
	private static $instance;

	public static function getInstance()
	{
		if (self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function userRegistration()
	{
		$data = post();
		$data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
		$status = User::insert('users', $data);
		if($status === true){
			echo json_encode([
				"status" => true,
				"status_code" => 201,
	    		"message" => "User Insert Success.",
				"data" => post()
			]);
		}
		else{
			echo json_encode([
				"status" => false,
				"status_code" => 422, // Unprocessable Entity
	    		"message" => $status,
				"data" => post()
			]);
		}
	}

	public function login()
	{
		$result = User::read_one('users', ["email" => post('email')]);
		echo $this->loginProcess(post(), $result);		
	}

	public function loginProcess($data, $result)
	{
		if(is_object($result)){
			$id = $result->id;
			$name = $result->name;
			$email = $result->email;
			$hashPassword = $result->password;
			if(password_verify($data['password'], $hashPassword))
			{
				$secret_key = "HelloImdad";
				$issuer_claim = "THE_ISSUER"; // this can be the servername
				$audience_claim = "THE_AUDIENCE";
				$issuedat_claim = time(); // issued at
				$notbefore_claim = $issuedat_claim + 10; //not before in seconds
				$expire_claim = $issuedat_claim + 3600; // expire time in seconds
				$token = array(
					"iss" => $issuer_claim,
					"aud" => $audience_claim,
					"iat" => $issuedat_claim,
					"nbf" => $notbefore_claim,
					"exp" => $expire_claim,
					"data" => [
						"id" => $result->id,
						"name" => $result->name,
						"email" => $result->email,
				]);
				$jwt = JWT::encode($token, $secret_key);

				return json_encode([
					"status" => true,
					"status_code" => 200,
		    		"message" => "Login Successful",
					"data" => [
						"token" => $jwt,
						"expire_at" => $expire_claim,
					]
				]);
			} else {
				return json_encode([
					"status" => true,
					"status_code" => 200,
		    		"message" => "Invalid Password.",
					"data" => [
						"token" => $jwt,
						"expire_at" => $expire_claim,
					]
				]);
			}
		}
		return json_encode([
			"status" => true,
			"status_code" => 200,
    		"message" => "Invalid Email.",
			"data" => [
				"token" => null,
				"expire_at" => null,
			]
		]);
	}

	public function secureRoute()
	{	
		$check = User::verifyToken();		
		if($check){
			echo json_encode(["status_code" => 200, "message" => "Access Granted"]);;
		}else{
			echo json_encode(["status_code" => 200, "message" => "Route Denied."]);;
		}
	}

}