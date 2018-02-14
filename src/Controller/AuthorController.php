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
     * @param Author $author
     *
     * @return JsonResponse
     */
    public function getOne(Author $author): JsonResponse
    {
		return new JsonResponse($author->getAttributes());	
	}
}