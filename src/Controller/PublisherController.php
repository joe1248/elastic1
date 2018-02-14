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
     * @param string $id
     * @param ControllerHelper $controllerHelper
     *
     * @return JsonResponse
     */
    public function getOne(string $id, ControllerHelper $controllerHelper): JsonResponse
    {
        if (!is_numeric($id)) {
            return $controllerHelper->getBadRequestJsonResponse('invalid value specified for `publisherId`');
        }

        /** @var PublisherRepository $publisherRepository */
        $publisherRepository = $this->getDoctrine()->getRepository(Publisher::class);
        $publisher = $publisherRepository->findOneBy(['id' => $id]);

        if (!$publisher) {
            return $controllerHelper->getBadRequestJsonResponse('publisher not found');
        }

        return new JsonResponse($publisher->getAttributes());
    }
}