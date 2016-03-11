<?php

namespace TonicHealthCheck\Incident;

/**
 * Interface IncidentInterface
 * @package TonicHealthCheck\Incident
 */
interface IncidentInterface extends \SplSubject
{
    const STATUS_OK = 0;
    const TYPE_URGENT = 'urgent';
    const TYPE_WARNING = 'warning';
    const TYPE_MINOR = 'minor';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     */
    public function setId($id);

    /**
     * @return mixed
     */
    public function getIdent();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     */
    public function setMessage($message);

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @param int $status
     */
    public function setStatus($status);

    /**
     * Get externalId
     *
     * @return integer
     */
    public function getExternalId();

    /**
     * Set externalId
     *
     * @param integer $externalId
     */
    public function setExternalId($externalId);

    /**
     * Get type
     *
     * @return string
     */
    public function getType();

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type);
}
