<?php

class HTTPResponse extends Tool {
	
	const FORMAT_NONE = 'NONE';
	const FORMAT_DEBUG = 'DEBUG';
	const FORMAT_JSON = 'JSON';
	
	private $outputFormat = self::FORMAT_DEBUG;
	private $outputDataFn = null;
	private $outputResponseFn = null;

	private $header = [];
	private $data = [];
	
	private $headersFlushed = false;
	private $outputed = false;

	public function initOutputFormat($format) {
		$this->outputFormat = $format;
		return $this;
	}
	
	public function initOutputDataFunction($outputDataFn) {
		$this->outputDataFn = $outputDataFn;
		return $this;
	}
	
	public function initOutputResponseFunction($outputResponseFn) {
		$this->outputResponseFn = $outputResponseFn;
		return $this;
	}
	
	public function setHeader($key, $value) {
		$this->header[$key] = $value;
	}
	
	public function setDataValue($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function setData($data) {
		$this->data = array_merge($this->data, $data);
	}
	
	public function flushHeaders() {
		if ($this->headersFlushed) {
			throw new Exception('Headers already flushed.');
		}
		
		$this->headersFlushed = true;
		
		// TODO flush headers
	}
	
	public function generateOutput() {
		if (is_callable($this->outputDataFn)) {
			$data = call_user_func($this->outputDataFn, $this->data);
		} else {
			$data = $this->data;
		}
		
		switch ($this->outputFormat) {
			case self::FORMAT_JSON:
				$response = json_encode($data);
				break;
			case self::FORMAT_NONE:
				$response = null;
				break;
			case self::FORMAT_DEBUG:
				// fallthough
			default:
				$response = print_r($data, true);
		}
		
		if (is_callable($this->outputResponseFn)) {
			return call_user_func($this->outputResponseFn, $response);
		}
		
		return $response;
	}
	
}
