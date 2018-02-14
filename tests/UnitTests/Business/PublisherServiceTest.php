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

        /** @var Publisher $publisher1 */
        $publisher1 = new Publisher(['id' => 1, 'name' => 'PublisherOne', 'deleted' => false]);
        /** @var Publisher $publisher2 */
        $publisher2 = new Publisher(['id' => 2, 'name' => 'PublisherTwo', 'deleted' => false]);
        $this->assertEquals(1, $publisher1->getId());

        $publisherRepositoryMock->expects($this->once())
            ->method('findBy')
            ->with($this->equalTo(['deleted' => false]))
            ->willReturn([$publisher1, $publisher2]);

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
