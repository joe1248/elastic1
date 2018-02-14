<?php

namespace App\Tests\Controller;

use App\DataFixtures\AuthorFixtures;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class AuthorControllerTest extends WebTestCase
{
    protected $kernelDir = '/app';

    /** @var ReferenceRepository */
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(
            [
                AuthorFixtures::class,
            ]
        )->getReferenceRepository();
    }

    public function testGetAll()
    {
        $client = $this->makeClient(true);
        $client->request('GET', '/authors/list');

        $this->assertStatusCode(200, $client);
        $data = json_decode($client->getResponse()->getContent(), true);
        $authors = $data['authors'];
        $this->assertEquals(120, count($authors));

        // Test First and Last = 120th author data...
        $this->assertEquals(
            [
                'id' => $this->fixtures->getReference('author1')->getId(),
                'first_name' => 'Ejioeyey',
                'last_name' => 'Uhogueoy',
            ],
            $authors[0]
        );
        $this->assertEquals(
            [
                'id' => $this->fixtures->getReference('author120')->getId(),
                'first_name' => 'Aiehozol',
                'last_name' => 'Adicoxih',
            ],
            $authors[119]
        );
    }

    public function testGetOneWorks()
    {
        $id5 = $this->fixtures->getReference('author5')->getId();

        $client = $this->makeClient(true);
        $client->request('GET', '/authors/' . $id5);

        $this->assertStatusCode(200, $client);
        $author = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(
            [
                'id' => $id5,
                'first_name' => 'Otucojei',
                'last_name' => 'Uhezijev',
            ],
            $author
        );
    }

    public function testGetOneFailsIfIdNotNumeric()
    {
        $client = $this->makeClient(true);
        $client->request('GET', '/authors/bad_id');

        $this->assertStatusCode(200, $client);
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(
            [
                'error' => [
                    'code' => 400,
                    'message' => 'Bad Request: invalid value specified for `authorId`'
                ]
            ],
            $response
        );
    }

    public function testGetOneFailsIfNotFound()
    {
        $client = $this->makeClient(true);
        $client->request('GET', '/authors/-2');

        $this->assertStatusCode(200, $client);
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(
            [
                'error' => [
                    'code' => 400,
                    'message' => 'Bad Request: author not found'
                ]
            ],
            $response
        );
    }
}
