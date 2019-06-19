<?php

namespace Eww\SubhhOaDashboard\Domain\Model;

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


/**
 * Class Repository
 *
 * @package Eww\SubhhOaDashboard\Domain\Model
 */
Class Repository  extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $country = '';

    /**
     * @var string
     */
    protected $geoData = '';

    /**
     * @var string
     */
    protected $technicalPlatform = '';

    /**
     * @var string
     */
    protected $repoType = '';

    /**
     * @var string
     */
    protected $oaStatus = '';

    /**
     * @var string
     */
    protected $harvestingUrl = '';

    /**
     * @var string
     */
    protected $firstIndexTimestamp = '';

    /**
     * @var string
     */
    protected $repoState = '';

    /**
     * @var string
     */
    protected $id = '';

    /**
     * @var int
     */
    protected $earliestRecordTimestamp = 0;

    /**
     * @var int
     */
    protected $latestRecordTimestamp = 0;

    /**
     * @var int
     */
    protected $recordCount = 0;

    /**
     * @var int
     */
    protected $recordCountFulltext = 0;

    /**
     * @var int
     */
    protected $recordCountOa = 0;

    /**
     * @var string
     */
    protected $metadataFormats = '';

    /**
     * @var string
     */
    protected $recordLicenceUri = '';

    /**
     * @var array
     */
    protected $recordCountTimeRangeUris = array();

    /**
     * @var string
     */
    protected $repositoryAvailabilityUri = '';

    /**
     * @var string
     */
    protected $recordDocumentTypesUri = '';

    /**
     * @var string
     */
    protected $repositorySetsUri = '';

    /**
     * @var  bool $isAll
     */
    protected $isAll = FALSE;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getGeoData()
    {
        return $this->geoData;
    }

    /**
     * @param string $geodata
     */
    public function setGeoData($geoData)
    {
        $this->geodata = $geoData;
    }

    /**
     * @return string
     */
    public function getTechnicalPlatform()
    {
        return $this->technicalPlatform;
    }

    /**
     * @param string $technicalPlatform
     */
    public function setTechnicalPlatform($technicalPlatform)
    {
        $this->technicalPlatform = $technicalPlatform;
    }

    /**
     * @return string
     */
    public function getRepoType()
    {
        return $this->repoType;
    }

    /**
     * @param string $repoType
     */
    public function setRepoType($repoType)
    {
        $this->repoType = $repoType;
    }

    /**
     * @return string
     */
    public function getOaStatus()
    {
        return $this->oaStatus;
    }

    /**
     * @param string $oaStatus
     */
    public function setOaStatus($oaStatus)
    {
        $this->oaStatus = $oaStatus;
    }

    /**
     * @return string
     */
    public function getHarvestingUrl()
    {
        return $this->harvestingUrl;
    }


    /**
     * @param string $harvestingUrl
     */
    public function setHarvestingUrl($harvestingUrl)
    {
        $this->harvestingUrl = $harvestingUrl;
    }

    /**
     * @return string
     */
    public function getFirstIndexTimestamp()
    {
        return $this->firstIndexTimestamp;
    }

    /**
     * @param string $firstIndexTimestamp
     */
    public function setFirstIndexTimestamp($firstIndexTimestamp)
    {
        $this->firstIndexTimestamp = $firstIndexTimestamp;
    }

    /**
     * @return string
     */
    public function getRepoState()
    {
        return $this->repoState;
    }

    /**
     * @param string $repoState
     */
    public function setRepoState($repoState)
    {
        $this->repoState = $repoState;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getEarliestRecordTimestamp()
    {
        return $this->earliestRecordTimestamp;
    }

    /**
     * @param int $earliestRecordTimestamp
     */
    public function setEarliestRecordTimestamp($earliestRecordTimestamp)
    {
        $this->earliestRecordTimestamp = $earliestRecordTimestamp;
    }

    /**
     * @return int
     */
    public function getLatestRecordTimestamp()
    {
        return $this->latestRecordTimestamp;
    }

    /**
     * @param int $latestRecordTimestamp
     */
    public function setLatestRecordTimestamp($latestRecordTimestamp)
    {
        $this->latestRecordTimestamp = $latestRecordTimestamp;
    }

    /**
     * @return int
     */
    public function getRecordCount()
    {
        return $this->recordCount;
    }

    /**
     * @param int $recordCount
     */
    public function setRecordCount($recordCount)
    {
        $this->recordCount = $recordCount;
    }

    /**
     * @return int
     */
    public function getRecordCountFulltext()
    {
        return $this->recordCountFulltext;
    }

    /**
     * @param int $recordCountFulltext
     */
    public function setRecordCountFulltext($recordCountFulltext)
    {
        $this->recordCountFulltext = $recordCountFulltext;
    }

    /**
     * @return int
     */
    public function getRecordCountOa()
    {
        return $this->recordCountOa;
    }

    /**
     * @param int $recordCountOa
     */
    public function setRecordCountOa($recordCountOa)
    {
        $this->recordCountOa = $recordCountOa;
    }

    /**
     * @return string
     */
    public function getMetadataFormats()
    {
        return $this->metadataFormats;
    }

    /**
     * @param string $metadataFormats
     */
    public function setMetadataFormats($metadataFormats)
    {
        $this->metadataFormats = $metadataFormats;
    }

    /**
     * @return string
     */
    public function getRecordLicenceUri()
    {
        return $this->recordLicenceUri;
    }

    /**
     * @param string $recordLicenceUri
     */
    public function setRecordLicenceUri($recordLicenceUri)
    {
        $this->recordLicenceUri = $recordLicenceUri;
    }

    /**
     * @return array
     */
    public function getRecordCountTimeRangeUris()
    {
        return $this->recordCountTimeRangeUris;
    }

    /**
     * @param array $recordCountTimeRangeUris
     */
    public function setRecordCountTimeRangeUris($recordCountTimeRangeUris)
    {
        $this->recordCountTimeRangeUris = $recordCountTimeRangeUris;
    }

    /**
     * @return string
     */
    public function getRepositoryAvailabilityUri()
    {
        return $this->repositoryAvailabilityUri;
    }

    /**
     * @param string $repositoryAvailabilityUri
     */
    public function setRepositoryAvailabilityUri($repositoryAvailabilityUri)
    {
        $this->repositoryAvailabilityUri = $repositoryAvailabilityUri;
    }

    /**
     * @return string
     */
    public function getRecordDocumentTypesUri()
    {
        return $this->recordDocumentTypesUri;
    }

    /**
     * @param $recordDocumentTypesUri
     */
    public function setRecordDocumentTypesUri($recordDocumentTypesUri)
    {
        $this->recordDocumentTypesUri = $recordDocumentTypesUri;
    }

    /**
     * @return string
     */
    public function getRepositorySetsUri()
    {
        return $this->repositorySetsUri;
    }

    /**
     * @param $repositorySetsUri
     */
    public function setRepositorySetsUri($repositorySetsUri)
    {
        $this->repositorySetsUri = $repositorySetsUri;
    }

    /**
     * @return bool
     */
    public function getIsAll() {
        return $this->isAll;
    }

    /**
     * @param $isAll
     */
    public function setIsAll($isAll) {
        $this->isAll = $isAll;
    }

}

