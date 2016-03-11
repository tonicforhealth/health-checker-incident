<?php

namespace TonicHealthCheck\Incident\Siren\NotificationType;

use Swift_Mailer;
use Swift_Message;
use TonicHealthCheck\Incident\IncidentInterface;
use TonicHealthCheck\Incident\Siren\Subject\SubjectInterface;

/**
 * Class EmailNotificationType
 * @package TonicHealthCheck\Incident\Siren\NotificationType;
 */
class EmailNotificationType implements NotificationTypeInterface
{
    const EMAIL_BODY_T = "%s";
    const EMAIL_SUBJECT_T = 'Health Check Incident:%s';

    /**
     * @var string;
     */
    private $fromName;

    /**
     * @var Swift_Mailer;
     */
    private $mailer;

    /**
     * @var string;
     */
    private $from;

    /**
     * EmailNotificationType constructor.
     * @param Swift_Mailer $mailer
     * @param string       $from
     * @param string       $fromName
     */
    public function __construct(Swift_Mailer $mailer, $from, $fromName)
    {
        $this->setMailer($mailer);
        $this->setFrom($from);
        $this->setFromName($fromName);
    }

    /**
     * @param SubjectInterface $subject
     * @param IncidentInterface $incident
     */
    public function notify(SubjectInterface $subject, IncidentInterface $incident)
    {
        if ($incident->getStatus() != IncidentInterface::STATUS_OK) {
            $message = Swift_Message::newInstance()
                ->setTo($subject->getTarget())
                ->setFrom($this->getFrom(), $this->getFromName())
                ->setSubject(sprintf(self::EMAIL_SUBJECT_T, $incident->getIdent()))
                ->setBody(sprintf(self::EMAIL_BODY_T, $incident->getMessage()));
            $this->getMailer()->send($message);
            $this->getMailer()->getTransport()->stop();
        }
    }

    /**
     * @return Swift_Mailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @param Swift_Mailer $mailer
     */
    protected function setMailer($mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $from
     */
    protected function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @param string $fromName
     */
    protected function setFromName($fromName)
    {
        $this->fromName = $fromName;
    }
}