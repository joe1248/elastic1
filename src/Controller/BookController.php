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

        $jsonResponse = new JsonResponse();
        $jsonResponse->setEncodingOptions(JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT); // all but JSON_HEX_AMP
        $jsonResponse->setData(['books' => $bookService->getAllFeaturedAndNonDeleted($bookRepository)]);

        return $jsonResponse;
    }

    /**
     * Return one book by ID
     *
     * @param string $id
     * @param ControllerHelper $controllerHelper
     *
     * @return JsonResponse
     */
    public function getOne(string $id, ControllerHelper $controllerHelper): JsonResponse
    {
        if (!is_numeric($id)) {
            return $controllerHelper->getBadRequestJsonResponse('invalid value specified for `bookId`');
        }

        /** @var BookRepository $bookRepository */
        $bookRepository = $this->getDoctrine()->getRepository(Book::class);
        $book = $bookRepository->findOneBy(['id' => $id]);

        if (!$book) {
            return $controllerHelper->getBadRequestJsonResponse('book not found');
        }

		return new JsonResponse($book->getAttributes());
	}
}