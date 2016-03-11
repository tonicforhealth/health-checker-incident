<?php

namespace TonicHealthCheck\Incident\Siren\Subject;

use Cron\CronExpression;

/**
 * Class Subject
 * @package TonicHealthCheck\Incident\Siren\Subject
 */
class Subject implements SubjectInterface
{
    /**
     * @var string;
     */
    private $target;

    /**
     * @var CronExpression ;
     */
    private $schedule;

    /**
     * Subject constructor.
     * @param string              $target
     * @param null|CronExpression $schedule
     */
    public function __construct($target, CronExpression $schedule = null)
    {
        $this->setTarget($target);
        $this->setSchedule($schedule);
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }



    /**
     * @return CronExpression
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTarget();
    }

    /**
     * @param string $target
     */
    protected function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @param null|CronExpression $schedule
     */
    protected function setSchedule(CronExpression $schedule = null)
    {
        $this->schedule = $schedule;
    }
}
