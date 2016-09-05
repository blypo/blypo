<?php

namespace AuM\Blypo\ViewHelper;

Class LinkPage extends \AuM\Blypo\ViewHelper\ViewHelper{
	
	function render(){
		$pageUid = $this->arguments['pageUid'];
        $additionalParams = isset($this->arguments['additionalParams']) ? $this->arguments['additionalParams'] : [];
        $pageType = $this->arguments['pageType'];
        $noCache = $this->arguments['noCache'];
        $noCacheHash = $this->arguments['noCacheHash'];
        $section = $this->arguments['section'];
        $linkAccessRestrictedPages = $this->arguments['linkAccessRestrictedPages'];
        $absolute = $this->arguments['absolute'];
        $addQueryString = $this->arguments['addQueryString'];
        $argumentsToBeExcludedFromQueryString = isset($this->arguments['argumentsToBeExcludedFromQueryString']) ? $this->arguments['argumentsToBeExcludedFromQueryString'] : [];
        $addQueryStringMethod = $this->arguments['addQueryStringMethod'];
		
        $uriBuilder = static::$renderingContext->getControllerContext()->getUriBuilder();
        $uri = $uriBuilder->setTargetPageUid($pageUid)->setTargetPageType($pageType)->setNoCache($noCache)->setUseCacheHash(!$noCacheHash)->setSection($section)->setLinkAccessRestrictedPages($linkAccessRestrictedPages)->setArguments($additionalParams)->setCreateAbsoluteUri($absolute)->setAddQueryString($addQueryString)->setArgumentsToBeExcludedFromQueryString($argumentsToBeExcludedFromQueryString)->setAddQueryStringMethod($addQueryStringMethod)->build();
        echo $uri;
	}
}
