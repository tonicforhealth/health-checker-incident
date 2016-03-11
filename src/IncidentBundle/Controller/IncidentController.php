<?php

namespace IncidentBundle\Controller;

use IncidentBundle\Entity\Incident;
use JMS\Serializer\Serializer;
use JMS\SerializerBundle\DependencyInjection\JMSSerializerExtension;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class IncidentController
 * @package IncidentBundle\Controller
 */
class IncidentController extends Controller
{
    /**
     * @var Serializer;
     */
    private $serializer;

    /**
     * @Route("/{id}")
     * @Method({"GET"})
     * @param int $id
     * @return Response
     */
    public function getAction($id)
    {
        $responseData = ['data' => [], 'error' => []];
        /** @var Incident $incident */
        $incident = $this->getIncident($id);
        if ($incident) {
            /** @var Serializer $serializer */
            $serializer = $this->get('jms_serializer');
            $responseData['data'] = $serializer->serialize($incident, 'json');
        } else {
            $responseData['error'] = 'Incident not find';
        }

        return new Response(json_encode($responseData));
    }

    /**
     * @Route("/{id}")
     * @Method({"POST"})
     * @param int     $id
     * @param Request $request
     * @return Response
     */
    public function postAction($id, Request $request)
    {
        $responseData = ['data' => [], 'error' => []];
        /** @var Incident $incident */
        $incident = $this->getIncident($id);
        if ($incident) {
            $entityManager = $this->getDoctrine()->getEntityManager();
            /** @var Serializer $serializer */
            $serializer = $this->get('jms_serializer');
            $incidentNew = $serializer->deserialize($request->getContent(), Incident::class, 'json');
            $incidentNew->setId($incident->getId());
            $entityManager->persist($incidentNew);
            $entityManager->flush();
            $responseData['data'] = $this->get('jms_serializer')->serialize($incident, 'json');
        } else {
            $responseData['error'] = 'Incident not find';
        }

        return new Response(json_encode($responseData));
    }

    /**
     * @Route("/")
     * @Method({"PUT"})
     * @param Request $request
     * @return Response
     */
    public function putAction(Request $request)
    {
        $responseData = ['data' => [], 'error' => []];

        $entityManager = $this->getDoctrine()->getEntityManager();
        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');
        /** @var Incident $incident */
        $incident = $serializer->deserialize($request->getContent(), Incident::class, 'json');
        $entityManager->persist($incident);
        $entityManager->flush();
        $responseData['data'] = $this->get('jms_serializer')->serialize($incident, 'json');

        return new Response(json_encode($responseData));
    }

    /**
     * @Route("/{id}")
     * @Method({"DELETE"})
     * @param int $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $responseData = ['data' => [], 'error' => []];
        /** @var Incident $incident */
        $incident = $this->getIncident($id);
        if ($incident) {
            $entityManager = $this->getDoctrine()->getEntityManager();
            $entityManager->remove($incident);
            $entityManager->flush();
        } else {
            $responseData['error'] = 'Incident not find';
        }

        return new Response(json_encode($responseData));
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @param Serializer $serializer
     */
    protected function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param $id
     * @return Incident
     */
    protected function getIncident($id)
    {
        $incident = $this->getDoctrine()
            ->getRepository('IncidentBundle:Incident')
            ->findOneBy(['id' => $id]);

        return $incident;
    }


}
