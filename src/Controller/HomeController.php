<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class HomeController
{
    public function index(Request $request)
    {
		return new Response(
            phpinfo()
        );
		/*
        return new Response(
            '<html><body>Page d\'accueil de l\'API</body></html>'
        );*/
    }
}