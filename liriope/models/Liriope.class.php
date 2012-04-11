<?php
// --------------------------------------------------
// Liriope.class.php
// --------------------------------------------------

// What is this class used for? It's worthless at the moment!

class Liriope extends SQLQuery {
	protected $_model;

	function __construct() {
		$this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		$this->_model = get_class($this);
		$this->_table = strtolower($this->_model)."s";
	}

	function __destruct() {
	}
}

