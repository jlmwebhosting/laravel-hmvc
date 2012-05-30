<?php namespace HMVC; use \URI, \Router;

class HMVC {
	
	protected static $routes = array();

	public static $main_uri;

	public static function __callStatic($method, $arguments)
	{
		if ( ! empty(static::$routes) && isset(static::route()->$method))
		{
			return static::route()->$method;
		}

		return static::call_hmvc($method, $arguments);
	}

	public static function call_hmvc($method, $arguments)
	{
		array_unshift($arguments, 'test/uri');
		if ( ! $uri = Router::uses($arguments[1]))
		{
			$uri = str_replace(array('::', '@'), '/', $arguments[1]);
		}
		$action = array('uses' => $arguments[1]);
		$parameters = isset($arguments[2]) ? $arguments[2] : array();

		$route = static::$routes[] = new \Route(strtoupper($method), $uri, $action, $parameters);

		// Inject false input data
		if ( ! empty($arguments[3]))
		{
			\Input::merge($arguments[3]);
		}

		// Temporarily mess with URI::current()
		$old_uri = URI::$uri;
		URI::$uri = $uri;
		(count(static::$routes) == 1) && static::$main_uri = URI::$uri;

		$result = $route->call();

		// Put things back in place
		URI::$uri = $old_uri;

		array_pop(static::$routes);
		count(static::$routes) || static::$main_uri = null;

		return $result;
	}

	public static function route()
	{
		return end(static::$routes);
	}

	public static function active()
	{
		return ! empty(static::$routes);
	}
}