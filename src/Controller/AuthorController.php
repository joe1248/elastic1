<?php

namespace App\Controller;

use App\Business\AuthorService;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class AuthorController extends Controller
{
    const CACHE_EXPIRES_AFTER = '1 day';

    /**
     * List all authors non-deleted
     *
     * @param AuthorService $authorService
     *
     * @return JsonResponse
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getAll(AuthorService $authorService): JsonResponse
    {
        $cache = new FilesystemAdapter();
        $cachedItem = $cache->getItem('allAuthors');
        $cachedItem->expiresAfter(\DateInterval::createFromDateString(self::CACHE_EXPIRES_AFTER));

        if ($cachedItem->isHit()) {
            return new JsonResponse(['authors' => $cachedItem->get()]);
        }

        /** @var AuthorRepository $authorRepository */
        $authorRepository = $this->getDoctrine()->getRepository(Author::class);
        $allAuthors = $authorService->getAllNonDeleted($authorRepository);
        $cache->save($cachedItem->set($allAuthors));

        return new JsonResponse(['authors' => $allAuthors]);
    }

    /**
     * Return one author by ID
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
            return $controllerHelper->getBadRequestJsonResponse('invalid value specified for `authorId`');
        }

        /** @var AuthorRepository $authorRepository */
        $authorRepository = $this->getDoctrine()->getRepository(Author::class);

        $cache = new FilesystemAdapter();
        $cachedItem = $cache->getItem('author' . $id);
        $cachedItem->expiresAfter(\DateInterval::createFromDateString(self::CACHE_EXPIRES_AFTER));
        if ($cachedItem->isHit()) {
            return new JsonResponse($cachedItem->get());
        }

        /** @var Author $author */
        $author = $authorRepository->find($id);
        if (!$author) {
            return $controllerHelper->getBadRequestJsonResponse('author not found');
        }

        $authorData = $author->getAttributes();
        $cachedItem->expiresAfter(\DateInterval::createFromDateString(self::CACHE_EXPIRES_AFTER));
        $cache->save($cachedItem->set($authorData));

        return new JsonResponse($authorData);
    }
}