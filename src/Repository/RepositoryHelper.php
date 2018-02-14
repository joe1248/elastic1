<?php

namespace App\Repository;

class RepositoryHelper
{
    /**
     * @param array $entities
     *
     * @return array
     */
    public function sanitiseResults(array $entities): array
    {
        $sanitised = [];
        foreach ($entities as $entity) {
            $sanitised[] = $entity->getAttributes();
        }

        return $sanitised;
    }

}