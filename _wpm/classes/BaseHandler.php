<?php
namespace Wpm;


/*
 * When the wpm function is passed a string, a handler is selected from the
 * classMap and used to route request to the associated class. This is the
 * BaseHandler that all other handlers must extend.
 */
abstract class BaseHandler
{

    // preselect the "component" handler type
    protected $handlerType = 'component';

    
    /**
     * Passes the call to the proper component to continue
     *
     * @param $args
     * @return mixed
     */
    public function handle($args)
    {
        $class = wpmMapClass($this->handlerType, $args[0]);

        return (new $class($args[1]));
    }
}