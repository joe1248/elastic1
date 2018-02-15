<?php

namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PageNotFoundControllerTest extends WebTestCase
{
    protected $kernelDir = '/app';

    public function testPageNotFound()
    {
        $client = $this->makeClient(true);

        $client->request('GET', '/hhhhhhhhhhhiiiiiiiiiii');
        $this->assertStatusCode(200, $client);
        $error = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(['error' => [
            'code' => 400,
            'message' => 'Not found'
        ]],
            $error
        );
    }

    public function testRootIsNotFound()
    {
        $client = $this->makeClient(true);

        $client->request('GET', '/');
        $this->assertStatusCode(200, $client);
        $error = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(['error' => [
            'code' => 400,
            'message' => 'Not found'
        ]],
            $error
        );
    }
}
