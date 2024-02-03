<?php
defined('TYPO3') or die();

call_user_func(
    static function () {

        /**
         * Add option to "Sort by"-selectbox of news plugin
         */
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByNews'] .= ',mdNewsClickcountCount';
    }
);
