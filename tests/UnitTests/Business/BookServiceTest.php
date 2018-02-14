<?php

namespace App\Tests\UnitTests\Business;

use App\Business\BookService;
use App\Entity\Book;
use App\Entity\Publisher;
use App\Entity\Author;
use App\Repository\BookRepository;
use PHPUnit\Framework\TestCase;

class BookServiceTest extends TestCase
{
    public function testGetAllFeatured()
    {
        /** MockObject $bookRepositoryMock */
        $bookRepositoryMock = $this->getMockBuilder(BookRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var Author $author1 */
        $author1 = new Author(['id' => 1, 'first_name' => 'John', 'last_name' => 'Smith']);
        /** @var Author $author2 */
        $author2 = new Author(['id' => 2, 'first_name' => 'Max', 'last_name' => 'Well']);
        /** @var Publisher $publisher1 */
        $publisher1 = new Publisher(['id' => 1, 'name' => 'Gold']);
        /** @var Publisher $publisher2 */
        $publisher2 = new Publisher(['id' => 43, 'name' => 'Hype']);

        $bookRepositoryMock->expects($this->once())
            ->method('findBy')
            ->with($this->equalTo([
                //'featured' => true,
                'deleted' => false
            ]))
            ->willReturn([
                new Book([
                    'id' => 1,
                    'title' => 'BookOne',
                    'description' => 'Desc one',
                    'cover_url' => 'http://okok.com',
                    'isbn' => '51651651561651',
                    'featured' => true,
                    'deleted' => false,
                    'author' => $author1,
                    'publisher' => $publisher1,
                ]),
                new Book([
                    'id' => 2,
                    'title' => 'BookTwo',
                    'description' => 'Desc Two',
                    'cover_url' => 'http://okok2.com',
                    'isbn' => '1518164681',
                    'featured' => true,
                    'deleted' => false,
                    'author' => $author2,
                    'publisher' => $publisher2,
                ])
            ]);

        /** BookService $bookServiceToTest */
        $bookServiceToTest = new BookService();
        $books = $bookServiceToTest->getAllFeatured($bookRepositoryMock, 0 , 100);
        $this->assertEquals(
            [
                [
                    'id' => 1,
                    'title' => 'BookOne',
                    'description' => 'Desc one',
                    'cover_url' => 'http://okok.com',
                    'isbn' => '51651651561651',
                    'author' => ['id' => 1, 'first_name' => 'John', 'last_name' => 'Smith'],
                    'publisher' => ['id' => 1, 'name' => 'Gold'],
                ],
                [
                    'id' => 2,
                    'title' => 'BookTwo',
                    'description' => 'Desc Two',
                    'cover_url' => 'http://okok2.com',
                    'isbn' => '1518164681',
                    'author' => ['id' => 2, 'first_name' => 'Max', 'last_name' => 'Well'],
                    'publisher' => ['id' => 43, 'name' => 'Hype'],
                ],
            ],
            $books
        );
    }

    public function testGetNumberOfBooksFeatured()
    {
        /** MockObject $bookRepositoryMock */
        $bookRepositoryMock = $this->getMockBuilder(BookRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $bookRepositoryMock->expects($this->once())
            ->method('findBy')
            ->with($this->equalTo([
                //'featured' => true,
                'deleted' => false
            ]))
            ->willReturn([
                new Book(['id' => 1]),
                new Book(['id' => 2])
            ]);

        /** BookService $bookServiceToTest */
        $bookServiceToTest = new BookService();
        $numberOfBooks = $bookServiceToTest->getNumberOfBooksFeatured($bookRepositoryMock);
        $this->assertEquals(2, $numberOfBooks);
    }
}
