<?php

namespace IncidentBundle\Tests\Controller;

use IncidentBundle\Entity\Incident;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class IncidentControllerTest
 */
class IncidentControllerTest extends WebTestCase
{
    const UPDATED_NAME = "updated.name";
    const UPDATED_STATUS = 35;
    const INCIDENT_DEF_IDENT = 'test_ident-23';

    /**
     * test create incident ok
     */
    public function testCreateActionOk()
    {
        $client = static::createClient();

        $client->request(
            'DELETE',
            '/incident/'.static::INCIDENT_DEF_IDENT
        );

        $testDataObjForI = $this->createFullIncidentObj();

        $crawler = $client->request(
            'PUT',
            '/incident/',
            [],
            [],
            [],
            json_encode($testDataObjForI)
        );

        $responseObj = $this->assertIncidentJsonResValid($client, $crawler);

        $this->assertObjectHasAttribute('data', $responseObj);

        $this->assertEquals($testDataObjForI, $responseObj->data);

        $this->assertObjectHasAttribute('errors', $responseObj);
        $this->assertEmpty($responseObj->errors);
    }

    /**
     * test create incident fail without some required args
     */
    public function testCreateActionFail()
    {
        $client = static::createClient();

        $testDataObjForI = $this->createFullIncidentObj();
        unset($testDataObjForI->ident);

        $crawler = $client->request(
            'PUT',
            '/incident/',
            [],
            [],
            [],
            json_encode($testDataObjForI)
        );

        $responseObj = $this->assertIncidentJsonResValid($client, $crawler);

        $this->assertObjectHasAttribute('data', $responseObj);

        $this->assertEmpty($responseObj->data);

        $this->assertObjectHasAttribute('errors', $responseObj);
        $this->count(1, $responseObj->errors);
    }

    /**
     * @depends testCreateActionOk
     * test update incident ok
     */
    public function testUpdateActionOk()
    {
        $client = static::createClient();

        $testDataObjForI = $this->createFullIncidentObj();

        $testDataObjForI->name = self::UPDATED_NAME;
        $testDataObjForI->status = self::UPDATED_STATUS;

        $crawler = $client->request(
            'POST',
            '/incident/'.$testDataObjForI->ident,
            [],
            [],
            [],
            json_encode($testDataObjForI)
        );

        $responseObj = $this->assertIncidentJsonResValid($client, $crawler);

        $this->assertObjectHasAttribute('data', $responseObj);

        $this->assertEquals($testDataObjForI, $responseObj->data);

        $this->assertObjectHasAttribute('errors', $responseObj);
        $this->assertEmpty($responseObj->errors);
    }

    /**
     * @depends testCreateActionOk
     * test update incident fail without ident
     */
    public function testUpdateActionFail()
    {
        $client = static::createClient();

        $testDataObjForI = $this->createFullIncidentObj();

        unset($testDataObjForI->ident);
        $crawler = $client->request(
            'POST',
            '/incident/null',
            [],
            [],
            [],
            json_encode($testDataObjForI)
        );

        $responseObj = $this->assertIncidentJsonResValid($client, $crawler);

        $this->assertObjectHasAttribute('data', $responseObj);

        $this->assertEmpty($responseObj->data);

        $this->assertObjectHasAttribute('errors', $responseObj);
        $this->count(1, $responseObj->errors);
    }

    /**
     * @depends testUpdateActionOk
     * test get incident ok
     */
    public function testGetActionOk()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'GET',
            '/incident/'.static::INCIDENT_DEF_IDENT
        );

        $responseObj = $this->assertIncidentJsonResValid($client, $crawler);

        $this->assertObjectHasAttribute('data', $responseObj);

        $this->assertEquals(static::UPDATED_NAME, $responseObj->data->name);
        $this->assertEquals(static::UPDATED_STATUS, $responseObj->data->status);

        $this->assertObjectHasAttribute('errors', $responseObj);
        $this->assertEmpty($responseObj->errors);
    }

    /**
     * @depends testUpdateActionOk
     * test get incident fail wrong ident
     */
    public function testGetActionFail()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'GET',
            '/incident/test2233432'
        );

        $responseObj = $this->assertIncidentJsonResValid($client, $crawler);

        $this->assertObjectHasAttribute('data', $responseObj);

        $this->assertEmpty($responseObj->data);

        $this->assertObjectHasAttribute('errors', $responseObj);
        $this->assertCount(1, $responseObj->errors);
        $this->assertContains('Incident not find', $responseObj->errors[0]->text);
        $this->assertEquals(404, $responseObj->errors[0]->code);
    }

    /**
     * @return object
     */
    protected function createFullIncidentObj()
    {
        $testDataObjForI = (object) [
            'ident' => self::INCIDENT_DEF_IDENT,
            'name' => 'test_name-foo',
            'message' => 'Some check catch some error',
            'status' => 32,
            'type' => 'urgent',
        ];

        return $testDataObjForI;
    }

    /**
     * @depends testCreateActionOk
     * test get incident ok
     */
    public function testDeleteAction()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'DELETE',
            '/incident/'.static::INCIDENT_DEF_IDENT
        );

        $responseObj = $this->assertIncidentJsonResValid($client, $crawler);

        $this->assertObjectHasAttribute('errors', $responseObj);
        $this->assertEmpty($responseObj->errors);

        $crawler = $client->request(
            'DELETE',
            '/incident/'.static::INCIDENT_DEF_IDENT
        );

        $responseObj = $this->assertIncidentJsonResValid($client, $crawler);

        $this->assertCount(1, $responseObj->errors);
        $this->assertContains('Incident not find', $responseObj->errors[0]->text);
        $this->assertEquals(404, $responseObj->errors[0]->code);
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
}
