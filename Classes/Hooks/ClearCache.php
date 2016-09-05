<?php

namespace AuM\Blypo\Hooks;

class ClearCache {
	
	public function clear(array $parameters){
		// only clear if clicked on frontend or system
		if(isset($parameters['cacheCmd']) && ($parameters['cacheCmd'] == 'pages' || $parameters['cacheCmd'] == 'system')) {
			$cachedViewsDirectory = realpath(__DIR__.'/../../../../../typo3temp/Blypo/');
			if(is_dir($cachedViewsDirectory)){
				$files = glob($cachedViewsDirectory.'/*');
		        foreach($files as $file) {
		            if(is_file($file)) {
		                @unlink($file);
		            }
		        }
			}
		}
	}
	
}
