<?php

function get($field = null){
	if (is_null($field)) {
		return $_GET;
	}
	return $_GET[$field];
}

function post($field = null){
	$data = json_decode(file_get_contents('php://input'), true);
	if (is_null($field)) {
		return $data;
	}
	return $data[$field];
}

?>