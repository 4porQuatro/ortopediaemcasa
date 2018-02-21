<?php
	class FormManager
	{
		private $mysqli;
		private $table;
		private $columns_arr;
		private $db_values_arr;
		private $validation_exceptions_arr;

		public $posts_arr;
		public $query_fields;

		/**
		 * Constructor
		 * @param MySQLi $mysqli
		 * @param string $table
		 */
		public function	__construct(\MySQLi $mysqli, $table){
			$this->mysqli = $mysqli;
			$this->table = $table;
			$this->columns_arr = $this->posts_arr = $this->db_values_arr = $this->validation_exceptions_arr = array();
			$rs_fields = $this->mysqli->query("SHOW COLUMNS FROM $table;") or die('<h3>' . get_class($this) . '::' . __FUNCTION__ . '</h3><p>An error occurred while fetching the table fields</p>');
			if($rs_fields->num_rows){
				while($column = $rs_fields->fetch_object()){
					$this->columns_arr[$column->Field] = $column;
				}
			}
		}

		/**
		 *	Getters
		 */
		public function getTable(){ return $this->table; }
		public function getColumnsArr(){ return $this->columns_arr; }
		public function getDBValuesArr(){ return $this->db_values_arr; }
		public function getPostsArr(){ return $this->posts_arr; }

		/**
		 * Get entity's primary key
		 * @return string $pk
		 */
		public function getPK(){
			$rs = $this->mysqli->query("SHOW KEYS FROM " . $this->table . " WHERE Key_name = 'PRIMARY'");
			$obj = $rs->fetch_object();

			$pk = $obj->Column_name;

			return $pk;
		}

		/*
		 *	GET VALUES BY INDEX
		 */
		public function getDBValue($key){
			return isset($this->db_values_arr[$key]) ? $this->db_values_arr[$key] : NULL;
		}

		public function getPost($key){
			return (isset($this->posts_arr[$key])) ? $this->posts_arr[$key] : NULL;
		}

		/*
		 *	GET SCOPE VALUE
		 */
		public function getScopeValue($key){
			$value = NULL;
			if(array_key_exists($key, $this->posts_arr)){
				$value = $this->posts_arr[$key];
			}else if(isset($this->db_values_arr[$key])){
				$value = $this->db_values_arr[$key];
			}else if(isset($this->columns_arr[$key])){
				$value = $this->columns_arr[$key]->Default;
			}
			return $value;
		}

		/*
		 *	PRINT VALUE
		 */
		public function output($key){
			return trim(htmlentities($this->getScopeValue($key), ENT_QUOTES | ENT_IGNORE, 'UTF-8'));
		}

		/*
		 *	PRINT MAX LENGTH
		 */
		public function maxlen($key){
			return $this->getColumnLength($this->columns_arr[$key]->Type);
		}

		public function getColumnLength($value){
			$string = ' ' . $value;
		    $ini = strpos($string, '(');
		    if ($ini == 0) return 'this column doesn\'t have max-length';
		    $ini += strlen('(');
		    $len = strpos($string, ')', $ini) - $ini;
		    return substr($string, $ini, $len);
		}

		/*
		 *
		 */
		public function mapDBValues($pk, $pk_id, $language_id = NULL){
			$where = "";
			if($pk_id && !empty($pk_id)){
				$where = " WHERE md5(" . $pk . ") = ?";
				if($language_id && !empty($language_id)){
					$where .= " AND language_id = " . $language_id;
				}
				$stmt = $this->mysqli->prepare("SELECT * FROM " . $this->table . $where . " LIMIT 0, 1");
				$stmt->bind_param("s", $pk_id);
				$stmt->execute() or die('<h3>' . get_class($this) . '::' . __FUNCTION__ . '</h3><p>An error occurred while fetching the record</p>');
				$stmt->store_result();
				if($stmt->num_rows){
					$meta = $stmt->result_metadata();
					$fields = $meta->fetch_fields();
					foreach($fields as $field) {
						$result_values[$field->name] = &$field->name;
					}
					call_user_func_array(array($stmt, 'bind_result'), $result_values);
					$stmt->fetch();
					foreach($result_values as $key=>$value) {
						if(isset($this->columns_arr[$key])){
							$this->db_values_arr[$key] = $value;
						}
					}
				}
			}else{
				die('<h3>' . get_class($this) . '::' . __FUNCTION__ . '</h3><p>Invalid parameters for fetching the record</p>');
			}
		}
		/*
		 *
		 */
		public function mapPosts(){
			foreach($this->columns_arr as $key=>$column){
				if(isset($_POST[$key]) && $_POST[$key] !== ""){
					$this->posts_arr[$key] = $_POST[$key];
				}else{
					$this->posts_arr[$key] = (strtolower($this->columns_arr[$key]->Null) == "no") ? '' : NULL;
				}
			}
			return $this->posts_arr;
		}
		/*
		 *
		 */
		public function addPost($key){
			$this->posts_arr[$key] = (isset($_POST[$key])) ? $_POST[$key] : NULL;
		}
		/*
		 *
		 */
		public function addRequiredException($key){
			if(isset($this->columns_arr[$key])){
				$this->validation_exceptions_arr[$key] = $key;
			}
		}
		/*
		 *
		 */
		public function checkRequiredFields(){
			// check required fields
			foreach($this->columns_arr as $key=>$field){
				if(!in_array($key, $this->validation_exceptions_arr) && isset($_POST[$key]) && $_POST[$key] == "" && $field->Null == "NO"){
					return false;
				}
			}
			return true;
		}

		/**
		 *	This method checks if the value is duplicated
		 *
		 *	@param $field	The column name
		 */
		public function checkUniqueKey($field){
			if($this->getPost($field)){
				$value = $this->getPost($field);
				$stmt = $this->mysqli->prepare("SELECT " . $field . " FROM " . $this->table . " WHERE " . $field . " = ?") or die($this->mysqli->error);
				$stmt->bind_param("s", $value);
				$stmt->execute() or die($stmt->error);
				$stmt->store_result();
				if($stmt->num_rows){
					if(!empty($this->getDBValue($field)) && $this->getDBValue($field) == $value){
						return true;
					}else{
						return false;
					}
				}else{
					return true;
				}
			}else{
				die("The post " . $field . " doesn't exist");
			}
		}


		public function setQueryFields($fields = array()){
			if(sizeof($fields)){
				$this->query_fields = [
					'names' => $this->setQueryFieldsNames($fields),
					'placeholders' => $this->setQueryFieldsPlaceholders($fields),
					'names=placeholders' => $this->setQueryFieldsNamesPlaceholders($fields),
					'types' => $this->setQueryFieldsTypes($fields),
					'posts' => $this->setQueryFieldsPosts($fields),
				];
			}
		}

		private function setQueryFieldsNames($fields){
			$fields_names = array_map(function($string){
				return preg_replace('/(.*)-/', '', $string);
			}, $fields);

			return implode(",", $fields_names);
		}

		private function setQueryFieldsNamesPlaceholders($fields){
			$fields_names = array_map(function($string){
				return preg_replace('/(.*)-/', '', $string) . ' = ?';
			}, $fields);

			return implode(",", $fields_names);
		}

		private function setQueryFieldsTypes($fields){
			$fields_types = array_map(function($string){
				return preg_replace('/-(.+)/', '', $string);
			}, $fields);

			return implode("", $fields_types);
		}

		private function setQueryFieldsPlaceholders($fields){
			$fields_placeholders = array_map(function($string){
				return '?';
			}, $fields);

			return implode(",", $fields_placeholders);
		}

		private function setQueryFieldsPosts($fields){
			$fields_posts = array_map(function($string){
				return preg_replace('/(.*)-/', '', $string);
			}, $fields);

			return $fields_posts;
		}

		public function getQueryFieldsParams(){
			$posts = $this->posts_arr;
			$fields_values = array();

			$fields_posts = array_map(function($field) use($posts){
				return (isset($posts[$field])) ? $posts[$field] : NULL;
			}, $this->query_fields['posts']);

			return $fields_posts;
		}
	}
