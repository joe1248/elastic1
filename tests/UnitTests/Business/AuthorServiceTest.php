<?php

namespace App\Tests\UnitTests\Business;

use App\Business\AuthorService;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use PHPUnit\Framework\TestCase;

class AuthorServiceTest extends TestCase
{
    public function testGetAllNonDeleted()
    {
        /** MockObject $authorRepositoryMock */
        $authorRepositoryMock = $this->getMockBuilder(AuthorRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var Author $author1 */
        $author1 = new Author(['id' => 1, 'first_name' => 'Mike', 'last_name' => 'Beans', 'deleted' => false]);
        /** @var Author $author2 */
        $author2 = new Author(['id' => 2, 'first_name' => 'John', 'last_name' => 'Smith', 'deleted' => false]);
        $this->assertEquals(1, $author1->getId());

        $authorRepositoryMock->expects($this->once())
            ->method('findBy')
            ->with($this->equalTo(['deleted' => false]))
            ->willReturn([$author1, $author2]);

        /** AuthorService $authorServiceToTest */
        $authorServiceToTest = new AuthorService();
        $authors = $authorServiceToTest->getAllNonDeleted($authorRepositoryMock);
        $this->assertEquals(
            [
                ['id' => 1, 'first_name' => 'Mike', 'last_name' => 'Beans'],
                ['id' => 2, 'first_name' => 'John', 'last_name' => 'Smith'],
            ],
            $authors
        );
    }
}
