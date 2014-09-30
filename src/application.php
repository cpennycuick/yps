<?php

include __DIR__.'/../framework/setup.php';

class Application extends Tool {

	private $errorHandler;
	private $classLoader;
	private $database;
	private $response;
	private $router;

	public function init() {
		$this->initResponse();
		$this->initErrorHandler();

		return $this;
	}

	private function initResponse() {
		$this->response = HTTPResponse::start();

		$responseJSON = preg_match('#\bapplication/json\b#i', $_SERVER['HTTP_ACCEPT']);
		$this->response->initOutputDataFunction(function ($data) use ($responseJSON) {
			if (!$responseJSON and empty($data['Payload']) and empty($data['Error'])) {
				return null;
			}
			return $data;
		});
		$this->response->initOutputResponseFunction(function ($response) use ($responseJSON) {
			return ($responseJSON ? $response : "<pre>{$response}</pre>");
		});

		$this->response->initOutputFormat($responseJSON ? HTTPResponse::FORMAT_JSON : HTTPResponse::FORMAT_DEBUG);
	}

	private function initErrorHandler() {
		$this->errorHandler = ErrorHandler::start()
			->initErrorHandler()
			->initShutdownHook(function ($code, $message, $file) {
				$errorMessage = "Error #{$code}: {$message}\n{$file}";

				error_log($errorMessage);

				echo json_encode([
					'Code' => 400,
					'Error' => $errorMessage,
				]);
			})
			->initExceptionHandler(
				Exception::class,
				function ($e) {
					error_log($e->getMessage()."\nTrace: ".$e->getTraceAsString());

					$this->response->setDataValue('Code', 400);
					$this->response->setDataValue('Error', $e->getMessage());
					$this->response->setDataValue('Trace', $e->getTraceAsString());

					$this->output();
				}
			);
	}

	public function run() {
		$this->errorHandler->run(function () {
			$this->setup();
			$this->doRun();
			$this->output();
		});
	}

	private function setup() {
		$this->classLoader = ClassLoader::start()
			->init('/home/ubuntu/workspace/yps');

		$this->classLoader->add('\\YP\\*', function ($class) {
			$path = str_replace('\\', '/', $class);
			$path = substr($path, 4);
			$path = strtolower($path);
			$path = "/src/php/{$path}.php";
			//echo "{$class}: {$path}\n";

			return $path;
		});

		$this->database = DataBase::start();
		$this->database->openConnection('mysql:dbname=yps;host=0.0.0.0', 'chrispennycuick', '');
		\YP\Addin\DataBase::$_connection = $this->database->getConnection();

		$this->router = Router::start();
		\YP\Tool\ApplicationRouter::start()->init($this->router);
	}

	private function doRun() {
		$match = $this->router->match($_GET['path']);
		$controllerName = array_shift($match);
		$action = array_shift($match);
		$data = array_shift($match);

		$controllerClass = '\YP\Controller\\'.$controllerName;
		if (!class_exists($controllerClass)) {
			throw new Exception("Class '{$controllerClass}' not found.");
		}

		$controller = new $controllerClass();
		$this->response->setDataValue('Payload', $controller->$action());

		$this->response->setDataValue('Code', 200);
	}

	private function output() {
		$this->response->flushHeaders();
		echo $this->response->generateOutput();
	}

}
