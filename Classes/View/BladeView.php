<?php

namespace AuM\Blypo\View;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use AuM\Blypo\Rendering\RenderingContext;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;
use TYPO3\CMS\Extbase\Mvc\Web\Request as WebRequest;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use Philo\Blade\Blade;

class BladeView implements \TYPO3\CMS\Extbase\Mvc\View\ViewInterface{

	public $cachePath = 'typo3temp/Blypo';
	public $viewPath;
	public $fileName;
	public $filePath;
	public $templateData = [];
	public $controllerContext;
	private $blade;


	public function __construct(){
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var ConfigurationManagerInterface $configurationManager */
        $configurationManager = $this->objectManager->get(ConfigurationManagerInterface::class);
        if ($contentObject === null) {
            /** @var ContentObjectRenderer $contentObject */
            $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        }
        $configurationManager->setContentObject($contentObject);
        $this->setRenderingContext($this->objectManager->get(RenderingContext::class));
        /** @var WebRequest $request */
        $request = $this->objectManager->get(WebRequest::class);
        $request->setRequestURI(GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
        $request->setBaseURI(GeneralUtility::getIndpEnv('TYPO3_SITE_URL'));
        /** @var UriBuilder $uriBuilder */
        $uriBuilder = $this->objectManager->get(UriBuilder::class);
        $uriBuilder->setRequest($request);
        /** @var ControllerContext $controllerContext */
        $controllerContext = $this->objectManager->get(ControllerContext::class);
        $controllerContext->setRequest($request);
        $controllerContext->setUriBuilder($uriBuilder);
        $this->setControllerContext($controllerContext);
    }

	/**
     * Injects a fresh rendering context
     *
     * @param \AuM\Blypo\Rendering\RenderingContextInterface $renderingContext
     * @return void
     */
    public function setRenderingContext(\AuM\Blypo\Rendering\RenderingContextInterface $renderingContext)
    {
        $this->renderingContext = $renderingContext;
        $this->controllerContext = $renderingContext->getControllerContext();
    }

	public function assign($key,$value){
		if ( is_array($value) ) {
			if ( !isset($this->templateData[$key]) ) {
				$this->templateData[$key] = [];
				$this->templateData['_all'][$key] = [];
			}
			$this->templateData[$key] = array_merge( $this->templateData[$key], $value );
			$this->templateData['_all'][$key] = array_merge( $this->templateData['_all'][$key], $value );

		} else
			$this->templateData[$key] = $value;
			$this->templateData['_all'][$key] = $value;
	}

	public function assignMultiple(array $values){
		foreach($values as $key=>$value){
			$this->assign($key,$value);		}
	}

	public function setControllerContext(\TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext){
		$this->controllerContext = $controllerContext;
		$this->renderingContext->setControllerContext($controllerContext);
	}

	public function canRender(\TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext){
		//TODO really check
		return true;
	}

	public function setBlade($blade){
		$this->blade = $blade;
	}


	public function setViewPath($path){
		$this->viewPath = $path;
	}

	public function setFileName($name){
		$this->fileName = $name;
	}

	public function setFilePath($path){
		$this->filePath = $path;
	}

	public function initializeView(){
		$this->blade = new Blade(GeneralUtility::getFileAbsFileName($this->viewPath),GeneralUtility::getFileAbsFileName($this->cachePath));
		$this->setDirectives();
	}

	public function setDirectives(){
		\AuM\Blypo\ViewHelper\ViewHelper::setRenderContext($this->renderingContext);
		\AuM\Blypo\ViewHelper\ViewHelper::setControllerContext($this->controllerContext);
		$compiler = $this->blade->getCompiler();

		// add default namespace directive
		$compiler->directive('namespace', function($expression){
			$expression = explode(',',trim($expression,'()'));
			$className = $expression[1];
			$internalClassName = $className . '_' . str_shuffle(md5(rand(0,65536)));
			$namespace = addslashes($expression[0]);
			return '<?php
			    if(class_exists("'.$className.'") && '.$className.'::$targetNamespace !== "'.$namespace.'") {
					throw new \Exception("Alias \"'.$className.'\" is already defined and uses  \"".'.$className.'::$targetNamespace."\" as its target namespace", 1);
			    }
                            if(!class_exists("'.$className.'")){
				class '.$internalClassName.' extends \AuM\Blypo\ViewHelper\ViewHelper {
					public static $targetNamespace = "'.$namespace.'";
				}
				if(!class_exists("'.$className.'")) {
					class_alias("'.$internalClassName.'","'.$className.'");
				}
			    }
			?>';
		});

		// Directives Hook
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['blypo']['addDirective'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['blypo']['addDirective'] as $key => $classRef) {
				$compiler->directive($key, function($expression) use ($classRef){
					$expressionRaw = $expression;
					$expression = explode(',',trim($expression,'()'));

					if(is_subclass_of($classRef, \AuM\Blypo\Directive\StaticDirective::class)){
						return $classRef::render($expression, $expressionRaw);
					}

					if(!is_subclass_of($classRef, \AuM\Blypo\Directive\Directive::class)){
						throw new \Exception("directive \"".$classRef."\" must extend \AuM\Blypo\Directive\Directive", 1);
					}

					$hookObject = GeneralUtility::makeInstance($classRef);
					if($hookObject){
						$output = $hookObject->render($expression, $expressionRaw);
						return $output;
					}
					return '';
				});
            }
        }
	}

	public function render(){
		$file = $this->filePath.'.'.$this->fileName;
		return $this->blade->view()->make($file,$this->templateData)->render();
	}

}
