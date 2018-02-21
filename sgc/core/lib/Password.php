<?php
	class Password{
		private $time_target;
		private $cost;

		public function __construct(){
			$this->timeTarget = 0.05;
			$this->setAppropriateCost();
		}

		public function hashPassword($password){
			$hash_options = array(
				'cost' => $this->cost
			);

			return password_hash($password, PASSWORD_BCRYPT, $hash_options);
		}

		private function setAppropriateCost(){
			$this->cost = 8;

			do {
				$this->cost++;
				$start = microtime(true);
				password_hash("test", PASSWORD_BCRYPT, array("cost" => $this->cost));
				$end = microtime(true);
			}while(($end - $start) < $this->time_target);
		}

		public static function match($password, $hash){
			return password_verify($password, $hash);
		}
	}
?>
