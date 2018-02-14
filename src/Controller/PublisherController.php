<?php

namespace App\Controller;

use App\Business\PublisherService;
use App\Entity\Publisher;
use App\Repository\PublisherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


class PublisherController extends Controller
{
    /**
     * List all publishers non-deleted
     *
     * @param PublisherService $publisherService
     *
     * @return JsonResponse
     */
    public function getAll(PublisherService $publisherService): JsonResponse
    {
        /** @var PublisherRepository $publisherRepository */
        $publisherRepository = $this->getDoctrine()->getRepository(Publisher::class);

        return new JsonResponse(['publishers' => $publisherService->getAllNonDeleted($publisherRepository)]);
    }

    /**
     * Return one publisher by ID
     *
     * @param Publisher $publisher
     *
     * @return JsonResponse
     */
    public function getOne(Publisher $publisher): JsonResponse
    {   
		return new JsonResponse($publisher->getAttributes());	
	}
}