<?php
namespace TonicHealthCheck\Incident;


/**
 * Class ChecksIncidentTypeMapperInterface
 * @package TonicHealthCheck\Incident
 */
interface ChecksIncidentTypeMapperInterface
{
    /**
     * @param string $componentCheckIdent
     * @return string
     */
    public function getChecksIncidentType($componentCheckIdent);

    /**
     * @param string $componentCheckIdent
     * @param string $incidentType
     */
    public function setChecksIncidentType($componentCheckIdent, $incidentType);
}