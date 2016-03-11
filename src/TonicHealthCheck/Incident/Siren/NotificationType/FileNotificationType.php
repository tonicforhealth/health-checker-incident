<?php

namespace TonicHealthCheck\Incident\Siren\NotificationType;

use TonicHealthCheck\Incident\IncidentInterface;
use TonicHealthCheck\Incident\Siren\Subject\SubjectInterface;

/**
 * Class FileNotificationType
 * @package TonicHealthCheck\Incident\Siren\NotificationType;
 */
class FileNotificationType implements NotificationTypeInterface
{
    protected $pathMessage;

    /**
     * FileNotificationType constructor.
     * @param string $pathMessage
     */
    public function __construct($pathMessage)
    {
        $this->setPathMessage($pathMessage);
    }

    /**
     * @param SubjectInterface  $subject
     * @param IncidentInterface $incident
     */
    public function notify(SubjectInterface $subject, IncidentInterface $incident)
    {
        if ($incident->getStatus() != IncidentInterface::STATUS_OK) {
            file_put_contents($this->getPathMessage().$subject, $incident->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getPathMessage()
    {
        return $this->pathMessage;
    }

    /**
     * @param string $pathMessage
     */
    public function setPathMessage($pathMessage)
    {
        if (!is_writable($pathMessage)) {
            throw FileNotificationTypeException::dirForMessageDoesNotWritable($pathMessage);
        }
        $this->pathMessage = rtrim($pathMessage, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }
}