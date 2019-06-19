<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function() {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Eww.SubhhOaDashboard',
            'RepositoryList',
            [
                'Repository' => 'list'
            ],
            // non-cacheable actions
            [
                'Repository' => 'list'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Eww.SubhhOaDashboard',
            'RepositoryDetails',
            [
                'Repository' => 'show'
            ],
            // non-cacheable actions
            [
                'Repository' => 'show'
            ]
        );


        $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['repositoryList'] = \Eww\SubhhOaDashboard\Controller\EidController::class . '::listRepositories';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['getRecordCountTimeRange'] = \Eww\SubhhOaDashboard\Controller\EidController::class . '::getRecordCountTimeRange';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['getRecordLicence'] = \Eww\SubhhOaDashboard\Controller\EidController::class . '::getRecordLicence';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['getRepositoryAvailability'] = \Eww\SubhhOaDashboard\Controller\EidController::class . '::getRepositoryAvailability';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['getRecordDocumentTypes'] = \Eww\SubhhOaDashboard\Controller\EidController::class . '::getRecordDocumentTypes';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['getRepositorySets'] = \Eww\SubhhOaDashboard\Controller\EidController::class . '::getRepositorySets';

    }
);