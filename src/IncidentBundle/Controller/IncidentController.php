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
     * @Route("/{ident}")
     * @Method({"GET"})
     * @param int $ident
     * @return Response
     */
    public function getAction($ident)
    {
        $errors = [];
        set_error_handler($this->getErrorHandlerCallback($errors));

        $responseData = ['data' => [], 'error' => []];

        if ($incident = $this->getIncident($ident, $errors)) {
            /** @var Serializer $serializer */
            $serializer = $this->get('jms_serializer');
            $responseData['data'] = $serializer->serialize($incident, 'json');
        }

        $responseData['errors'] = $errors;

        return new Response(json_encode($responseData));
    }

    /**
     * @Route("/{ident}")
     * @Method({"POST"})
     * @param int     $ident
     * @param Request $request
     * @return Response
     */
    public function postAction($ident, Request $request)
    {
        $errors = [];
        set_error_handler($this->getErrorHandlerCallback($errors));

        $responseData = ['data' => [], 'errors' => []];

        if ($incident = $this->getIncident($ident, $errors)) {
            $entityManager = $this->getDoctrine()->getManager();
            /** @var Serializer $serializer */
            $serializer = $this->get('jms_serializer');
            $incidentNew = $serializer->deserialize($request->getContent(), Incident::class, 'json');
            $incidentNew->setId($incident->getId());
            $entityManager->merge($incidentNew);
            $entityManager->flush();
            $responseData['data'] = $this->get('jms_serializer')->serialize($incident, 'json');
        }

        $responseData['errors'] = $errors;

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
        $errors = [];
        set_error_handler($this->getErrorHandlerCallback($errors));

        $responseData = ['data' => [], 'errors' => []];

        $entityManager = $this->getDoctrine()->getManager();
        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');
        /** @var Incident $incident */
        $incident = $serializer->deserialize($request->getContent(), Incident::class, 'json');
        $entityManager->persist($incident);
        $entityManager->flush();
        $responseData['data'] = $this->get('jms_serializer')->serialize($incident, 'json');

        $responseData['errors'] = $errors;

        return new Response(json_encode($responseData));
    }

    /**
     * @Route("/{ident}")
     * @Method({"DELETE"})
     * @param int $ident
     * @return Response
     */
    public function deleteAction($ident)
    {
        $errors = [];
        set_error_handler($this->getErrorHandlerCallback($errors));

        $responseData = ['data' => [], 'error' => []];

        if ($incident = $this->getIncident($ident, $errors)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($incident);
            $entityManager->flush();
        }

        $responseData['errors'] = $errors;

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
     * @param string $ident
     * @param array $errors
     * @return Incident
     */
    protected function getIncident($ident, &$errors)
    {
        $incident = $this->getDoctrine()
            ->getRepository('IncidentBundle:Incident')
            ->findOneBy(['ident' => $ident]);
        if (!$incident) {
            $errors[] = ['code' => 404, 'text' => 'Incident not find'];
        }

        return $incident;
    }

    /**
     * @param array $errors
     * @return \Closure
     */
    protected function getErrorHandlerCallback(&$errors)
    {
        return function ($errno, $errstr, $errfile, $errline) use (&$errors) {
            $errors[] = ['code' => $errno, 'text' => $errstr];
        };
    }

}
