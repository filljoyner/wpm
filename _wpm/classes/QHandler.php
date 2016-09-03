<?php
namespace Wpm;

/*
 * Overrides the default handle method and returns a new QueryPostType instance
 * for processing queries (q).
 */
use Wpm\Components\QueryPostType;

class QHandler
{
    /**
     * Passes the call to the proper component to continue
     *
     * @param $args
     * @return mixed
     */
    public function handle($args)
    {
        return (new QueryPostType($args));
    }
}