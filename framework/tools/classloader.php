<?php

class ClassLoader extends Tool {
	
	private $loaders = [];
	
	public function init($basePath) {
		spl_autoload_register(function ($class) use($basePath) {
			$matches = [];
			$class = '\\'.$class;
			foreach ($this->loaders as $loader) {
				list($classNameMatch, $callback) = $loader;
				if (preg_match("/^{$classNameMatch}$/i", $class, $matches)) {
					//echo "/^{$classNameMatch}$/i\n";
					$path = $callback($class);

					if (file_exists($basePath.$path)) {
						include $basePath.$path;
						//echo "{$class} loaded\n";
						return true;
					}
				}
			}

			return false;
		});
		return $this;
	}
	
	public function add($classNameMatch, $callback) {
		$classNameMatch = preg_replace(['/[^a-z0-9\\\*]/i', '/\\\\/', '/\*/'], ['', '\\\\\\', '([a-z0-9\\\\\\]+)'], $classNameMatch);
		$this->loaders[] = [$classNameMatch, $callback];
	}
	
}
