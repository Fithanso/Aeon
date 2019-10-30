<?php

namespace Vendor\Aeon\HtmlGenerator;

class HtmlGenerator {

	/*на вход массив ['язык'=>'ссылка обработчик без слеша']*/
	public function LangForm($langs_arr,$link) {

		foreach($langs_arr as $lang) {

			$template = '
                <form class="lang_wrapper_form_ru" action="'. route($link).'" method="post">
                    <a href="#" onclick="parentNode.submit();">
                        <input type="hidden" name="lang" value="'.$lang.'"/>
                        <div class="lang_div">
                            <img src="/public/img/'.$lang.'.png">
                        </div>
                    </a>
                </form>';
			$container[] = $template;
		}

		$html = implode($container);
		return $html;
	}
}