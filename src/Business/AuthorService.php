<?php

namespace App\Business;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use PHPUnit\Framework\MockObject\MockObject;

class AuthorService
{
    /**
     * @param AuthorRepository|MockObject $authorRepository
     *
     * @return array
     */
    public function getAllNonDeleted(AuthorRepository $authorRepository): array
    {
        /** @var Author[] $authors */
        $authors = $authorRepository->findBy(['deleted' => false]);

        return array_map(
            function(Author $author){return $author->getAttributes();},
            $authors
        );
    }
}
