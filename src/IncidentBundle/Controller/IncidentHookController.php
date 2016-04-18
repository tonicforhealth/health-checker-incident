<?php

namespace IncidentBundle\Controller;

use IncidentBundle\Entity\Incident;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Exception;

/**
 * Class IncidentHookController
 * @package IncidentBundle\Controller
 */
class IncidentHookController extends Controller
{
    /**
     * @Route("/dnnlpwo2cj287189282bbcnjskshewk/{checkIdent}")
     * @param Request $request
     * @return Response
     */
    public function emailReciveCheckProcHookAction($checkIdent, Request $request)
    {
        $ident = $this->getIdent($checkIdent);

        $errors = [];
        $responseData = [];
        set_error_handler($this->getErrorHandlerCallback($errors));

        try {
            $entityManager = $this->getDoctrine()->getManager();
            if (! $incident = $this->getIncident($ident)) {
                $incident = new Incident($ident);
                $entityManager->persist($incident);
            }
            $incident->setMessage('Email don\'t receive webhook check');
            $incident->setStatus(500);
            $entityManager->flush();
        } catch (Exception $e) {
            $errors[] = $e;
        }
        $responseData['status'] = empty($errors) ?'ok':'error';

        return new Response(json_encode($responseData));
    }

    /**
     * @param string $ident
     * @return Incident
     */
    protected function getIncident($ident)
    {
        $incident = $this->getDoctrine()
            ->getRepository('IncidentBundle:Incident')
            ->findOneBy(['ident' => $ident], ['id' => 'desc']);

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

    /**
     * @param $checkIdent
     * @return string
     */
    protected function getIdent($checkIdent)
    {
        return sprintf('%s.%s', $checkIdent, date('Ymd'));
    }
}
