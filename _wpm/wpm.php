<?php
// include composer's autoload file.
require __DIR__ . '/vendor/autoload.php';

define('WPM_DIR', __DIR__);
define('WPM_URL', get_template_directory_uri() . '/' . basename(__DIR__));

define('WPM_CACHE_DIR', WPM_DIR . '/cache');
define('WPM_CACHE_URL', WPM_URL . '/cache');

define('WPM_CACHE_IMG_DIR', WPM_CACHE_DIR . '/img');
define('WPM_CACHE_IMG_URL', WPM_CACHE_URL . '/img');

define('WPM_RESOURCES_DIR', WPM_DIR . '/resources');
define('WPM_RESOURCES_URL', WPM_URL . '/resources');

/**
 * returns the class map for wpm to pull from and create the container
 *
 * @return array
 */
function wpmClassMap($type)
{
    global $wpmClassMap;
    
    if(empty($wpmClassMap)) {
        $wpmClassMap = include __DIR__ . '/config/classMap.php';
    }

    if($type) {
        if(!empty($wpmClassMap[$type])) {
            return $wpmClassMap[$type];
        }
    }
    
    return $wpmClassMap;
}


/**
 * Return the correct class from the class map by a given key.
 * 
 * @param $key
 * @return bool|mixed
 */
function wpmMapClass($type, $key)
{
    $classMap = wpmClassMap($type);
    
    if (isset($classMap[ $key ])) {
        return $classMap[ $key ];
    }
    
    return false;
}


/**
 * wpm is the base function for all interactions with WPM. This functions
 * as the container for all loaded classes, storage, and vendor calls.
 *
 * @param $selectorString
 * @return bool|object
 */
function wpm($selectorString)
{
    global $wpmContainer;
    
    $args = parseSelectorString($selectorString);
    $selector = $args[0];
    
    if (empty($wpmContainer[ $selector ])) {
        $class = wpmMapClass('handler', $selector);
        $wpmContainer['handler'][ $selector ] = new $class;
    }
    
    return $wpmContainer['handler'][ $selector ]->handle($args[1]);
}
