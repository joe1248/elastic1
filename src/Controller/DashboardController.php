<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class DashboardController
{
	public function dashboard()
	{
		return new Response(
			'<html><body>Lucky number: OKOK???</body></html>'
		);
	}
}