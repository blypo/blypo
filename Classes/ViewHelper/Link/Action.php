<?php

namespace AuM\Blypo\ViewHelper;

Class LinkAction extends \AuM\Blypo\ViewHelper\ViewHelper{
public function render(){
	
		$action = isset($this->arguments['action']) ? $this->arguments['action'] : null; 
		$arguments = $this->arguments; 
		$controller = isset($this->arguments['controller']) ? $this->arguments['controller'] : null;
		$extensionName = isset($this->arguments['extensionName']) ? $this->arguments['extensionName'] : null;
		$pluginName = isset($this->arguments['pluginName']) ? $this->arguments['pluginName'] : null;
		$pageUid = $this->arguments['pageUid'];
		$pageType =  isset($this->arguments['pageType']) ? $this->arguments['pageType'] : 0; 
		$noCache = isset($this->arguments['noCache']) ? $this->arguments['noCache'] : false;
		$noCacheHash = isset($this->arguments['noCacheHash']) ? $this->arguments['noCacheHash'] : false;
		$section = isset($this->arguments['section']) ? $this->arguments['section'] : ''; 
		$format = isset($this->arguments['format']) ? $this->arguments['format'] : ''; 
		$linkAccessRestrictedPages = isset($this->arguments['linkAccessRestrictedPages']) ? $this->arguments['linkAccessRestrictedPages'] : false; 
		$additionalParams = isset($this->arguments['additionalParams'])? $this->arguments['additionalParams'] : array();
		$absolute = isset($this->arguments['absolute'])? $this->arguments['absolute'] : false; 
		$addQueryString = isset($this->arguments['addQueryString'])? $this->arguments['addQueryString'] : false;
		$argumentsToBeExcludedFromQueryString = isset($this->arguments['argumentsToBeExcludedFromQueryString'])? $this->arguments['argumentsToBeExcludedFromQueryString'] : array();
		$addQueryStringMethod = isset($this->arguments['addQueryStringMethod'])? $this->arguments['addQueryStringMethod'] : null;
		
        $uriBuilder = static::$renderingContext->getControllerContext()->getUriBuilder();
        $uri = $uriBuilder->reset()->setTargetPageUid($pageUid)->setTargetPageType($pageType)->setNoCache($noCache)->setUseCacheHash(!$noCacheHash)->setSection($section)->setFormat($format)->setLinkAccessRestrictedPages($linkAccessRestrictedPages)->setArguments($additionalParams)->setCreateAbsoluteUri($absolute)->setAddQueryString($addQueryString)->setArgumentsToBeExcludedFromQueryString($argumentsToBeExcludedFromQueryString)->setAddQueryStringMethod($addQueryStringMethod)->uriFor($action, $arguments, $controller, $extensionName, $pluginName);
        return $uri;
    }
}