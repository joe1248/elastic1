<?php

namespace App\Business;

use App\Entity\Book;
use App\Repository\BookRepository;
use PHPUnit\Framework\MockObject\MockObject;

class BookService
{
    /**
     * @param BookRepository|MockObject $bookRepository
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getAllFeatured(
        BookRepository $bookRepository,
        int $offset,
        int $limit
    ): array
    {
        /** @var Book[] $books */
        $books = $bookRepository->findBy([
            //'featured' => true,
            'deleted' => false
        ],
            null,
            $limit,
            $offset
        );

        return array_map(
            function(Book $book){return $book->getAttributes();},
            $books
        );
    }

    /**
     * @param BookRepository|MockObject $bookRepository
     *
     * @return int
     */
    public function getNumberOfBooksFeatured(BookRepository $bookRepository): int
    {
        /** @var Book[] $books */
        $books = $bookRepository->findBy([
            //'featured' => true,
            'deleted' => false
        ]);

        return count($books);
    }
}
