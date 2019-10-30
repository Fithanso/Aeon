<?php

namespace Vendor\Aeon\Language;

class Language {

	public static function lang_detect() {

        if (!isset($_SESSION['lang']))
            $_SESSION['lang'] = "ru";

        if(!empty($_POST['lang'])) {

            $lang = trim($_POST['lang']);
            if(isset($lang) && !empty($lang) && $_SESSION['lang'] != $lang) {
                if ($lang == "en")
                    $_SESSION['lang'] = "en";
                else if ($lang == "ru")
                    $_SESSION['lang'] = "ru";
            }

            $route = "Public/langs/" . $_SESSION['lang'] . ".php";
            $rq = require_once $route;

            return $rq;

        }else if(empty($_POST)) {
            $route = "Public/langs/" . $_SESSION['lang'] . ".php";
            $rq = require_once $route;

            return $rq;
        }

    }

}