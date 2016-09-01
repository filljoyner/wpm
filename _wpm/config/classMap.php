<?php

return [
    'handler' => [
        'wp'       => Wpm\ComponentHandler::class,
        'store'    => Wpm\ComponentHandler::class,
        'q'        => Wpm\QHandler::class,
        'img'      => Wpm\ImgHandler::class,
    ],
    
    'component' => [
        'postType'  => Wpm\Components\PostType::class,
        'taxonomy'  => Wpm\Components\Taxonomy::class,
        'var'       => Wpm\Components\StoreVar::class,
        'db'        => Wpm\Components\StoreDb::class,
        'sort'      => Wpm\Components\SortPostType::class,
        'hook'      => Wpm\Components\Hook::class,
        'action'    => Wpm\Components\Action::class,
        'filter'    => Wpm\Components\Filter::class,
        'nav'       => Wpm\Components\Nav::class,
    ]
];
