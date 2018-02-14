<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;

class BookFixtures extends Fixture implements FixtureInterface, DependentFixtureInterface
{
    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            AuthorFixtures::class,
            PublisherFixtures::class,
        ];
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $apiData = json_decode(file_get_contents(__DIR__ . '/books.json'), true);
        $allBooks = $apiData['books'];

        for ($i = 0; $i < count($allBooks); $i++) {
            $bookData = $allBooks[$i];
            $bookData['featured'] = true;
            $bookData['author'] = $this->getReference('author' . $bookData['author']['id']);
            $bookData['publisher'] = $this->getReference('publisher' . $bookData['publisher']['id']);

            $book = new book($bookData);

            $manager->persist($book);
        }

        $manager->flush();
    }
}