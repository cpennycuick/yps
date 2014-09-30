<?php

abstract class Tool {

	private static $started = [];

	final protected function __construct() {}

	public static function start() {
		$className = get_called_class();
		if (isset(self::$started[$className])) {
			throw new Exception('Already started.');
		}

		$reflectionClass  = new ReflectionClass($className) ;
		$constructor = $reflectionClass->getConstructor() ;

		$class = $reflectionClass->newInstanceWithoutConstructor() ;

		$constructor->setAccessible(true) ;
		$constructor->invokeArgs($class, func_get_args());

		return $class;
	}

}
