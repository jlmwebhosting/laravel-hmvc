<?php namespace HMVC;

class HMVC {
	
	public static function __callStatic($method, $arguments)
	{
		$uri = $arguments[0];
		$action = array('uses' => $arguments[1]);
		$parameters = isset($arguments[2]) ? $arguments[2] : array();

		$route = new \Route(strtoupper($method), $uri, $action, $parameters);

		// Inject false input data
		if ( ! empty($arguments[3]))
		{
			\Input::merge($arguments[3]);
		}

		return $route->call();
	}
}