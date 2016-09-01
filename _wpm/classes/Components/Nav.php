<?php
namespace Wpm\Components;

class Nav
{
    public function prevUrl($text=null)
    {
        if($text) return get_previous_posts_link($text);
        return get_previous_posts_page_link();
    }
    
    
    public function nextUrl($text=null)
    {
        if($text) return get_next_posts_link($text);
        return get_next_posts_page_link();
    }
    
    
    public function paginate($args=[])
    {
        return get_the_posts_pagination($args);
    }
    
    
    public function menu($location=null, $argA='')
    {
        $args = [
            'container'      => false,
            'menu_class'     => (is_string($argA) ? $argA : 'nav'),
            'theme_location' => $location,
            'echo'           => false
        ];
        
        if(is_array($argA)) {
            $args = array_merge($args, $argA);
        }
        
        return wp_nav_menu($args);
    }
}