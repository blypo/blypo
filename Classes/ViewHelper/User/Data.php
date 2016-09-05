<?php

namespace AuM\Blypo\ViewHelper;

Class UserData extends \AuM\Blypo\ViewHelper\ViewHelper{
	public function render(){
		if(!$GLOBALS['TSFE']->loginUser) {
			echo '';
		} else {
			
			$seperator = isset($this->arguments['seperator']) ? $this->arguments['seperator']: ' ';
			$fields = isset($this->arguments['fields']) ? $this->arguments['fields']: [];
			$data = [];
			foreach($fields as $key){
				if(isset($GLOBALS['TSFE']->fe_user->user[$key])){
					$data[] = $GLOBALS['TSFE']->fe_user->user[$key];
				}
			}
			echo implode($seperator, $data);
		}
	}
}