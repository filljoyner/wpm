<?php
namespace Wpm;

use Wpm\Components\Img;

class ImgHandler
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