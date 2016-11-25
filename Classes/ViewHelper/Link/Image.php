<?php

namespace AuM\Blypo\ViewHelper;

Class LinkImage extends \AuM\Blypo\ViewHelper\Image{

	function render(){
		echo parent::render(true);
	}
}
