<?php

namespace App\System;

class Router
{
	private static $notFound = true;

	public $db = null;

	private static function getUrl()
	{
		return $_SERVER["REQUEST_URI"];
	}


	private static function processPattern($pattern)
	{
		$pattern = preg_replace("/{(\w+)}/i", '(\w+)', $pattern);
		if (preg_match("~^/?{$pattern}/?$~", self::getUrl(), $matches)) {
			return $matches;
		}
		return null;
	}

	public static function process($pattern, $callback)
	{
		$matches = self::processPattern($pattern);
		if ($matches) {
			$callBackParam = array_slice($matches, 1);
			self::$notFound = false;
			if (is_callable($callback) || is_array($callback)) {
				if (is_array($callback)) {
					$className = $callback[0];
					$methodName = $callback[1];
					(new $className)->$methodName(...$callBackParam);
				} else {
					$callback(...$callBackParam);
				}
			}
		}
	}

	public static function get($pattern, $callback)
	{
		if ($_SERVER["REQUEST_METHOD"] != "GET") {
			return null;
		}
		self::process($pattern, $callback);
	}

	public static function post($pattern, $callback)
	{
		if ($_SERVER["REQUEST_METHOD"] != "POST") {
			return null;
		}
		self::process($pattern, $callback);
	}

	public static function put($pattern, $callback)
	{
		if ($_SERVER["REQUEST_METHOD"] != "PUT") {
			return null;
		}
		self::process($pattern, $callback);
	}

	public static function delete($pattern, $callback)
	{
		if ($_SERVER["REQUEST_METHOD"] != "DELETE") {
			return null;
		}
		self::process($pattern, $callback);
	}

	public static function notFound($callback)
	{
		if (self::$notFound) {
			if (is_callable($callback)) {
				$callback();
			}
		}
	}
}
