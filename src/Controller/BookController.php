<?php

namespace App\Controller;

use App\Business\BookService;
use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


class BookController extends Controller
{
    /**
     * List all books highlighted/featured non-deleted
     *
     * @param BookService $bookService
     *
     * @return JsonResponse
     */
    public function getAllFeatured(BookService $bookService): JsonResponse
    {
        /** @var BookRepository $bookRepository */
        $bookRepository = $this->getDoctrine()->getRepository(Book::class);

        return new JsonResponse(['books' => $bookService->getAllFeaturedAndNonDeleted($bookRepository)]);
    }

    /**
     * Return one book by ID
     *
     * @param Book $book
     *
     * @return JsonResponse
     */
    public function getOne(Book $book): JsonResponse
    {   
		return new JsonResponse($book->getAttributes());	
	}
}