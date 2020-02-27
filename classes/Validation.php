<?php 

class Validation
{
	private $_passed = false,
			$_errors = [],
			$_db = null;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	private function addError($field, $error)
	{
		$this->_errors[$field] = $error;
	}

	public function check($source, $fields = [])
	{
		if(count($fields)) {

			foreach ($fields as $field => $rules) {

				foreach ($rules as $rule => $rule_value) {

					$data = trim($source[$field]);

					if($rule === 'required' && empty($data)) {
						$this->addError($field, "{$field} is required");
					}
					else if(!empty($data)) {
						switch ($rule) {
							case 'min':
								if(strlen($data) < $rule_value)
									$this->addError($field, "{$field} must be a minimum of {$rule_value} charectors.");
								break;
							
							case 'max':
								if(strlen($data) > $rule_value)
									$this->addError($field, "{$field} must be a maximum of {$rule_value} charectors.");
								break;

							case 'unique':
								$check = $this->_db->get($rule_value, [$field, '=', $data]);
								if($check->count())
									$this->addError($field, "{$field} already exists.");
								break;

							case 'exists':
								$check = $this->_db->get($rule_value, [$field, '=', $data]);
								if(!$check->count())
									$this->addError($field, "{$field} not exists in our records.");
								break;

							case 'matches':
								if($data != $source[$rule_value])
									$this->addError($rule_value, "{$rule_value} must match {$field}.");
								break;
						}
					}
				}
			}

			if(empty($this->_errors)) {
				$this->_passed = true;
			}
		}
		return $this;
	}

	public function errors()
	{
		return $this->_errors;
	}

	public function passed()
	{
		return $this->_passed;
	}
}