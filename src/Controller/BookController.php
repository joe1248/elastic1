<?php

namespace App\Controller;

use App\Business\BookService;
use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;

class BookController extends Controller
{
    const MAX_LIMIT_NUMBER_OF_FEATURED_BOOKS = 100;
    const CACHE_EXPIRES_AFTER = '1 hour';

    /**
     * List all books highlighted/featured
     *
     * @param string $offset
     * @param string $limit
     * @param BookService $bookService
     * @param ControllerHelper $controllerHelper
     *
     * @param FilesystemAdapter $cache
     * @return JsonResponse
     *
     * @throws \Psr\Cache\InvalidArgumentException
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

        $cache = new FilesystemAdapter();
        $cachedItem = $cache->getItem('allFeaturedBooks_' . $offset . '_' . $limit);
        $cachedItem->expiresAfter(\DateInterval::createFromDateString(self::CACHE_EXPIRES_AFTER));
        if ($cachedItem->isHit()) {
            $featuredBooks = $cachedItem->get();
        } else {
            $featuredBooks = $bookService->getAllFeatured($bookRepository, $offset, $limit);
            $cache->save($cachedItem->set($featuredBooks));
        }

        $cachedItem = $cache->getItem('numberOfBooksFeatured');
        $cachedItem->expiresAfter(\DateInterval::createFromDateString(self::CACHE_EXPIRES_AFTER));
        if ($cachedItem->isHit()) {
            $numberOfBooksFeatured = $cachedItem->get();
        } else {
            $numberOfBooksFeatured = $bookService->getNumberOfBooksFeatured($bookRepository);
            $cache->save($cachedItem->set($numberOfBooksFeatured));
        }

        $jsonResponse = new JsonResponse();
        $jsonResponse->setEncodingOptions(JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT); // all but JSON_HEX_AMP
        $jsonResponse->setData([
            'books' => $featuredBooks,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $numberOfBooksFeatured,
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
     * @param FilesystemAdapter $cache
     *
     * @return JsonResponse
     *
     * @throws \Psr\Cache\InvalidArgumentException
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

        $cache = new FilesystemAdapter();
        $cachedItem = $cache->getItem('numberOfBooksMatched_' . $offset . '_' . $limit . '_' . $searched);
        $cachedItem->expiresAfter(\DateInterval::createFromDateString(self::CACHE_EXPIRES_AFTER));
        if ($cachedItem->isHit()) {
            $matchedBooks = $cachedItem->get();
        } else {
            $matchedBooks = $bookService->getAllWithMatchingTitle($bookRepository, $searched, $offset, $limit);
            $cache->save($cachedItem->set($matchedBooks));
        }

        $cachedItem = $cache->getItem('numberOfBooksMatched' . $searched);
        $cachedItem->expiresAfter(\DateInterval::createFromDateString(self::CACHE_EXPIRES_AFTER));
        if ($cachedItem->isHit()) {
            $numberOfMatchedBooks = $cachedItem->get();
        } else {
            $numberOfMatchedBooks = $bookService->getNumberOfBooksWithMatchingTitle($bookRepository, $searched);
            $cache->save($cachedItem->set($numberOfMatchedBooks));
        }

        $jsonResponse = new JsonResponse();
        $jsonResponse->setEncodingOptions(JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT); // all but JSON_HEX_AMP
        $jsonResponse->setData([
            'books' => $matchedBooks,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $numberOfMatchedBooks,
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
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getOne(string $id, ControllerHelper $controllerHelper): JsonResponse
    {
        if (!is_numeric($id)) {
            return $controllerHelper->getBadRequestJsonResponse('invalid value specified for `bookId`');
        }

        $cache = new FilesystemAdapter();
        $cachedItem = $cache->getItem('BookView_' . $id);
        if ($cachedItem->isHit()) {
            return new JsonResponse($cachedItem->get());
        }

        /** @var BookRepository $bookRepository */
        $bookRepository = $this->getDoctrine()->getRepository(Book::class);
        $book = $bookRepository->find($id);

        if (!$book) {
            return $controllerHelper->getBadRequestJsonResponse('book not found');
        }

        $bookData = $book->getAttributes();
        $cachedItem->expiresAfter(\DateInterval::createFromDateString(self::CACHE_EXPIRES_AFTER));
        $cache->save($cachedItem->set($bookData));

		return new JsonResponse($bookData);
	}
}