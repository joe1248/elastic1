<?php

namespace App\Business;

use App\Entity\Book;
use App\Repository\BookRepository;
use PHPUnit\Framework\MockObject\MockObject;

class BookService
{
    /**
     * @param BookRepository|MockObject $bookRepository
     *
     * @return array
     */
    public function getAllFeaturedAndNonDeleted(BookRepository $bookRepository): array
    {
        /** @var Book[] $books */
        $books = $bookRepository->findBy([
     //       'featured' => true,
            'deleted' => false
        ]);

        return array_map(
            function(Book $book){return $book->getAttributes();},
            $books
        );
    }
}
