<?php
defined('TYPO3_MODE') || die();

call_user_func(
    function () {
        /**
         * Register this extension with ext:news
         */
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Domain/Model/News'][] = 'md_news_clickcount';
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Controller/NewsController'][] = 'md_news_clickcount';

        /**
         * Add an additional action in news Plugin
         */
        $GLOBALS['TYPO3_CONF_VARS']
            ['EXT']
            ['news']
            ['switchableControllerActions']
            ['newItems']
            ['News->mdIncreaseCount'] = 'LLL:EXT:md_news_clickcount/Resources/Private/Language/locallang.xlf:controllerAction.mdIncreaseCount';
    }
);
