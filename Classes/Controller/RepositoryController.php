<?php

namespace Eww\SubhhOaDashboard\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */


class RepositoryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * Show the list of all available repositoriess.
     *
     */
    public function listAction()
    {
        $dashboardApi = $this->objectManager->get('Eww\\SubhhOaDashboard\\Service\\DashboardApi');
        $repositories = json_decode($dashboardApi->listRepos());
        $this->view->assign('repositories', $repositories);
    }

    /**
     * Show the data of a repository.
     * Diagram/Chart data is represented by URLs and later loaded in the browser via AJAX
     *
     * @param string $repositoryId
     */
    public function showAction($repositoryId = 0)
    {
        $data = NULL;

        $repository = $this->objectManager->get(\Eww\SubhhOaDashboard\Domain\Model\Repository::class);

        $repository->setIsAll(empty($repositoryId));

        $dashboardApi = $this->objectManager->get('Eww\\SubhhOaDashboard\\Service\\DashboardApi');


        $jsonData = $dashboardApi->getStateAtTimePoint($repositoryId, date("Y-m-d"), $repository->getIsAll());
        $data = json_decode($jsonData);


        if (json_decode($jsonData,true)) {

            $repository->setName($data->repository->name);
            $repository->setCountry($data->repository->land);
            $repository->setGeoData($data->repository->geodaten);
            $repository->setTechnicalPlatform($data->repository->technische_plattform);
            $repository->setRepoType($data->repository->repo_type);
            $repository->setOaStatus($data->repository->oa_status);
            $repository->setHarvestingUrl($data->repository->harvesting_url);
            $repository->setFirstIndexTimestamp($data->repository->first_index_timestamp);
            $repository->setRepoState($data->repository->state);
            $repository->setId($data->repository->id);
            $repository->setEarliestRecordTimestamp($data->earliest_record_timestamp);
            $repository->setLatestRecordTimestamp($data->latest_record_timestamp);
            $repository->setRecordCount($data->record_count);
            $repository->setRecordCountFulltext($data->record_count_fulltext);
            $repository->setRecordCountOa($data->record_count_oa);

            $metadataFormats = array();
            foreach ($data->metadataFormats as $metadataFormat) {
                $metadataFormats[] = $metadataFormat->prefix;
            }
            sort($metadataFormats);

            $repository->setMetadataFormats(implode(', ', $metadataFormats));

            $repository->setRecordCountTimeRangeUris(
                array(
                    'record_count' => $this->getTimeRanges($repository, "record_count"),
                    'record_count_oa' => $this->getTimeRanges($repository, "record_count_oa"),
                )
            );

            $repository->setRecordLicenceUri(
                $this->uriBuilder->reset()
                    ->setCreateAbsoluteUri(true)
                    ->setUseCacheHash(false)
                    ->setArguments(
                        array(
                            'eID' => 'getRecordLicence',
                            'repositoryId' => $repository->getId(),
                            'combined' => $repository->getIsAll()
                        ))
                    ->build()
            );

            $repository->setRecordDocumentTypesUri(
                $this->uriBuilder->reset()
                    ->setCreateAbsoluteUri(true)
                    ->setUseCacheHash(false)
                    ->setArguments(
                        array(
                            'eID' => 'getRecordDocumentTypes',
                            'repositoryId' => $repository->getId(),
                            'combined' => $repository->getIsAll()
                        ))
                    ->build()
            );

            $repository->setRepositorySetsUri(
                $this->uriBuilder->reset()
                    ->setCreateAbsoluteUri(true)
                    ->setUseCacheHash(false)
                    ->setArguments(
                        array(
                            'eID' => 'getRepositorySets',
                            'repositoryId' => $repository->getId(),
                            'combined' => $repository->getIsAll()
                        ))
                    ->build()
            );

            $repository->setRepositoryAvailabilityUri(
                $this->uriBuilder->reset()
                    ->setCreateAbsoluteUri(true)
                    ->setUseCacheHash(false)
                    ->setArguments(
                        array(
                            'eID' => 'getRepositoryAvailability',
                            'repositoryId' => $repository->getId()
                        ))
                    ->build()
            );

        }

        $documentTypes = [];
        foreach ($this->settings['documentTypes'] as $docType) {
            $typeTranslation = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                'subhh_oa_dashboard.chart.document_types.'.$docType,
                'subhh_oa_dashboard'
            );

            if (empty($typeTranslation)) {
                $documentTypes[$docType] = $docType;
            } else {
                $documentTypes[$docType] = $typeTranslation;
            }

        }

        $this->view->assign('documentTypes', json_encode($documentTypes));
        $this->view->assign('repository', $repository);

    }


    /**
     * Returns a list of time ranges and the corresponding request URI
     *
     * @param \Eww\SubhhOaDashboard\Domain\Model\Repository $repository
     * @param string $dataField
     * @return array
     * @throws \Exception
     */
    protected function getTimeRanges(\Eww\SubhhOaDashboard\Domain\Model\Repository $repository, $dataField = 'record_count') {
        $timeRanges = array();

        $currentYear = (int) (new \DateTime())->format('Y');

        if ($repository->getFirstIndexTimestamp()) {
            $firstYear = (int)new \DateTime(date("Y", floor($repository->getFirstIndexTimestamp() / 1000)));
        } else {
            $firstYear = $currentYear;
        }

        if ($firstYear != $currentYear) {
            for ($year = $firstYear; $year <= $currentYear; $year++) {
                $timeRanges[$year] = $this->uriBuilder->reset()
                    ->setCreateAbsoluteUri(true)
                    ->setArguments(
                        array(
                            'eID' => 'getRecordCountTimeRange',
                            'repositoryId' => $repository->getId(),
                            'valueParameters' => $dataField,
                            'fromTime' => $year . '-01',
                            'toTime' => $year . '-12',
                        ))
                    ->build();
            }
        }


        $timeRanges['last12month'] = $this->uriBuilder->reset()
                ->setCreateAbsoluteUri(true)
                ->setArguments(
                    array(
                        'eID' => 'getRecordCountTimeRange',
                        'repositoryId' => $repository->getId(),
                        'valueParameters' => $dataField,
                        'fromTime' => (new \DateTime())->sub(new \DateInterval('P1Y'))->format('Y-m'),
                        'toTime' => (new \DateTime())->format('Y-m'),
                    ))
                ->build();

        return $timeRanges;
    }
}