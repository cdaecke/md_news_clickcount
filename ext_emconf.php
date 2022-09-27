<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "md_news_clickcount"
 *
 * Auto generated by Extension Builder 2021-05-02
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'News click count',
    'description' => 'With this extension you are able to count views of ext:news records. You can display a list with most viewed news and delete the statistics automatically with a scheduler task.',
    'category' => 'plugin',
    'author' => 'Christoph Daecke',
    'author_email' => 'typo3@mediadreams.org',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '2.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
            'news' => '9.2.0-10.9.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
