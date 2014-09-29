<?php

class ErrorHandler extends Tool {
	
	private $handlers = [];
	
	public function initExceptionHandler($exception, $fn) {
		$this->handlers[] = [$exception, $fn];
		return $this;
	}
	
	public function initErrorHandler() {
		set_error_handler(function($num, $str, $file, $line, $context = null) {
		    throw new ErrorException($str, 0, $num, $file, $line);
		});
		return $this;
	}
	
	public function initShutdownHook($fn) {
		register_shutdown_function(function () use ($fn) {
			$error = error_get_last();
			if ($error) {
				$fn($error['code'], $error['message'], $error['file']);
			}
		});
		return $this;
	}
	
	public function run($fn) {
		try {
			$fn();
		} catch (\Exception $e) {
			foreach ($this->handlers as $handler) {
				list($exception, $fn) = $handler;
				if ($e instanceof $exception) {
					$fn($e);
					return;
				}
			}
			
			throw $e;
		}
	}
	
}
