<?php

namespace TonicHealthCheck\Incident\Siren;

/**
 * Class IncidentSirenException
 * @package TonicHealthCheck\Incident\Siren
 */
class IncidentSirenException extends \Exception
{
    /**
     * @param string $dir
     * @return IncidentSirenException
     */
    public static function dirForTriggerDataDoesNotWritable($dir)
    {
        return new self(sprintf('Trigger data directory %s doesn\'t writable', $dir));
    }
}