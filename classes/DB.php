<?php 

class DB
{
	private static $_instance = null;
	private $_pdo, 
			$_query, 
			$_error = false, 
			$_results,
			$_count = 0;

	private function __construct () 
	{
		try {
			// $this->_pdo = new PDO('mysql:host=localhost;dbname=opps', 'root', 'password');

			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));

			$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    		// echo "Connected successfully";
		} 
		catch(PDOException $e) {
			die($e->getMessage());
		}
	}

	public static function getInstance() 
	{
		if(!isset(self::$_instance)) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}

	public function query($sql, $params = []) 
	{
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)) {
			if(count($params)) {
				$i = 1;
				foreach ($params as $param) {
					$this->_query->bindValue($i, $param);
					$i++;
				}
			}
			if($this->_query->execute()) {
				try {
					$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
					$this->_count = $this->_query->rowCount();
				} catch(Exception $e){};
			}
			else {
				$this->_error = true;
			}
		}
		return $this;
	}

	private function action($action, $table, $params = [])
	{
		if(count($params) === 3) {
			$operators = ['=', '>', '<', '>=', '<=', 'LIKE'];

			$field		=	$params[0];	
			$operator	=	$params[1];	
			$value		=	$params[2];

			if(in_array($operator, $operators)) {
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

				if(!$this->query($sql, [$value])->error()) {
					return $this;
				}
			}	
		}
		return false;
	}

	public function insert($table, $fields = [])
	{
		if(count($fields)) {
			$keys = array_keys($fields);
			$values = null;
			$i = 1;
			foreach ($fields as $field) {
				$values .= '?';
				if($i < count($fields)) {
					$values .= ",";
				}
				$i++;
			}

			// $sql = "INSERT INTO table (`f1`, `f2`, `f3`) VALUES (?, ?, ?)";
			$sql = "INSERT INTO {$table} (`" . implode('`,`', $keys) . "`) VALUES ({$values})";
			// echo $sql;

			if(!$this->query($sql, $fields)->error()) {
				return true;
			}
		} 
		return false;
	}

	public function update($table, $id, $fields)
	{
		if(count($fields)) {
			$set = '';
			$i = 1;

			foreach ($fields as $key => $value) {
				$set .= "`{$key}` = ?";
				if($i < count($fields)) {
					$set .= ',';
				}
				$i++;
			}

			// $sql = "UPDATE table SET f1='val2', f2='val2' WHERE id = 1";
			$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
			
			if(!$this->query($sql, $fields)->error()) {
				return true;
			}
		} 
		return false;
	}

	public function get($table, $where)
	{
		$sql = "SELECT *";
		return $this->action($sql, $table, $where); 
	}

	public function delete($table, $where)
	{
		$sql = "DELETE ";
		return $this->action($sql, $table, $where);
	}

	public function count() 
	{
		return $this->_count;
	}

	public function results() 
	{
		return $this->_results;
	}

	public function first() 
	{
		if($this->_count)
			return $this->_results[0];
		else
			return false;
	}

	public function error() 
	{
		return $this->_error;
	}
}