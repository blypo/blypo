<?php

namespace AuM\Blypo\ViewHelper;

Class HasGroup extends \AuM\Blypo\ViewHelper\ViewHelper{
	public function render(){
		$group = $this->arguments['group'];
        if (!isset($GLOBALS['TSFE']) || !$GLOBALS['TSFE']->loginUser) {
            return false;
        }
		
        if (is_numeric($group)) {
            return is_array($GLOBALS['TSFE']->fe_user->groupData['uid']) && in_array($group, $GLOBALS['TSFE']->fe_user->groupData['uid']);
        } else {
            return is_array($GLOBALS['TSFE']->fe_user->groupData['title']) && in_array($group, $GLOBALS['TSFE']->fe_user->groupData['title']);
        }
	}
}