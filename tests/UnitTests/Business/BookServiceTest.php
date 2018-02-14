<?php

namespace App\Tests\UnitTests\Business;

use App\Business\BookService;
use App\Entity\Book;
use App\Entity\Publisher;
use App\Entity\Author;
use App\Repository\BookRepository;
use App\Repository\RepositoryHelper;
use PHPUnit\Framework\TestCase;

class BookServiceTest extends TestCase
{
    /** RepositoryHelper */
    private $repositoryHelper;

    public function __construct()
    {
        parent::__construct();

        $this->repositoryHelper = new RepositoryHelper();
    }

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
        /** @var Book $book1 */
        $book1 = new Book([
            'id' => 1,
            'title' => 'BookOne',
            'description' => 'Desc one',
            'cover_url' => 'http://okok.com',
            'isbn' => '51651651561651',
            'featured' => true,
            'deleted' => false,
            'author' => $author1,
            'publisher' => $publisher1,
        ]);
        /** @var Book $book2 */
        $book2 = new Book([
            'id' => 2,
            'title' => 'BookTwo',
            'description' => 'Desc Two',
            'cover_url' => 'http://okok2.com',
            'isbn' => '1518164681',
            'featured' => true,
            'deleted' => false,
            'author' => $author2,
            'publisher' => $publisher2,
        ]);
        $this->assertEquals(1, $book1->getId());

        $bookRepositoryMock->expects($this->once())
            ->method('findBy')
            ->with($this->equalTo([
                'featured' => true,
                'deleted' => false,
            ]))
            ->willReturn([$book1, $book2]);

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
                'featured' => true,
                'deleted' => false,
            ]))
            ->willReturn([
                new Book(['id' => 1]),
                new Book(['id' => 2]),
            ]);

        /** BookService $bookServiceToTest */
        $bookServiceToTest = new BookService();
        $numberOfBooks = $bookServiceToTest->getNumberOfBooksFeatured($bookRepositoryMock);
        $this->assertEquals(2, $numberOfBooks);
    }

    public function testGetAllWithMatchingTitle()
    {
        /** MockObject $bookRepositoryMock */
        $bookRepositoryMock = $this->getMockBuilder(BookRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $bookRepositoryMock->expects($this->once())
            ->method('getAllWithMatchingTitle')
            ->with(
                $this->equalTo($this->repositoryHelper),
                $this->equalTo('SearchedTitle'),
                $this->equalTo(0),
                $this->equalTo(20)
            )
            ->willReturn([
                new Book(['id' => 1, 'title' => 'The SearchedTitle1']),
                new Book(['id' => 2, 'title' => 'The SearchedTitle2']),
            ]);

        /** BookService $bookServiceToTest */
        $bookServiceToTest = new BookService();
        $booksMatched = $bookServiceToTest->getAllWithMatchingTitle($bookRepositoryMock, 'SearchedTitle', 0 ,20);
        $this->assertEquals(2, count($booksMatched));
    }

    public function testGetNumberOfBooksWithMatchingTitle()
    {
        /** MockObject $bookRepositoryMock */
        $bookRepositoryMock = $this->getMockBuilder(BookRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $bookRepositoryMock->expects($this->once())
            ->method('getNumberOfBooksWithMatchingTitle')
            ->with($this->equalTo('SearchedTitle'))
            ->willReturn(2);

        /** BookService $bookServiceToTest */
        $bookServiceToTest = new BookService();
        $numberBooksMatched = $bookServiceToTest->getNumberOfBooksWithMatchingTitle($bookRepositoryMock, 'SearchedTitle');
        $this->assertEquals(2, $numberBooksMatched);
    }

}
