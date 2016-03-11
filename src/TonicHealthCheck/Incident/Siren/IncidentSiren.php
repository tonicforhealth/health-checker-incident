<?php

namespace TonicHealthCheck\Incident\Siren;

use SplSubject;
use TonicHealthCheck\Incident\IncidentInterface;
use TonicHealthCheck\Incident\Siren\NotificationType\NotificationTypeInterface;
use TonicHealthCheck\Incident\Siren\Subject\SubjectCollection;
use TonicHealthCheck\Incident\Siren\Subject\SubjectInterface;

/**
 * Class IncidentSiren
 * @package TonicHealthCheck\Incident\Siren
 */
class IncidentSiren implements \SplObserver
{
    const FILE_NAME_ALARM_TRIGGER = 'alarm_triger.data';

    /**
     * @var NotificationTypeInterface
     */
    protected $notificationTypeInstance;

    /**
     * email or tel numbers
     *
     * @var SubjectCollection
     */
    protected $subjects = [];


    /**
     * IncidentSiren constructor.
     * @param NotificationTypeInterface $notificationTypeInstance
     * @param null|array                $subjects
     * @throws IncidentSirenException
     */
    public function __construct(NotificationTypeInterface $notificationTypeInstance, SubjectCollection $subjects = null)
    {
        $this->setNotificationTypeInstance($notificationTypeInstance);
        $this->setSubjects($subjects);
    }

    /**
     * Receive update from subject
     * @link http://php.net/manual/en/splobserver.update.php
     * @param SplSubject $subject <p>
     * The <b>SplSubject</b> notifying the observer of an update.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function update(SplSubject $subject)
    {

        if ($subject instanceof IncidentInterface) {
            /** @var IncidentInterface $subject */
                $this->notify($subject);
        }
    }

    /**
     * @param IncidentInterface $incident
     * @param null|array        $subjects
     */
    public function notify(IncidentInterface $incident, $subjects = null)
    {
        $subjects = null === $subjects? $this->getSubjects(): $subjects;
        /** @var SubjectInterface $subject */
        foreach ($subjects as $subject) {
            try {
                if (null === $subject->getSchedule() || $subject->getSchedule()->isDue()) {
                    $this->getNotificationTypeInstance()->notify($subject, $incident);
                }
            } catch (\Exception $e) {
                user_error($e->getMessage(), E_USER_WARNING);
            }
        }
    }

    /**
     * @return NotificationTypeInterface
     */
    public function getNotificationTypeInstance()
    {
        return $this->notificationTypeInstance;
    }

    /**
     * @return SubjectCollection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @param SubjectCollection $subjects
     */
    protected function setSubjects(SubjectCollection $subjects)
    {
        $this->subjects = $subjects;
    }

    /**
     * @param NotificationTypeInterface $notificationTypeInstance
     */
    protected function setNotificationTypeInstance(NotificationTypeInterface $notificationTypeInstance)
    {
        $this->notificationTypeInstance = $notificationTypeInstance;
    }

}
