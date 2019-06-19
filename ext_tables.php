<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Eww.SubhhOaDashboard',
            'RepositoryList',
            'HOS OA Dashboard - Repository List'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Eww.SubhhOaDashboard',
            'RepositoryDetails',
            'HOS OA Dashboard - Repository Details'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('subhh_oa_dashboard', 'Configuration/TypoScript', 'SUBHH OA-Dashboard');
    }
);