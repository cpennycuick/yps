<?php

class Router extends Tool {

	private $map = [];

	public function register($path, $action) {
		$pathPattern = $this->convertToRegExp($path);
		$this->map[$pathPattern] = $action;
	}

	private function convertToRegExp($path) {
		$path = preg_replace('/:([a-z]+)/i', '(?<$1>[a-zA-Z]+)', $path);
		return '#^/'.$path.'/?$#';
	}

	public function match($path) {
		$matches = [];
		foreach ($this->map as $pathPattern => $action) {
			if (preg_match($pathPattern, $path, $matches)) {
				$data = [];
				foreach ($matches as $key => $value) {
					if (!is_int($key)) {
						$data[$key] = $value;
					}
				}

				return $action($data);
			}
		}

		echo "<pre>Route not found for '$path'\n";
		print_r(array_keys($this->map));
		return [null, null];
	}

}
