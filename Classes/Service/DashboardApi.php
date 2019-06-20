<?php

namespace Eww\SubhhOaDashboard\Service;

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


class DashboardApi
{
    protected $baseUri = '';

    public function __construct()
    {
        $host = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)
            ->get('subhh_oa_dashboard', 'restApiHost');

        $this->baseUri = trim($host,'/').'/oai-dashboard-rest/rest/api/';
    }

    /**
     * Calls the passed $endpoint on the OA dashbord API and returns the JSON response.
     *
     * @param $endpoint
     * @return mixed
     */
    protected function read($endpoint)
    {
        try {
            $requestFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Http\RequestFactory::class);
            $url = $this->baseUri . "fdsdgfd".$endpoint;
            $additionalOptions = [
                // Additional headers for this specific request
                'headers' => ['Cache-Control' => 'no-cache'],
                // Additional options, see http://docs.guzzlephp.org/en/latest/request-options.html
                'allow_redirects' => false,
                'cookies' => false,
            ];

            // Return a PSR-7 compliant response object
            $response = $requestFactory->request($url, 'GET', $additionalOptions);
            // Get the content as a string on a successful request
            if ($response->getStatusCode() === 200) {
                //if (strpos($response->getHeaderLine('Content-Type'), 'text/html') === 0) {
                return $response->getBody()->getContents();
                //}
            }

            return NULL;

        } catch (\Exception $e) {
            return NULL;
        }
    }

    /**
     * Get a list of all repositories
     *
     * @return mixed
     */
    public function listRepos()
    {
        return $this->read('ListRepos');
    }

    /**
     * Returns the data of a repository ID of the specified date.
     *
     * @param string $repositoryId
     * @param string $date
     * @param bool $combined
     * @return mixed
     */
    public function getStateAtTimePoint($repositoryId, $date, $combined = FALSE)
    {
        if ($combined) {
            $endpoint = "GetCombinedStateAtTimePoint/".$date;
        } else {
            $endpoint = "GetStateAtTimePoint/".$repositoryId."/".$date;
        }

        return $this->read($endpoint);
    }

    /**
     * Returns the data of a repository ID of the specified time range.
     *
     * @param $repositoryId
     * @param $dateFrom
     * @param $dateTo
     * @return mixed
     */
    public function getStatesAtTimeRange($repositoryId, $dateFrom, $dateTo)
    {
        $endpoint = "GetStatesAtTimeRange/".$repositoryId."/".$dateFrom."/".$dateTo;
        return $this->read($endpoint);
    }

}