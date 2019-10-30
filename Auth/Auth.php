<?php

namespace Vendor\Aeon\Auth;

use Vendor\Aeon\Database\Db;

 class Auth {

 	/*This function checks if user can enter as admin
 	*/

	public function validateAdmin($data = []) {
		
		foreach($data as $key=>$val) {//применить htmlspecialchars ко всему
			unset($data[$key]);
			$key = htmlspecialchars($key);
			$val = htmlspecialchars($val);
			$data[$key] = $val;
		}

		if(empty($data) && !empty($_SESSION['admin']))
			return true;

		if(!empty($data)) {
			if ( empty($_SESSION['admin'])) {

				$unique = $this->checkDbUnique( $data, 'admin' );

				if ( $unique ) {
					$_SESSION['admin'] = $unique;
					return true;
				} else {
					return false;// если сессия не существует и админ с такими данными не найден
				}

			} else {
				return true;
			}
		}
	}

	/*
	*This function checks user uniqueness using role and/or other data
	*and in case of success returns user's table entry.
	*/

	private function checkDbUnique($data, $role = '') {
		$db = new Db;
		$sql = 'SELECT * from users WHERE role = :role and email = :email and password = :password';
		$data['role'] = $role;
		$stmt = $db->row($sql,$data);
		
		if (count($stmt) == 1) {
			return $stmt;
		}else{
			return false;
		}
	}
}

// 