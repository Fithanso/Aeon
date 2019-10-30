<?php

namespace Vendor\Aeon\Kernel;

use Vendor\Aeon\Kernel\View;

use Vendor\Aeon\Language\Language;

use Vendor\Aeon\HtmlGenerator\HtmlGenerator;

abstract class Controller {

    public $route;
    public $view;
    public $model;
    public $acl;
    public $generator;
    public $lang;


    public function __construct($route) {

        $this->generator = new HtmlGenerator;
        $this->lang = Language::lang_detect();

        $this->route = $route;

        if(!$this->checkAcl()) {
            View::errorCode(403);
        }
        $this->view = new View($route);
        $this->model = $this->loadModel($route['controller']);

    }

    public function loadModel($name) {
	    if ($name{strlen($name)-1} == 's') {   //так как модель всегда в ед.числе-обрезать s символ
		    $name = substr($name,0,-1);
		    $path = 'App\Models\\'.ucfirst($name);//полное имя класса

		    if(class_exists($path)) {
			    return new $path;
		    }
	    }

    }


    public function checkAcl() {

        $this->acl = require 'Config/Acl/'.$this->route['controller'].'.php';

        if($this->isAcl('all')) {
            return true;
        }
        elseif(isset($_SESSION['authorize']['id']) and $this->isAcl('authorize')) {
            return true;
        }
        elseif(!isset($_SESSION['authorize']['id']) and $this->isAcl('guest')) {
            return true;
        }
        elseif(isset($_SESSION['admin']) and $this->isAcl('admin')) {
            return true;
        }
        return false;
    }

    public function isAcl($key) {//есть ли наш action в одной из групп прав

        return in_array($this->route['action'], $this->acl[$key]);//проверяем, есть ли действие в массиве прав 
    }

}