<?php
namespace TonicHealthCheck\Incident\Siren\Subject;

use Collections\Collection;

/**
 * Class SubjectCollection
 * @package TonicHealthCheck\Incident\Siren\Subject
 */
class SubjectCollection extends Collection
{
    const OBJECT_CLASS = SubjectInterface::class;

    /**
     * IncidentCollection constructor.
     */
    public function __construct()
    {
        parent::__construct(static::OBJECT_CLASS);
    }
}