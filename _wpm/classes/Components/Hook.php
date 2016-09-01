<?php
namespace Wpm\Components;

class Hook
{
    public function action($action, $closure)
    {
        add_action($action, $closure);
    }


    public function filter($action, $closure)
    {
        add_filter($action, $closure);
    }
}