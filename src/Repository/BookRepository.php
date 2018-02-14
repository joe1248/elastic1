<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\ORM\EntityRepository;

class BookRepository extends EntityRepository
{
    /**
     * @param RepositoryHelper $repositoryHelper
     * @param string $searched
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getAllWithMatchingTitle(RepositoryHelper $repositoryHelper, string $searched, int $offset, int $limit): array
    {
        /** @var Book[] $books */
        $books = $this->createQueryBuilder('u')
            ->where('u.title LIKE :searched')
            ->andWhere('u.deleted = 0')
            ->orderBy('u.id')
            ->setParameter('searched', '%' . addcslashes($searched, '%_') . '%')
            ->getQuery()
            ->setFirstResult( $offset )
            ->setMaxResults( $limit )
            ->getResult();

        return $repositoryHelper->sanitiseResults($books);
    }

    /**
     * @param string $searched
     *
     * @return string
     */
    public function getNumberOfBooksWithMatchingTitle(string $searched): string
    {
        $result = $this->createQueryBuilder('u')
            ->select('COUNT(u.id) as numberOfBooksMatched')
            ->where('u.title LIKE :searched')
            ->andWhere('u.deleted = 0')
            ->setParameter('searched', '%' . addcslashes($searched, '%_') . '%')
            ->getQuery()
            ->getScalarResult();

        return $result[0]['numberOfBooksMatched'] ?? 0;
    }
}