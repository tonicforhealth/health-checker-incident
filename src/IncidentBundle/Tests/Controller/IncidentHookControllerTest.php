<?php

namespace IncidentBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class IncidentHookControllerTest
 */
class IncidentHookControllerTest extends WebTestCase
{
    /**
     * test webhook get action ok
     */
    public function testGetActionOK()
    {
        $client = static::createClient();

        $incidentIdent = 'proc.7.email.check.'.random_bytes(10);

        $crawler = $client->request(
            'GET',
            '/webhook/incident/dnnlpwo2cj287189282bbcnjskshewk/'.$incidentIdent
        );

        $responseObj = $this->assertIncidentJsonResValid($client, $crawler);

        $this->assertStatusOk($responseObj);

        $crawler = $client->request(
            'GET',
            '/webhook/incident/dnnlpwo2cj287189282bbcnjskshewk/'.$incidentIdent
        );

        $responseObj = $this->assertIncidentJsonResValid($client, $crawler);

        $this->assertStatusOk($responseObj);
    }

    /**
     * @param $client
     * @param $crawler
     *
     * @return mixed
     */
    protected function assertIncidentJsonResValid($client, $crawler)
    {
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'HTTP STATUS CODE FAIL:');

        $this->assertJson($crawler->text());

        $responseObj = json_decode($crawler->text());

        return $responseObj;
    }

    /**
     * @param $responseObj
     */
    protected function assertStatusOk($responseObj)
    {
        $this->assertObjectHasAttribute('status', $responseObj);

        $this->assertEquals('ok', $responseObj->status);
    }
}
