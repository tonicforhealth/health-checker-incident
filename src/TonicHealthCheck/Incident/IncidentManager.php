<?php

namespace TonicHealthCheck\Incident;

use Doctrine\ORM\EntityManager;
use TonicHealthCheck\Check\CheckException;
use TonicHealthCheck\Check\CheckInterface;
use TonicHealthCheck\Entity\Incident;

/**
 * Class IncidentManager
 * @package TonicHealthCheck\Incident
 */
class IncidentManager
{
    /**
     * @var EntityManager
     */
    protected $doctrine;

    /**
     * @var ChecksIncidentTypeMapperInterface
     */
    private $checksIncidentTypeMapper;

    /**
     * IncidentHandler constructor.
     * @param EntityManager                     $doctrine
     * @param ChecksIncidentTypeMapperInterface $checksIncidentTypeMapper
     */
    public function __construct(
        EntityManager $doctrine,
        ChecksIncidentTypeMapperInterface $checksIncidentTypeMapper
    ) {
        $this->setDoctrine($doctrine);
        $this->setChecksIncidentTypeMapper($checksIncidentTypeMapper);
    }

    /**
     * @param CheckInterface $checkObj
     * @param CheckException $e
     */
    public function fireIncident(CheckInterface $checkObj, CheckException $e)
    {
        $ident = $checkObj->getIndent();
        $name = $checkObj->getCheckComponent().":".$checkObj->getCheckNode();
        /** @var IncidentInterface $incident */

        $incident = $this->getDoctrine()
            ->getRepository('TonicHealthCheck\Entity\Incident')
            ->findOneBy(['ident' => $ident]);
        if (!$incident) {
            $incident = new Incident($ident, $name);
            $incident->setMessage($e->getMessage());
            $incident->setType($this->getChecksIncidentTypeMapper()->getChecksIncidentType($checkObj->getIndent()));
            $this->getDoctrine()->persist($incident);
            $this->getDoctrine()->flush();
        }

        $incident->setStatus($e->getCode());

        $this->getDoctrine()->persist($incident);
        $this->getDoctrine()->flush();
    }

    /**
     * @param CheckInterface $checkObj
     */
    public function resolveIncident(CheckInterface $checkObj)
    {
        $ident = $checkObj->getIndent();

        $incident = $this->getDoctrine()
            ->getRepository('TonicHealthCheck\Entity\Incident')
            ->findOneBy(['ident' => $ident]);
        if ($incident && $incident instanceof Incident) {
            $incident->setStatus(IncidentInterface::STATUS_OK);
            $this->getDoctrine()->persist($incident);
            $this->getDoctrine()->flush();
            $this->getDoctrine()->remove($incident);
            $this->getDoctrine()->flush();
        }
    }

    /**
     * @return EntityManager
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @return ChecksIncidentTypeMapperInterface
     */
    public function getChecksIncidentTypeMapper()
    {
        return $this->checksIncidentTypeMapper;
    }

    /**
     * @param EntityManager $doctrine
     */
    protected function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param ChecksIncidentTypeMapperInterface $checksIncidentTypeMapper
     */
    protected function setChecksIncidentTypeMapper(ChecksIncidentTypeMapperInterface $checksIncidentTypeMapper)
    {
        $this->checksIncidentTypeMapper = $checksIncidentTypeMapper;
    }
}