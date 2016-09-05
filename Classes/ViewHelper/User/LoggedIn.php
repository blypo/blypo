<?php

namespace AuM\Blypo\ViewHelper;

Class LoggedIn extends \AuM\Blypo\ViewHelper\ViewHelper{
	public function render(){
		return isset($GLOBALS['TSFE']) && $GLOBALS['TSFE']->loginUser;
	}
}