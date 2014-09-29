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
		$this->setupCategoryController();
	}
	
	private function setupRootController() {
		$this->router->register('', function () {
			return ['Index', 'run'];
		});
	}
	
	private function setupBasicController() {
		$this->router->register('(?<Controller>[a-zA-Z]{2,})(/(?<Action>[a-zA-Z]+))?', function ($matches) {
			$controller = $matches['Controller'] ?: 'Index';
			$action = (isset($matches['Action']) ? $matches['Action'] : 'run');
			
			return [$controller, $action];
		});
	}
	
	private function setupCategoryController() {
		$this->router->register('c/(?<Category>[a-zA-Z]+)(/(?<SubCategory>[a-zA-Z]+))?', function ($matches) {
			$data = [
				'Category' => $matches['Category'],
			];
			if (isset($matches['SubCategory'])) {
				$data['SubCategory'] = $matches['SubCategory'];
			}
			
			return ['Browse', 'category', $data];
		});
	}
	
}
