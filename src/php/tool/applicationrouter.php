<?php

namespace YP\Tool;

class ApplicationRouter extends \Tool {

	/**
	 * Router
	 */
	private $router;

	public function init($router) {
		$this->router = $router;

		$this->setupRootController();
		$this->setupBasicController();
	}

	private function setupRootController() {
		$this->router->register('v1', function () {
			return ['App', 'info'];
		});
	}

	private function setupBasicController() {
		$this->router->register('v1/:Controller(/:Action)?', function ($matches) {
			$controller = $matches['Controller'] ?: 'Index';
			$action = (isset($matches['Action']) ? $matches['Action'] : 'run');

			return [$controller, $action];
		});
	}

}
