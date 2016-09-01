<?php
namespace Wpm\Components;

class Filter
{
    public function add($filterTag, $closure)
    {
        return add_filter($filterTag, $closure);
    }


    public function has($filterTag, $functionNameToCheck)
    {
        return has_filter($filterTag, $functionNameToCheck);
    }


    public function run($filterTag, $args='')
    {
        if(is_array($args)) {
            return apply_filters_ref_array($filterTag, $args);
        }
        return apply_filters($filterTag, $args);
    }


    public function running($filterTag)
    {
        return doing_filter($filterTag);
    }


    public function remove($filterTag, $functionNameToRemove, $priority=10)
    {
        return remove_action($filterTag, $functionNameToRemove, $priority);
    }


    public function removeAll($filterTag, $priority=false)
    {
        return remove_all_actions($filterTag, $priority);
    }
}