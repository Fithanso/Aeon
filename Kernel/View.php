<?php

namespace Vendor\Aeon\Kernel;

 class View {

     public $path;
     public $route;
     public $layout = 'main';//ВНИМАНИЕ!!! СВОЙСТВА КЛАССА ВИДНЫ И В ПОДКЛЮЧАЕМЫХ ВИДАХ
	 public $view;

    public function __construct($route) {
        $this->route = $route;
        $this->path = $route['controller'].'/'.$route['action'];

    }

    public function render($title, $vars = []) {

    	if(isset($this->view)) {
		    $path = 'App/Views/'.$this->view.'.php';
	    }else{
		    $path = 'App/Views/'.$this->path.'.php';
	    }

        if(file_exists($path)) {
	        extract($vars);
            ob_start();
            require $path;
            $content = ob_get_clean();

	        //$menu = $this->renderHeaderMenu($menu_config);

            require 'App/Views/layouts/'.$this->layout.'.php';
        }else{
            echo 'Вид не найден: '.$this->path;
        }

    }
    public function redirect($url) {
        header('location: '.$url);
        exit;
    }

    public static function errorCode($code) {
        http_response_code($code);
        $path = 'App/Views/errors/'.$code.'.php';
        if(file_exists($path)) {
            require $path;
        }
        exit;
    }

    public function message($status, $message) {
        exit(json_encode(['status' => $status, 'message' => $message]));
    }

    public function location($url) {
        exit(json_encode(['url' => $url]));
    }

    /*
    *This function generates header menu from particle in appropriate file
    *when providing it with config array, the structure is:
    *[
    *[name,active/empty,link,framing tag]
    *]
    *
    */
    public function renderHeaderMenu($config = []) {// useless function. my nerves was thr only thing it got
        
        ob_start();
        require('App/Views/'.$this->route['controller'].'/particles/header-menu-sample.php');

        $particle = ob_get_clean();
        $menu_container = [];
        
        for($i=0; $i<count($config);$i++) {
            $title = $config[$i][0];
            $active = $config[$i][1];
            $link = $config[$i][2];
            $tag = '</'.$config[$i][3].'>';
            $string = $particle;//просто копия


            //Editing title
            $tag_pos = strpos($string, $tag);
            $string = substr_replace($string , $title , $tag_pos);//вставка названия между двух указанных обрамляющих тегов
	        print_r($string);
	        /*
		   //Editing class
		   if($active == 'active') {
			  $class_str  = 'class="';
			  $class_pos = strpos($string, $class_str);
			  $class_pos += 7;//сдвиг на само слово class="
			  $string = substr_replace($string, $active, $class_pos,0);//0-по сути просто вставка, что и нужно

		   }

		   //Editing link
		   if(!empty($link)) {
			   $href_str = 'href="';
			   $href_pos = strpos($string, $href_str);
			   $href_pos += 6;
			   $string = substr_replace($string, $link, $href_pos,0);//0-по сути просто вставка, что и нужно
		   }*/
	        $menu_container[] = $string;
            
        }
        //return implode($menu_container);
              
    }

}