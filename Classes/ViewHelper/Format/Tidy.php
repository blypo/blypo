<?php

namespace AuM\Blypo\ViewHelper;
use Stringy\Stringy as S;

class FormatTidy extends \AuM\Blypo\ViewHelper\ViewHelper {
	
	public function render($str = false){
		if(!$str) {
			$str = isset($this->arguments['str']) ? $this->arguments['str']: '';
		}
		$str = isset($this->arguments['str']) ? $this->arguments['str']:'';
		return S::create($str)->tidy();
	}
}