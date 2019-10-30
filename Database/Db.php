<?php

namespace Vendor\Aeon\Database;

use PDO;

class Db {

    protected $db;//здесь лежит подключение к БД

    public function __construct() {

        $config = require 'Config/app.php';
        //$this->db = new PDO('mysql:host='.$config['db']['host'].';dbname='.$config['db']['name'].'', $config['db']['user'], $config['db']['password']);
	    $options = [
		    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		    PDO::ATTR_EMULATE_PREPARES   => false,
	    ];
	    $dsn = "mysql:host=".$config['db']['host'].";dbname=".$config['db']['name'].";charset=utf8";
	    try {
		    $this->db = new PDO($dsn, "root", "qwerty", $options);
	    } catch (\PDOException $e) {
		    throw new \PDOException($e->getMessage(), (int)$e->getCode());
	    }

    }
    
    public function query($sql, $params = []) {
    	/*echo $sql;
    	echo '<br>';
    	print_r($params);*/
        $stmt = $this->db->prepare($sql);
        if(!empty($params)) {

            foreach($params as $key => $val) {
	            /*echo 'bind <br>';
	            echo ':'.$key.'<br>'.$val.'<br>';*/
                $stmt->bindValue(':'.$key, $val);
            }

        }

        $stmt->execute();

	    return $stmt;

    }

        public function row($sql, $params = []) {// функция позволяет получить выборку в ассоциативном массива по строкам
        $result = $this->query($sql, $params);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    
}

