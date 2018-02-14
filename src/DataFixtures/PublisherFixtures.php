<?php

namespace App\DataFixtures;

use App\Entity\Publisher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PublisherFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $apiData = json_decode(file_get_contents(__DIR__ . '/publishers.json'), true);
        $publishersData = $apiData['publishers'];
        for ($i = 0; $i < count($publishersData); $i++) {
            $publisher = new Publisher($publishersData[$i]);

            $manager->persist($publisher);
        }

        $manager->flush();
    }
}