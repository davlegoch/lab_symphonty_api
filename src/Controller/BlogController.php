<?php
// src/Controller/BlogController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class BlogController
{
    public function list()
    {
        return new Response(
            '<html><body>Blog:List</body></html>'
        );
    }
	
	public function index()
    {
        return new Response('Salut les gens');
    }
}