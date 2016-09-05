<?php
namespace AuM\Blypo\ViewHelper;

use TYPO3\CMS\Core\Resource\FileReference;

class Image extends \AuM\Blypo\ViewHelper\ViewHelper {

	public function render($onlyUrl = false){

		$this->envService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Service\EnvironmentService');
		$this->resourceFac = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Resource\ResourceFactory');
		$this->imageService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Service\ImageService');
		$this->imageService->injectEnvironmentService($this->envService);
		$this->imageService->injectResourceFactory($this->resourceFac);

		$arg['src'] = isset($this->arguments['src']) ? $this->arguments['src'] : null;
		$sizes = null;
		$sizesMax = null;
		$sizesMin = null;

		if(isset($this->arguments['sizes'])){
			$sizes = array_map('trim', explode(',', $this->arguments['sizes']));
			if(count($sizes) == 1){
				$sizes[] = $sizes[0];
			}
		}
		if(isset($this->arguments['sizesMax'])){
			$sizesMax = array_map('trim', explode(',', $this->arguments['sizesMax']));
			if(count($sizesMax) == 1){
				$sizesMax[] = $sizesMax[0];
			}
		}
		if(isset($this->arguments['sizesMin'])){
			$sizesMin = array_map('trim', explode(',', $this->arguments['sizesMin']));
			if(count($sizesMin) == 1){
				$sizesMin[] = $sizesMin[0];
			}
		}

		if($sizes){
			$arg['width'] = $sizes[0];
			$arg['height']  = $sizes[1];
		} else {
			$arg['width'] = isset($this->arguments['width']) ? $this->arguments['width'] : null;
			$arg['height'] = isset($this->arguments['height']) ? $this->arguments['height'] : null;
		}

		if($sizesMax){
			$arg['maxWidth'] = $sizesMax[0];
			$arg['maxHeight']  = $sizesMax[1];
		}else {
			$arg['maxWidth'] = isset($this->arguments['maxWidth']) ? $this->arguments['maxWidth'] : null;
			$arg['maxHeight'] = isset($this->arguments['maxHeight']) ? $this->arguments['maxHeight'] : null;
		}

		if($sizesMin){
			$arg['width'] = $sizesMin[0];
			$arg['height']  = $sizesMin[1];
		}else {
			$arg['minWidth'] = isset($this->arguments['minWidth']) ? $this->arguments['minWidth'] : null;
			$arg['minHeight'] = isset($this->arguments['minHeight']) ? $this->arguments['minHeight'] : null;
		}

		$arg['reference'] = isset($this->arguments['reference']) ? $this->arguments['reference'] : false;
		$arg['image'] = isset($this->arguments['image']) ? $this->arguments['image'] : null;
		$arg['crop'] = isset($this->arguments['crop']) ? $this->arguments['crop'] : null;
		$arg['absolute'] = isset($this->arguments['absolute']) ? $this->arguments['absolute'] : false;
		$attributes = isset($this->arguments['attributes']) ? $this->arguments['attributes'] : [];

		if (is_null($arg['src']) && is_null($arg['image']) || !is_null($arg['src']) && !is_null($arg['image'])) {
			\AuM\Blypo\Service\ExceptionService::throwException('You have to pass src or image obj');
		}

		try {
			$image = $this->imageService->getImage($arg['src'], $arg['image'], $arg['reference']);

			if ($crop === null) {
					$crop = $image instanceof FileReference ? $image->getProperty('crop') : null;
			}

			$processedImage = $this->imageService->applyProcessingInstructions($image, $arg);

			$imageUri = $this->imageService->getImageUri($processedImage, $absolute);

			if($onlyUrl){
				echo $imageUri;
				return;
			}

			$attributes['src'] = $imageUri;
			$attributes['width'] = $processedImage->getProperty('width');
			$attributes['height'] = $processedImage->getProperty('height');

			$alt = $image->getProperty('alternative');
			$title = $image->getProperty('title');

			// The alt-attribute is mandatory to have valid html-code, therefore add it even if it is empty
			if (empty($this->arguments['alt'])) {
					$attributes['alt'] = $alt;
			}
			if (empty($this->arguments['title']) && $title) {
				$attributes['title'] = $title;
			}
		} catch (ResourceDoesNotExistException $e) {
            // thrown if file does not exist
        } catch (\UnexpectedValueException $e) {
            // thrown if a file has been replaced with a folder
        } catch (\RuntimeException $e) {
            // RuntimeException thrown if a file is outside of a storage
        } catch (\InvalidArgumentException $e) {
            // thrown if file storage does not exist
		}
    	$attrs = '';
		foreach($attributes as $tag=>$attr){
			$attrs.=' '.$tag.'="'.$attr.'"';
		}
    	return '<img '.$attrs.'/>';
    }

}
