<?php

namespace App\Controller;

use App\Business\BookService;
use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


class BookController extends Controller
{
    const MAX_LIMIT_NUMBER_OF_FEATURED_BOOKS = 100;

    /**
     * List all books highlighted/featured
     *
     * @param string $offset
     * @param string $limit
     * @param BookService $bookService
     * @param ControllerHelper $controllerHelper
     *
     * @return JsonResponse
     */
    public function getAllFeatured(
        string $offset,
        string $limit,
        BookService $bookService,
        ControllerHelper $controllerHelper
    ): JsonResponse
    {
        if (!is_numeric($offset)) {
            return $controllerHelper->getBadRequestJsonResponse('invalid value specified for `offset`');
        }
        if (!is_numeric($limit)) {
            return $controllerHelper->getBadRequestJsonResponse('invalid value specified for `limit`');
        }
        if ($offset < 0) {
            return $controllerHelper->getBadRequestJsonResponse(
                'Invalid value specified for `offset`. Minimum required value is 0.'
            );
        }
        if ($limit > self::MAX_LIMIT_NUMBER_OF_FEATURED_BOOKS) {
            return $controllerHelper->getBadRequestJsonResponse(
                'Invalid value specified for `limit`. ' .
                'Maximum allowed value is ' . self::MAX_LIMIT_NUMBER_OF_FEATURED_BOOKS . '.'
            );
        }

        /** @var BookRepository $bookRepository */
        $bookRepository = $this->getDoctrine()->getRepository(Book::class);

        $jsonResponse = new JsonResponse();
        $jsonResponse->setEncodingOptions(JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT); // all but JSON_HEX_AMP
        $jsonResponse->setData([
            'books' => $bookService->getAllFeatured($bookRepository, $offset, $limit),
            'offset' => $offset,
            'limit' => $limit,
            'total' => $bookService->getNumberOfBooksFeatured($bookRepository),
        ]);

        return $jsonResponse;
    }

    /**
     * List all books whose title matches the $searched string
     *
     * @param string $searched
     * @param string $offset
     * @param string $limit
     * @param BookService $bookService
     * @param ControllerHelper $controllerHelper
     *
     * @return JsonResponse
     */
    public function searchByTitle(
        string $searched,
        string $offset,
        string $limit,
        BookService $bookService,
        ControllerHelper $controllerHelper
    ): JsonResponse
    {
        if (!is_numeric($offset)) {
            return $controllerHelper->getBadRequestJsonResponse('invalid value specified for `offset`');
        }
        if (!is_numeric($limit)) {
            return $controllerHelper->getBadRequestJsonResponse('invalid value specified for `limit`');
        }
        if ($offset < 0) {
            return $controllerHelper->getBadRequestJsonResponse(
                'Invalid value specified for `offset`. Minimum required value is 0.'
            );
        }
        if ($limit > self::MAX_LIMIT_NUMBER_OF_FEATURED_BOOKS) {
            return $controllerHelper->getBadRequestJsonResponse(
                'Invalid value specified for `limit`. ' .
                'Maximum allowed value is ' . self::MAX_LIMIT_NUMBER_OF_FEATURED_BOOKS . '.'
            );
        }

        /** @var BookRepository $bookRepository */
        $bookRepository = $this->getDoctrine()->getRepository(Book::class);

        $jsonResponse = new JsonResponse();
        $jsonResponse->setEncodingOptions(JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT); // all but JSON_HEX_AMP
        $jsonResponse->setData([
            'books' => $bookService->getAllWithMatchingTitle($bookRepository, $searched, $offset, $limit),
            'offset' => $offset,
            'limit' => $limit,
            'total' => $bookService->getNumberOfBooksWithMatchingTitle($bookRepository, $searched),
        ]);

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
        $book = $bookRepository->find($id);

        if (!$book) {
            return $controllerHelper->getBadRequestJsonResponse('book not found');
        }

		return new JsonResponse($book->getAttributes());
	}
}