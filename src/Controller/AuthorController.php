<?php

namespace App\Controller;

use App\Business\AuthorService;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


class AuthorController extends Controller
{
    /**
     * List all authors non-deleted
     *
     * @param AuthorService $authorService
     *
     * @return JsonResponse
     */
    public function getAll(AuthorService $authorService): JsonResponse
    {
        /** @var AuthorRepository $authorRepository */
        $authorRepository = $this->getDoctrine()->getRepository(Author::class);

        return new JsonResponse(['authors' => $authorService->getAllNonDeleted($authorRepository)]);
    }

    /**
     * Return one author by ID
     *
     * @param string $id
     * @param ControllerHelper $controllerHelper
     *
     * @return JsonResponse
     */
    public function getOne(string $id, ControllerHelper $controllerHelper): JsonResponse
    {
        if (!is_numeric($id)) {
            return $controllerHelper->getBadRequestJsonResponse('invalid value specified for `authorId`');
        }

        /** @var AuthorRepository $authorRepository */
        $authorRepository = $this->getDoctrine()->getRepository(Author::class);
        $author = $authorRepository->find($id);

        if (!$author) {
            return $controllerHelper->getBadRequestJsonResponse('author not found');
        }

        return new JsonResponse($author->getAttributes());
    }
}