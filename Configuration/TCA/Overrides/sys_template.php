<?php
defined('TYPO3') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'md_news_clickcount',
    'Configuration/TypoScript',
    'News click count'
);
