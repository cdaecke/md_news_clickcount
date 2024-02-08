<?php

return [
    'frontend' => [
        'md/news-clickcount/count' => [
            'target' => \Mediadreams\MdNewsClickcount\Middleware\CountMiddleware::class,
            'after' => [
                'typo3/cms-core/normalized-params-attribute'
            ],
            'before' => [
                'typo3/cms-frontend/eid'
            ]
        ],
    ],
];
