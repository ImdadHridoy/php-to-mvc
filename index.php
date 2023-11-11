<?php
require 'vendor/autoload.php';
require "app/helper.php";

use Dotenv\Dotenv;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

require "app/bootstrap.php";
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Max-Age: 86400');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// header('Access-Control-Expose-Headers: x-authorization');
// header('Access-Control-Allow-Headers: origin, content-type, accept, x-authorization');
// header('X-Authorization: '.YOUR_TOKEN_HERE);



/*
200 OK
201 Created
204 No Content
304 Not Modified
400 Bad Request
401 Unauthorized
403 Forbidden
404 Not Found
405 Method Not Allowed
410 Gone
415 Unsupported Media Type
422 Unprocessable Entity
429 Too Many Requests

{
    "success": true,
    "message": "Message here",
    "data": $data,
}
*/