<?php
namespace AuM\Blypo\ViewHelper;

class ViewHelper {
	
	public static $renderingContext;
	public static $controllerContext;
	public static $viewHelper;
	public $arguments;
	
	public static function __callStatic($name, $arguments){
		$classname = static::$targetNamespace.'\\'.ucfirst($name);
		$vh = new $classname;
		if(!is_array($arguments[0])){
			return $vh->render($arguments[0]);
		}
		
		$vh->addArguments($arguments);	
		return $vh->render();
	}
	
	public static function addViewHelper($namespace, $alias) {
		static::$viewHelper[$alias] = $namespace;
	}
	
	public static function setControllerContext($controllerContext){
		static::$controllerContext = $controllerContext;
	}
	public static function setRenderContext($renderingContext){
		static::$renderingContext = $renderingContext;
	}
	
	public function renderViewHelper($viewHelper, $arguments){
		$viewHelper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($viewHelper);
		$viewHelper->addArguments($arguments);
		$viewHelper->render();
	}
	
	public function addArguments($arguments){
		$this->arguments = $arguments[0];
	}
}