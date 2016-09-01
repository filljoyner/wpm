<?php
namespace Wpm;


abstract class BaseHandler
{

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