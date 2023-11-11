<?php

use App\System\Router;
use App\Controller\UserController;
use App\Controller\AccessControlController;



Router::post("api/user-registration", [AccessControlController::class, "userRegistration"]);
Router::post("api/login", [AccessControlController::class, "login"]);
Router::get("api/secure-route", [AccessControlController::class, "secureRoute"]);

Router::get("api/users", [UserController::class, "index"]);
Router::post("api/users", [UserController::class, "store"]);
Router::get("api/users/{id}", [UserController::class, "show"]);
Router::put("api/users/{id}", [UserController::class, "update"]);
Router::delete("api/users/{id}", [UserController::class, "destroy"]);


Router::notFound(function () {
	echo "Url Not Found";
});
