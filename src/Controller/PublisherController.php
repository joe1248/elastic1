<?php

namespace App\Controller;

use App\Entity\Publisher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PublisherController extends Controller
{
    /**
     * List all publishers non-deleted
     *
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {        
        /** @var PublishersRepo $publishersRepo */
        $publishersRepo = $this->getDoctrine()->getRepository(Publisher::class);

        /** @var Publisher[] $publishers */
		$publishers = $publishersRepo->findBy(['deleted' => false]);
		
		/** @var array */
		$publishers = array_map(function($pub){return $pub->getAttributes();}, $publishers);
		
        return new JsonResponse($publishers);
    }
	
    /**
     * Return one publisher by ID
     *
     * @return JsonResponse
     */
    public function getOne(Publisher $publisher): JsonResponse
    {   
		return new JsonResponse($publisher->getAttributes());	
	}
}