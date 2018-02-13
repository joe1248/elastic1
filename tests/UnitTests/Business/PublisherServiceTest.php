<?php

namespace App\Tests\UnitTests\Business;

use App\Business\PublisherService;
use App\Entity\Publisher;
use App\Repository\PublisherRepository;
use PHPUnit\Framework\TestCase;

class PublisherServiceTest extends TestCase
{
    public function testGetAllNonDeleted()
    {
        /** MockObject $publisherRepositoryMock */
        $publisherRepositoryMock = $this->getMockBuilder(PublisherRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $publisherRepositoryMock->expects($this->once())
            ->method('findBy')
            ->with($this->equalTo(['deleted' => false]))
            ->willReturn([
                new Publisher(['id' => 1, 'name' => 'PublisherOne', 'deleted' => false]),
                new Publisher(['id' => 2, 'name' => 'PublisherTwo', 'deleted' => false]),
            ]);

        /** PublisherService $publisherServiceToTest */
        $publisherServiceToTest = new PublisherService();
        $publishers = $publisherServiceToTest->getAllNonDeleted($publisherRepositoryMock);
        $this->assertEquals(
            [
                ['id' => 1, 'name' => 'PublisherOne'],
                ['id' => 2, 'name' => 'PublisherTwo'],
            ],
            $publishers
        );
    }
}
