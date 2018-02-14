<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $apiData = json_decode(file_get_contents(__DIR__ . '/authors.json'), true);
        $authorsData = $apiData['authors'];

        for ($i = 0; $i < count($authorsData); $i++) {
            $author = new author($authorsData[$i]);

            $manager->persist($author);
            $manager->flush();
            $this->addReference('author' . $authorsData[$i]['id'], $author);
        }
    }
}