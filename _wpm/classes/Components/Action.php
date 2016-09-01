<?php
namespace Wpm\Components;

class Action
{
    public function add($actionTag, $closure)
    {
        return add_action($actionTag, $closure);
    }


    public function has($actionTag, $functionNameToCheck)
    {
        return has_action($actionTag, $functionNameToCheck);
    }


    public function run($actionTag, $args=[])
    {
        if($args) {
            return do_action_ref_array($actionTag, $args);
        }
        return do_action($actionTag, $args);
    }


    public function running($actionTag)
    {
        return doing_action($actionTag);
    }


    public function ran($actionTag)
    {
        return did_action($actionTag);
    }


    public function remove($actionTag, $functionNameToRemove, $priority=10)
    {
        return remove_action($actionTag, $functionNameToRemove, $priority);
    }


    public function removeAll($actionTag, $priority=false)
    {
        return remove_all_actions($actionTag, $priority);
    }
}