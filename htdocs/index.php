<?php

include '../src/application.php';

$_POST = json_decode(file_get_contents('php://input'), true) ?: [];

Application::start()
	->init()
	->run();
