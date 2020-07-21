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


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EidController
{
    /**
     * Returns a list of all repositories and their base data in json format.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function listRepositories(ServerRequestInterface $request, ResponseInterface $response)
    {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $dashboardApi = $objectManager->get('Eww\\SubhhOaDashboard\\Service\\DashboardApi');

        $response = $response->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->getBody()->write($dashboardApi->read("ListRepos"));
        return $response;
    }


    /**
     * Returns the monthly record counts of the requested value parameter/parameters for a time range, fromTime and toTime.
     *
     * Request parameter: repositoryId
     * Request parameter: fromTime, Y-m
     * Request parameter: toTime, Y-m
     * Request parameter: valueParameters, a comma separated list of desired values (record_count, record_count_oa, record_count_fulltext)
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function getRecordCountTimeRange(ServerRequestInterface $request, ResponseInterface $response)
    {
        $repositoryId = $request->getQueryParams()['repositoryId'];

        $from =  new \DateTime($request->getQueryParams()['fromTime']);
        $from->modify('first day of this month');
        $fromTime = $from->format('Y-m-d');

        $to = new \DateTime($request->getQueryParams()['toTime']);
        $to->modify('last day of this month');
        $now = new \DateTime('now');

        // If the requested toTime is in the future there is no data, so
        // we can only deliver data until today
        if ($to > $now) {
            $toTime = $now->format('Y-m-d');
        } else {
            $toTime = $to->format('Y-m-d');
        }

        $valueParameters = array_map('trim', explode(',', $request->getQueryParams()['valueParameters']));

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $dashboardApi = $objectManager->get('Eww\\SubhhOaDashboard\\Service\\DashboardApi');

        $statesAtTimeRange = json_decode($dashboardApi->getStatesAtTimeRange($repositoryId, $fromTime, $toTime));

        $recordCount = array();
        foreach ($valueParameters as $valueParameter) {
            $recordCount[$valueParameter] = array();
        }

        $timeSteps = array();
        $monthlySteps = array();

        // Get the monthly record counts (take the value of the last day of a month).
        for ($i=0; $i < sizeof($statesAtTimeRange); $i++) {
            $state = $statesAtTimeRange[$i];
            $timeStep = date("Y-m", floor($state->timestamp/1000));
            $timeSteps[$timeStep] = $timeStep;

            $lastDayOfMonth = new \DateTime($timeStep);
            $lastDayOfMonth->modify('last day of this month');
            if ($lastDayOfMonth > $now) {
                $monthlySteps[$now->format('Y-m-d')] = $now->format('Y-m-d');
            } else {
                $monthlySteps[$lastDayOfMonth->format('Y-m-d')] = $lastDayOfMonth->format('Y-m-d');
            }

            foreach (array_keys($recordCount) as $key ) {
                $recordCount[$key][$timeStep] = $state->$key;
            }
        }

        // Eliminate the array keys because c3 can not cope with it.
        $data = array();
        $xAxis = array_keys($timeSteps);
        array_unshift($xAxis,'x');
        $data[] = $xAxis;
        foreach (array_keys($recordCount) as $key ) {
            $values = array();
            foreach($timeSteps as $step) {
                $values[] = $recordCount[$key][$step];
            }
            array_unshift($values, $key);
            $data[] = $values;
        }

        $response = $response->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->getBody()->write(json_encode(array('data' => $data, 'xGrids' => array_keys($monthlySteps))));
        return $response;
    }


    /**
     * Returns the percentage distribution of used licenses of a repository.
     *
     * Request parameter: repositoryId
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function getRecordLicence(ServerRequestInterface $request, ResponseInterface $response)
    {
        $repositoryId = $request->getQueryParams()['repositoryId'];
        $combined = $request->getQueryParams()['combined'] > 0;

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $dashboardApi = $objectManager->get('Eww\\SubhhOaDashboard\\Service\\DashboardApi');

        $statesAtTimePoint = json_decode($dashboardApi->getStateAtTimePoint($repositoryId, date('Y-m-d'), $combined));

        $licences = array();

        foreach ($statesAtTimePoint->licenceCounts as $licence) {
            $licences[$licence->licence_name] = $licence->record_count;
        }

        $result = array();
        $j = 1;
        foreach ($licences as $key => $value) {
            if ($value > 0) {
                $result['data'][] = array('data' . $j, $value);
                $result['names']['data' . $j] = $key;
            }
            $j++;
        }

        $response = $response->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->getBody()->write(json_encode($result));
        return $response;
    }


    /**
     * Returns the percentage distribution of used document types of a repository.
     *
     * Request parameter: repositoryId
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function getRecordDocumentTypes(ServerRequestInterface $request, ResponseInterface $response)
    {
        $repositoryId = $request->getQueryParams()['repositoryId'];
        $combined = $request->getQueryParams()['combined'] > 0;

        $otherLimit = $request->getQueryParams()['otherLimit']? $request->getQueryParams()['otherLimit'] : 0;

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $dashboardApi = $objectManager->get('Eww\\SubhhOaDashboard\\Service\\DashboardApi');

        $statesAtTimePoint = json_decode($dashboardApi->getStateAtTimePoint($repositoryId, date('Y-m-d'), $combined));

        $docTypeCount = 0;
        foreach ($statesAtTimePoint->dctypeCounts as $item) {
            $docTypeCount += $item->record_count;
        }

        $items = array();
        $other = 0;
        foreach ($statesAtTimePoint->dctypeCounts as $item) {
            if ($otherLimit > 0 && $item->record_count/$docTypeCount*100 < $otherLimit) {
                $other += $item->record_count;
            } else {
                $items[$item->dc_Type] = $item->record_count;
            }
        }

        if ($otherLimit > 0 && round($other/$docTypeCount*100, 1) >= $otherLimit) {
            $items['Other'] = $other;
        }

        $result = array();
        $j = 1;
        foreach ($items as $key => $value) {
            if ($value > 0) {
                $result['data'][] = array('data' . $j, $value);
                $result['names']['data' . $j] = $key;
            }
            $j++;
        }

        $response = $response->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->getBody()->write(json_encode($result));
        return $response;
    }


    /**
     * Returns the percentage distribution of used sets of a repository.
     *
     * Request parameter: repositoryId
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function getRepositorySets(ServerRequestInterface $request, ResponseInterface $response)
    {
        $repositoryId = $request->getQueryParams()['repositoryId'];
        $combined = $request->getQueryParams()['combined'] > 0;

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $dashboardApi = $objectManager->get('Eww\\SubhhOaDashboard\\Service\\DashboardApi');

        $statesAtTimePoint = json_decode($dashboardApi->getStateAtTimePoint($repositoryId, date('Y-m-d'), $combined));

        $items = array();

        foreach ($statesAtTimePoint->setCounts as $item) {

            $setSpec = strtolower($item->set_spec);

            if (
                $item->record_count > 0
                && strpos($setSpec, 'doc-type:') !== 0
            ) {
                $items[] = array(
                    'name' => $item->set_name,
                    'count' => $item->record_count,
                    'spec' => $item->set_spec
                );
            }
        }

        usort($items, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        $data = array('sets');
        $xAxis = array('x');
        $j = 1;
        foreach ($items as $value) {
            $data[] = $value['count'];
            $xAxis[] = $value['name'];
            $j++;
        }

        $result[] = $xAxis;
        $result[] = $data;

        $response = $response->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->getBody()->write(json_encode($result));
        return $response;
    }


    /**
     * Returns the availability of a repository, daily of the last 3 month, based on a successful harvesting process.
     *
     * Request parameter: repositoryId
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function getRepositoryAvailability(ServerRequestInterface $request, ResponseInterface $response) {
        $repositoryId = $request->getQueryParams()['repositoryId'];

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $dashboardApi = $objectManager->get('Eww\\SubhhOaDashboard\\Service\\DashboardApi');

        $toTime = (new \DateTime('now'))->format('Y-m-d');
        $fromTime = (new \DateTime('now'))->sub(new \DateInterval('P3M'))->format('Y-m-d');
        $now =  new \DateTime('now');

        $statesAtTimeRange = json_decode($dashboardApi->getStatesAtTimeRange($repositoryId, $fromTime, $toTime));

        $xAxis = array();
        $availability = array();
        $monthlySteps = array();
        $prevTimeStep = null;

        for ($i=0; $i < sizeof($statesAtTimeRange); $i++) {
            $state = $statesAtTimeRange[$i];

            $timeStep = new \DateTime();
            $timeStep->setTimestamp(floor($state->timestamp/1000));

            $availability[$timeStep->format("Y-m-d")] = ($state->status == "SUCCESS")? 1 : 0;

            $lastDayOfMonth = clone $timeStep;
            $lastDayOfMonth->modify('last day of this month');
            if ($lastDayOfMonth > $now) {
                $monthlySteps[$now->format('Y-m-d')] = $now->format('Y-m-d');
            } else {
                $monthlySteps[$lastDayOfMonth->format('Y-m-d')] = $lastDayOfMonth->format('Y-m-d');
            }
        }

        // Determine the x-axis and complete missing dates.
        $startDate = new \DateTime(reset(array_keys($availability)));
        $endDate = new \DateTime(end(array_keys($availability)));
        for ($dateStep = $startDate; $dateStep <= $endDate; $dateStep = $dateStep->add(new \DateInterval('P1D'))) {
            $xAxis[] = $dateStep->format("Y-m-d");
        }

        // Eliminate the array keys because c3 can not cope with it.
        $data = array();
        $values = array();
        foreach($xAxis as $step) {
            if (array_key_exists($step, $availability)) {
                $values[] = $availability[$step];
            } else {
                // Missing dates are interpreted as "repository was not available"
                $values[] = 0;
            }
        }

        array_unshift($xAxis,'x');
        $data[] = $xAxis;

        array_unshift($values, 'availability');
        $data[] = $values;



        $response = $response->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->getBody()->write(json_encode(array('data' => $data, 'xGrids' => array_keys($monthlySteps))));
        return $response;

    }

}