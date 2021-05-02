<?php
defined('TYPO3_MODE') || die();

$fields = [

    'md_news_clickcount_count' => [
        'exclude' => true,
        'label' => 'LLL:EXT:md_news_clickcount/Resources/Private/Language/locallang_db.xlf:tx_mdnewsclickcount_domain_model_news.md_news_clickcount_count',
        'config' => [
            'type' => 'input',
            'size' => 4,
            'eval' => 'int'
        ]
    ],

];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tx_news_domain_model_news',
    $fields
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_news_domain_model_news',
    '--div--;LLL:EXT:md_news_clickcount/Resources/Private/Language/locallang_db.xlf:tab.md_news_clickcount_count,md_news_clickcount_count,'
);
