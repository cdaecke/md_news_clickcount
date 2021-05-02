<?php
defined('TYPO3_MODE') || die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'md_news_clickcount',
    'Configuration/TypoScript',
    'News click count'
);
