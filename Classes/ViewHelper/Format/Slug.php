<?php

namespace AuM\Blypo\ViewHelper;
use Stringy\Stringy as S;

class FormatSlug extends \AuM\Blypo\ViewHelper\ViewHelper {
	
	public function render($str = false){
		if(!$str) {
			$str = isset($this->arguments['str']) ? $this->arguments['str']: '';
		}
		$replacement = isset($this->arguments['replacement']) ? $this->arguments['replacement'] : '-';
		return S::create($str)->slugify($replacement);
	}
}