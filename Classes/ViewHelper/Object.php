<?php
namespace AuM\Blypo\ViewHelper;
Class Object extends \AuM\Blypo\ViewHelper\ViewHelper{
	
	public function render(){
		$subject = $this->arguments['subject'];
		$propertyPath = $this->arguments['path'];
        return static::parse($subject,$propertyPath);
	}
	
	public static function parse($subject = null,$propertyPath = ''){
		if(empty($propertyPath)){
			return null;
		}
		$propertyPathSegments = explode('.', $propertyPath);
		if(count($propertyPathSegments) > 0 && !empty($propertyPathSegments[0])) {
			
		}
		foreach ($propertyPathSegments as $pathSegment) {
            if ($subject === null || is_scalar($subject)) {
                return null;
            }
            $propertyExists = false;
            $propertyValue = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getPropertyInternal($subject, $pathSegment, false, $propertyExists);
            if ($propertyExists !== true && (is_array($subject) || $subject instanceof \ArrayAccess) && isset($subject[$pathSegment])) {
                $subject = $subject[$pathSegment];
            } else {
                $subject = $propertyValue;
            }
        }
		
        return $subject;
	}
}
    