<?php

namespace App\Controller;

use App\Entity\Publisher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PublisherController extends Controller
{
    /**
     * List all publisher
     *
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {        
        /** @var PublishersRepo $publishersRepo */
        $publishersRepo = $this->getDoctrine()->getRepository(Publisher::class);

        $publishers = $publishersRepo->findBy(['deleted' => false]);

        return new JsonResponse($publishers);
    }
}