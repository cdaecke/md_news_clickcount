<?php

use Mediadreams\MdNewsClickcount\Middleware\CountMiddleware;

return [
    'frontend' => [
        'md/news-clickcount/count' => [
            'target' => CountMiddleware::class,
            'after' => [
                'typo3/cms-core/normalized-params-attribute',
                'typo3/cms-frontend/backend-user-authentication',
            ],
            'before' => [
                'typo3/cms-frontend/eid',
            ],
        ],
    ],
];
