<?php

namespace Vendor\Aeon\Kernel;

class AppContainer {

	private $classes = [];
	private $controllers = [];
	private $models = [];
	private $serviceProviders = [];

	public function __construct() {
		$this->classes = $_SESSION['loaded_classes'];
	}

	public function sortClasses() {

	 foreach($this->classes as $class) {

	 $parent = class_parents($class);	//просто получаем родителей

	 if(!empty($parent)) {
	    $this->controllers[] = $parent;

       }
	 }
	 return $this->controllers;

	}
}