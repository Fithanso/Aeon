<?php

namespace Vendor\Aeon\Kernel;

use Vendor\Aeon\Kernel\View;

class Router {

    protected $routes = [];//здесь хранится массив из файла routes, но с ключами-регулярными выражениями
    protected $params = [];

    public function __construct() {
        $arr = require 'Config/routes.php';
        foreach($arr as $key => $val) {// читаем файл с путями
            $this->add($key, $val);
        }
    }

    public function add($route, $params) {
        $route = '#^'.$route.'$#';
        $this->routes[$route] = $params;

    }

    public function match() {
        $url = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');// получаем конкретный path из uri и убираем первый слеш

        foreach($this->routes as $route => $params) {

            if(preg_match($route, $url, $matches)) {//если url совпадает с одним из описанных маршрутов в файле routes
                $this->params = $params;

                return true;
            }
        }
        return false;
    }

    public function run() {

        if($this->match()) { // ВНИМАНИЕ!!! ниже путь надо писать в windows формате, то есть \ вместо /

            $path = 'App\Controllers\\'.ucfirst($this->params['controller']).'Controller';//если зашли на правильный url, то создаём переменную с путём к контроллеру

	        if(class_exists($path)) {

                $action = $this->params['action'].'Action';

                if(method_exists($path, $action)) {// если в классе path есть метод action

                    $controller = new $path($this->params);// создаём объект из пути, если у него есть метод, передавая имя контроллера и экшена

                    $controller->$action();// вызываем, зная имя класса и метода

                } else {
                    View::errorCode(404);
                }

            }else{
                View::errorCode(404);
            }

        }else{
            View::errorCode(404);
        }
    }

}