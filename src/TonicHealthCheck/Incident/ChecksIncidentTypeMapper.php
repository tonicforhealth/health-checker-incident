<?php

namespace TonicHealthCheck\Incident;

/**
 * Class ChecksIncidentTypeMapper
 * @package TonicHealthCheck\Incident
 */
class ChecksIncidentTypeMapper implements ChecksIncidentTypeMapperInterface
{

    /**
     * @var array
     */
    private $checksIncidentTypeMap = [];

    /**
     * ChecksIncidentTypeMapper constructor.
     * @param array $checksIncidentTypeMap
     */
    public function __construct($checksIncidentTypeMap = [])
    {
        $this->setchecksIncidentTypeMap($checksIncidentTypeMap);
    }

    /**
     * @param string $componentCheckIdent
     * @return string
     */
    public function getChecksIncidentType($componentCheckIdent)
    {
        $checksIncidentType = IncidentInterface::TYPE_URGENT;

        if (isset($this->checksIncidentTypeMap[$componentCheckIdent])) {
            $checksIncidentType = $this->checksIncidentTypeMap[$componentCheckIdent];
        }

        return $checksIncidentType;
    }

    /**
     * @param string $componentCheckIdent
     * @param string $incidentType
     */
    public function setChecksIncidentType($componentCheckIdent, $incidentType)
    {
        $this->checksIncidentTypeMap[$componentCheckIdent] = $incidentType;
    }

    /**
     * @param array $checksIncidentTypeMap
     */
    protected function setChecksIncidentTypeMap($checksIncidentTypeMap)
    {
        $this->checksIncidentTypeMap = $checksIncidentTypeMap;
    }
}