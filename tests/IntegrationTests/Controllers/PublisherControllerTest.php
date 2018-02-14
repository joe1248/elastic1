<?php

namespace App\Tests\Controller;

use App\DataFixtures\PublisherFixtures;
//use App\Entity\Publisher;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class PublisherControllerTest extends WebTestCase
{
    protected $kernelDir = '/app';

    /** @var ReferenceRepository */
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(
            [
                PublisherFixtures::class,
            ]
        )->getReferenceRepository();
    }

    public function testGetAll()
    {
        $client = $this->makeClient(true);
        $client->request('GET', '/publishers/list');

        $this->assertStatusCode(200, $client);
        $data = json_decode($client->getResponse()->getContent(), true);
        $publishers = $data['publishers'];
        $this->assertEquals(20, count($publishers));

        // Test First and Last = 20th publisher data...
        $this->assertEquals(
            [
                'id' => $this->fixtures->getReference('publisher1')->getId(),
                'name' => 'Ujozibodopubemur'
            ],
            $publishers[0]
        );
        $this->assertEquals(
            [
                'id' => $this->fixtures->getReference('publisher20')->getId(),
                'name' => 'Emezavesuzodelix'
            ],
            $publishers[19]
        );
    }

    public function testGetOne()
    {
        $id5 = $this->fixtures->getReference('publisher5')->getId();

        $client = $this->makeClient(true);
        $client->request('GET', '/publishers/' . $id5);

        $this->assertStatusCode(200, $client);
        $publisher = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(
            [
                'id' => $id5,
                'name' => 'Ohitolejaluoopii'
            ],
            $publisher
        );
    }
}
