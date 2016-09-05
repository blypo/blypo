<?php
namespace AuM\Blypo\Rendering;
/*                                                                        *
 * This script is backported from the TYPO3 Flow package "TYPO3.Fluid".   *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

class RenderingContext implements \AuM\Blypo\Rendering\RenderingContextInterface
{

    /**
     * Object manager which is bubbled through. The ViewHelperNode cannot get an ObjectManager injected because
     * the whole syntax tree should be cacheable
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Controller context being passed to the ViewHelper
     *
     * @var \TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext
     */
    protected $controllerContext;

    /**
     * ViewHelper Variable Container
     *
     * @var \TYPO3\CMS\Fluid\Core\ViewHelper\ViewHelperVariableContainer
     */
    protected $viewHelperVariableContainer;

    /**
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     */
    public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param \TYPO3\CMS\Fluid\Core\ViewHelper\ViewHelperVariableContainer $viewHelperVariableContainer
     */
    public function injectViewHelperVariableContainer(\TYPO3\CMS\Fluid\Core\ViewHelper\ViewHelperVariableContainer $viewHelperVariableContainer)
    {
        $this->viewHelperVariableContainer = $viewHelperVariableContainer;
    }

    /**
     * Returns the object manager. Only the ViewHelperNode should do this.
     *
     * @return \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * Set the controller context which will be passed to the ViewHelper
     *
     * @param \TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext The controller context to set
     */
    public function setControllerContext(\TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext)
    {
        $this->controllerContext = $controllerContext;
    }

    /**
     * Get the controller context which will be passed to the ViewHelper
     *
     * @return \TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext The controller context to set
     */
    public function getControllerContext()
    {
        return $this->controllerContext;
    }

    /**
     * Get the ViewHelperVariableContainer
     *
     * @return \TYPO3\CMS\Fluid\Core\ViewHelper\ViewHelperVariableContainer
     */
    public function getViewHelperVariableContainer()
    {
        return $this->viewHelperVariableContainer;
    }
}
