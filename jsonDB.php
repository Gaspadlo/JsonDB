<?php

/**
 * Based on Straussn's JSON-Databaseclass.
 * Handle JSON-Files like a very, very simple DB. Useful for little ajax applications.
 * Last change: 03.June-2016
 * Version: 1.1a
 * Originally by: Manuel Strauss
 * Edited by: Adam Kurek www.aKurek.cz
 */
class JsonDB
{
	/**
	 * @var string
	 */
	protected $path = './';
	/**
	 * @var string
	 */
	protected $file_ext = '.json';
	/**
	 * @var array
	 */
	protected $tables = [];

	/**
	 * Construct the object and set the base path for databases.
	 *
	 * @param $path
	 * @throws Exception
	 */
	public function __construct($path) {
		if (!is_dir($path)) {
			throw new Exception("JsonDB Error: Database not found.");
		}
		/*$this->path = rtrim($path, '/');*/
		$this->path = $path;
	}

	/**
	 * Get table instance if exists or create new one.
	 *
	 * @param $table
	 * @param $create
	 * @return mixed
	 */
	protected function getTableInstance($table, $create) {
		if (!isset($this->tables[$table])) {
			$this->tables[$table] = new JsonTable($this->path . $table, $create);
		}
		return $this->tables[$table];
	}

	/**
	 * Magic call for JsonTable class.
	 *
	 * @param $op
	 * @param $args
	 * @return mixed
	 * @throws Exception
	 */
	public function __call($op, $args) {
		if ($args and method_exists('JsonTable', $op)) {
			$table = $args[0] . $this->file_ext;
			$create = false;
			if ($op == 'createTable') {
				return $this->getTableInstance($table, true);
			} elseif ($op == 'insert' and isset($args[2]) and $args[2] === true) {
				$create = true;
			}
			return $this->getTableInstance($table, $create)->$op($args);
		} else {
			throw new Exception("JsonDB Error: Unknown method or wrong arguments.");
		}
	}

	/**
	 * Set extension for json file.
	 *
	 * @param $file_ext
	 * @return $this
	 */
	public function setExtension($file_ext) {
		$this->file_ext = $file_ext;
		return $this;
	}
}

?>