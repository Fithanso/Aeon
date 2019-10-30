<?php

namespace Vendor\Aeon\Kernel;

use Vendor\Aeon\Database\Db;

abstract class Model {

	public $assoc_table;
    public $db;

    public function __construct() {
        $this->db = new Db;
    }

	public function save($params=[]) {

		if(isset($this->assoc_table)) {
		 $table = $this->assoc_table;
			foreach($params as $key=>$val) {
				$str = ':'.$key.',';
				$values[] = $str;
			}
			$values = implode($values);
			if ($values{strlen($values)-1} == ',') {   //если на конце оставить, не сработает pdo
				$values = substr( $values, 0, - 1 );
			}
		 $sql = 'INSERT INTO `'.$table.'` VALUES(NULL,'.$values.')';

			$db = new Db;
		 $query = $db->query($sql,$params);

		}

	}

	public function all() {
    	$sql = 'SELECT * FROM '.$this->assoc_table.'';
		$db = new Db;
		return $db->row($sql);
	}

	public function update($params) {
    	$id = $params['id'];
    	unset($params['id']);//удаляем id, т.к. первичный ключ изменять нельзя
		foreach($params as $key=>$val) {
			$str = $key.' = :'.$key.', ';
			$values[] = $str;
		}
		$values = implode($values);
		$values = trim($values);

		if ($values{strlen($values)-1} == ',') {   //если на конце оставить, не сработает pdo
			$values = substr( $values, 0, - 1 );
		}

		$sql = 'UPDATE works SET '.$values.' WHERE id ='.$id;

		$db = new Db;
		$query = $db->query($sql,$params);
	}

	public function delete($id) {
		$sql = 'DELETE FROM works WHERE id = :id';
		$params = [
			'id' => $id
		];
		$db = new Db;
		$db->query($sql,$params);
	}
	/*найти что то одно по id*/
	public function find($id) {
    	$sql = 'SELECT * FROM '.$this->assoc_table.' WHERE id = :id';
    	$params = [
    		'id' => $id
	    ];
		$db = new Db;
    	return $db->row($sql,$params);
	}


}