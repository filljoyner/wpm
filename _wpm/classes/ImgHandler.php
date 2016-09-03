<?php
namespace Wpm;

use Wpm\Components\Img;

/*
 * Overrides the default handle method and returns a new image instance
 * for image processing.
 */
class ImgHandler extends BaseHandler
{
    /**
     * Passes the call to the proper component to continue
     *
     * @param $args
     * @return mixed
     */
    public function handle($args)
    {
        return new Img(WPM_CACHE_IMG_DIR, WPM_CACHE_IMG_URL);
    }
}