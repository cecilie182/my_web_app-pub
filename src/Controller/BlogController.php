<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        // charger les posts depuis la bd
        $posts = [];
        return $this->render('Blog/index.html.twig', [
            'posts' => $posts
        ]);
    }
}