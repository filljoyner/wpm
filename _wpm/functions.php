<?php

/**
 * Die & Dump out a variable to testing.
 * 
 * @param $value
 * @param bool $pr
 */
function dd($value, $pr = false)
{
    if ($pr) {
        die(print_r($value));
    }
    die(var_dump($value));
}


/**
 * Parses the wpm string to usable parts.
 * 
 * @param $string
 * @return array
 */
function parseSelectorString($string)
{
    $parts = explode('.', $string);
    $args = [$parts[0]];
    
    if (isset($parts[1])) {
        $args[] = explode('|', $parts[1]);
    } else {
        $args[] = [];
    }
    
    return $args;
}



/**
 * Returns the value from an array from a given key if it is set. If not, it returns
 * the provided default value.
 *
 * @param $array
 * @param $key
 * @param bool $default
 * @return bool
 */
function issetOrDefault($array, $key, $default = false)
{
    return isset($array[ $key ]) ? $array[ $key ] : $default;
}



function wpmAppendPostData($results)
{
    if(empty($results)) return $results;
    
    $single = false;

    if(! is_array($results)) {
        $single = true;
        $results = [$results];
    }
    
    $results = array_map(function($result) {
        return new \Wpm\Components\Post\Post($result);
    }, $results);

    if($single) return $results[0];
    return $results;
}