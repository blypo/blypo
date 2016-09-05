<?php

namespace AuM\Blypo\ViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Service\TypoLinkCodecService;

Class TypoLink extends \AuM\Blypo\ViewHelper\ViewHelper{
	
	function render(){
		$parameter = isset($this->arguments['parameter']) ? $this->arguments['parameter']: false;
        $target = isset($this->arguments['target']) ? $this->arguments['target']: false;
        $class = isset($this->arguments['class']) ? $this->arguments['class']: false;
        $title = isset($this->arguments['title']) ? $this->arguments['title']: false;
        $additionalParams = isset($this->arguments['additionalParams']) ? $this->arguments['additionalParams'] : [];
        $additionalAttributes = isset($this->arguments['additionalAttributes']) ? $this->arguments['additionalAttributes']: [];
		
        // Merge the $parameter with other arguments
        $typolinkParameter = self::createTypolinkParameterArrayFromArguments($parameter, $target, $class, $title, $additionalParams);

        // array(param1 -> value1, param2 -> value2) --> param1="value1" param2="value2" for typolink.ATagParams
        $extraAttributes = array();
        foreach ($additionalAttributes as $attributeName => $attributeValue) {
            $extraAttributes[] = $attributeName . '="' . htmlspecialchars($attributeValue) . '"';
        }
        $aTagParams = implode(' ', $extraAttributes);

        // If no link has to be rendered, return an empty string
        $content = '';
		
        if ($parameter) {
            /** @var ContentObjectRenderer $contentObject */
            $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $contentObject->start(array(), '');
            $content = $contentObject->stdWrap(
                $content,
                array(
                    'typolink.' => array(
                        'parameter' => $typolinkParameter,
                        'ATagParams' => $aTagParams,
                    )
                )
            );
        }

        return $content;
	}

	/**
     * Transforms ViewHelper arguments to typo3link.parameters.typoscript option as array.
     *
     * @param string $parameter Example: 19 _blank - "testtitle \"with whitespace\"" &X=y
     * @param string $target
     * @param string $class
     * @param string $title
     * @param string $additionalParams
     *
     * @return string The final TypoLink string
     */
    protected static function createTypolinkParameterArrayFromArguments($parameter, $target = '', $class = '', $title = '', $additionalParams = '')
    {
        $typoLinkCodec = GeneralUtility::makeInstance(TypoLinkCodecService::class);
        $typolinkConfiguration = $typoLinkCodec->decode($parameter);
        if (empty($typolinkConfiguration)) {
            return $typolinkConfiguration;
        }

        // Override target if given in target argument
        if ($target) {
            $typolinkConfiguration['target'] = $target;
        }

        // Combine classes if given in both "parameter" string and "class" argument
        if ($class) {
            if ($typolinkConfiguration['class']) {
                $typolinkConfiguration['class'] .= ' ';
            }
            $typolinkConfiguration['class'] .= $class;
        }

        // Override title if given in title argument
        if ($title) {
            $typolinkConfiguration['title'] = $title;
        }

        // Combine additionalParams
        if ($additionalParams) {
            $typolinkConfiguration['additionalParams'] .= $additionalParams;
        }

        return $typoLinkCodec->encode($typolinkConfiguration);
    }
}
