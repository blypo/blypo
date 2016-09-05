<?php

namespace AuM\Blypo\ViewHelper;

Class LinkEmail extends \AuM\Blypo\ViewHelper\ViewHelper{
public function render(){
		$email = $this->arguments['email'];
		if (TYPO3_MODE === 'FE') {
            list($linkHref, $linkText) = $GLOBALS['TSFE']->cObj->getMailTo($email, $email);
        } else {
            $linkHref = 'mailto:' . $email;
        }
        return $linkHref;
    }
}