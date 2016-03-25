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
 * Class IncidentHookController
 * @package IncidentBundle\Controller
 */
class IncidentHookController extends Controller
{
    /**
     * @Route("/dnnlpwo2cj287189282bbcnjskshewk/{checkIdent}")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     */
    public function emailReciveCheckProcHookAction($checkIdent, Request $request)
    {
        $errors = [];
        $responseData = [];

        $entityManager = $this->getDoctrine()->getManager();
        $incident = new Incident($checkIdent);
        $incident->setMessage('Email don\'t recive proc check');
        $incident->setStatus(500);

        $entityManager->persist($incident);


        $responseData['status'] = empty($errors)?'ok':'error';

        return new Response(json_encode($responseData));
    }
}
