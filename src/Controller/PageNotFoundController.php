<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class PageNotFoundController
{
	public function getPageNotFound()
	{
        return new JsonResponse(['error' => [
            'code' => 400,
            'message' => 'Not found'
        ]]);
	}
}