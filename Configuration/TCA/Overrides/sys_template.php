<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

ExtensionManagementUtility::addStaticFile(
    'md_news_clickcount',
    'Configuration/TypoScript',
    'News click count'
);
