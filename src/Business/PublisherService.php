<?php

namespace App\Business;

use App\Entity\Publisher;
use App\Repository\PublisherRepository;
use PHPUnit\Framework\MockObject\MockObject;

class PublisherService
{
    /**
     * @param PublisherRepository|MockObject $publisherRepository
     *
     * @return array
     */
    public function getAllNonDeleted(PublisherRepository $publisherRepository): array
    {
        /** @var Publisher[] $publishers */
        $publishers = $publisherRepository->findBy(['deleted' => false]);

        return array_map(function(Publisher $pub){return $pub->getAttributes();}, $publishers);
    }
}
