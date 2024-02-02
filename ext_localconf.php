<?php
defined('TYPO3') or die();

call_user_func(
    function () {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'MdNewsClickcount',
            'Count',
            [
                \Mediadreams\MdNewsClickcount\Controller\NewsController::class => 'mdIncreaseCount'
            ],
            // non-cacheable actions
            [
                \Mediadreams\MdNewsClickcount\Controller\NewsController::class => 'mdIncreaseCount'
            ]
        );

        /**
         * Register this extension with ext:news
         */
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Domain/Model/News'][] = 'md_news_clickcount';

        /**
         * Add translations to news `locallang_be`
         */
        $GLOBALS['TYPO3_CONF_VARS']
            ['SYS']
            ['locallangXMLOverride']
            ['EXT:news/Resources/Private/Language/locallang_be.xlf']
            [] = 'EXT:md_news_clickcount/Resources/Private/Language/Overrides/news_locallang_be.xlf';

    }
);
