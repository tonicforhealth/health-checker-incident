<?php
namespace TonicHealthCheck\Incident\Siren\Subject;

use Cron\CronExpression;

/**
 * Class Subject
 * @package TonicHealthCheck\Incident\Siren\Subject
 */
interface SubjectInterface
{
    /**
     * @return string
     */
    public function getTarget();

    /**
     * @return CronExpression
     */
    public function getSchedule();

    /**
     * @return string
     */
    public function __toString();
}