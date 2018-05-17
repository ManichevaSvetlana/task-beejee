<?php

class Route
{
    public $controller;
    public $action;

	static function run()
	{
		$controllerName = 'Main';
		$actionName = 'index';

		$routes = explode('/', $_SERVER['REQUEST_URI']);

		if (!empty($routes[1]))$controllerName = $routes[1];

		if (!empty($routes[2]))$actionName = $routes[2];


		$controllerName = $controllerName.'Controller';
		$controllerFile = mb_strtolower($controllerName).'.php';
		$controller_path = "app/controllers/".$controllerFile;
		if(file_exists($controller_path)) include "app/controllers/".$controllerFile;
		else Route::ErrorPage404();

		$controller = new $controllerName;
		$action = $actionName;

		if(method_exists($controller, $action)) $controller->$action();
		else Route::ErrorPage404();

	}

	function ErrorPage404()
	{
       echo 'Status: 404 Not Found';
    }

}
