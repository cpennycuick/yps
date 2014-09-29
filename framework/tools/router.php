<?php

class Router extends Tool {
	
	private $map = [];
	
	public function register($path, $action) {
		$pathPattern = $this->convertToRegExp($path);
		$this->map[$pathPattern] = $action;
	}

	private function convertToRegExp($path) {
		return '#^/'.$path.'/?$#';
	}

	public function match($path) {
		$matches = [];
		foreach ($this->map as $pathPattern => $action) {
			if (preg_match($pathPattern, $path, $matches)) {
				return $action($matches);
			}
		}

		echo "<pre>Route not found for '$path'\n";
		print_r(array_keys($this->map));
		return [null, null];
	}
	
}
