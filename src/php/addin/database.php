<?php

namespace YP\Addin;

trait DataBase {
	
	public static $_connection = null;
	
	public function getCon() {
		return self::$_connection;
	}
	
}
