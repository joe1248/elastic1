<?php

namespace App\Controller;

use App\Business\PublisherService;
use App\Entity\Publisher;
use App\Repository\PublisherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class PublisherController extends Controller
{
    const CACHE_EXPIRES_AFTER = '1 day';

    /**
     * List all publishers non-deleted
     *
     * @param PublisherService $publisherService
     *
     * @return JsonResponse
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getAll(PublisherService $publisherService): JsonResponse
    {
        $cache = new FilesystemAdapter();
        $cachedItem = $cache->getItem('allPublishers');
        if ($cachedItem->isHit()) {
            return new JsonResponse(['publishers' => $cachedItem->get()]);
        }

        /** @var PublisherRepository $publisherRepository */
        $publisherRepository = $this->getDoctrine()->getRepository(Publisher::class);

        /** @var array $allPublishers */
        $allPublishers = $publisherService->getAllNonDeleted($publisherRepository);
        $cachedItem->expiresAfter(\DateInterval::createFromDateString(self::CACHE_EXPIRES_AFTER));
        $cache->save($cachedItem->set($allPublishers));

        return new JsonResponse(['publishers' => $allPublishers]);
    }

    /**
     * Return one publisher by ID
     *
     * @param string $id
     * @param ControllerHelper $controllerHelper
     *
     * @return JsonResponse
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getOne(string $id, ControllerHelper $controllerHelper): JsonResponse
    {
        if (!is_numeric($id)) {
            return $controllerHelper->getBadRequestJsonResponse('invalid value specified for `publisherId`');
        }

        /** @var PublisherRepository $publisherRepository */
        $publisherRepository = $this->getDoctrine()->getRepository(Publisher::class);

        $cache = new FilesystemAdapter();
        $cachedItem = $cache->getItem('publisher' . $id);

        if ($cachedItem->isHit()) {
            return new JsonResponse($cachedItem->get());
        }

        $publisher = $publisherRepository->find($id);
        if (!$publisher) {
            return $controllerHelper->getBadRequestJsonResponse('publisher not found');
        }

        $publisherData = $publisher->getAttributes();
        $cachedItem->expiresAfter(\DateInterval::createFromDateString(self::CACHE_EXPIRES_AFTER));
        $cache->save($cachedItem->set($publisherData));

        return new JsonResponse($publisherData);
    }
}